@props([
    'archive' => null,
    'categories' => [],
])

@php
    $isEdit = !is_null($archive);
    $endpoint = $isEdit ? route('archives.update', $archive) : route('archives.store');
    $method   = $isEdit ? 'PUT' : 'POST';
    
    $linksArray = [];
    if ($isEdit && $archive->type === 'url' && $archive->links) {
        $linksArray = is_array($archive->links) ? $archive->links : [];
    }
    $linksString = implode("\n", $linksArray);
@endphp

<div class="bg-surface border border-border-color rounded-md shadow-lg"
     x-data="{
        form: {
            name: {{ Js::from(old('name', $archive->name ?? '')) }},
            description: {{ Js::from(old('description', $archive->description ?? '')) }},
            preview_image_url: {{ Js::from(old('preview_image_url', $archive->preview_image_url ?? '')) }},
            category: {{ Js::from(old('category', $archive->category ?? '')) }},
            category_other: {{ Js::from(old('category_other', $archive->category_other ?? '')) }},
            tags: {{ Js::from(old('tags', $isEdit ? ($archive->tags->pluck('name')->implode(',') ?? '') : '')) }},
            is_public: {{ old('is_public', $archive->is_public ?? 0) ? 'true' : 'false' }},
            type: {{ Js::from(old('type', $archive->type ?? 'file')) }},
            links: {{ Js::from(old('links', $linksString)) }}
        },
        file: null,
        isUploading: false,
        isGeneratingAi: false, // State untuk loading AI
        uploadPercentage: 0,
        errors: {},
        errorMessage: '',
        endpoint: '{{ $endpoint }}',
        method: '{{ $method }}',
        csrf: '{{ csrf_token() }}',
        availableCategories: @js($categories),
        targetUrl: @js($targetUrl ?? route('archives.index')),
        returnUrl: @js($returnUrl ?? ''),

        init() {
            if (this.form.category && 
                !this.availableCategories.includes(this.form.category) && 
                this.form.category !== 'Other') {
                this.form.category = 'Other';
            }
        },

        checkCategory() {
            if (this.form.category !== 'Other') {
                this.form.category_other = '';
            }
        },

        handleFileChange(e) {
            this.file = e.target.files[0] || null;
        },

        // FITUR BARU: Generate AI Description
        generateAiDescription() {
            if (!this.form.name) {
                window.agentAlert?.('warning', 'MISSING INPUT', 'Mohon isi nama entri terlebih dahulu!');
                return;
            }

            this.isGeneratingAi = true;
            
            axios.post('{{ route('archives.generate-ai-desc') }}', {
                title: this.form.name
            })
            .then(response => {
                // Isi textarea description dengan hasil AI
                this.form.description = response.data.description;
            })
            .catch(err => {
                console.error(err);
                window.agentAlert?.('error', 'AI GENERATION FAILED', 'Gagal generate deskripsi AI. Cek console.');
            })
            .finally(() => {
                this.isGeneratingAi = false;
            });
        },

        submitHandler() {
            this.isUploading = true;
            this.uploadPercentage = 0;
            this.errors = {};
            this.errorMessage = '';

            const fd = new FormData();
            if (this.method === 'PUT') {
                fd.append('_method', 'PUT');
            }

            Object.keys(this.form).forEach(key => {
                const value = this.form[key];
                if (value === null || value === undefined) return;
                
                if (key === 'is_public') {
                    fd.append(key, (value === true || value === 'true' || value === 1 || value === '1') ? '1' : '0');
                } else {
                    fd.append(key, value);
                }
            });

            if (this.form.type === 'file' && this.file) {
                fd.append('archive_file', this.file);
            }

            // Kembalikan filter + halaman paginasi aktif ke server.
            // Tanpa ini, URL balik polos (?focus=xx) dan filter ikut hilang.
            if (this.returnUrl) {
                fd.append('return_url', this.returnUrl);
            }

            axios.post(this.endpoint, fd, {
                headers: {
                    'X-CSRF-TOKEN': this.csrf,
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (e) => {
                    if (e.total) {
                        this.uploadPercentage = Math.round((e.loaded * 100) / e.total);
                    }
                }
            })
            .then(res => {
                window.agentAlert?.('success', 'ARCHIVE UPDATED', 'Perubahan berhasil disimpan.');
                // Prioritaskan URL yang dihitung server (ikut tahu filter masih cocok atau tidak)
                window.location.href = res.data?.redirect_url || this.targetUrl;
            })
            .catch(err => {
                this.isUploading = false;
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors || {};
                    this.errorMessage = 'Please correct the errors below.';
                    this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    this.errorMessage = err.response?.data?.message || 'Upload failed! Please try again.';
                }
            });
        }
     }">

    <form @submit.prevent="submitHandler">
        <div class="p-6 space-y-6 font-mono">
            
            @include('archives.partials._form-header')
            
            @include('archives.partials._form-basic-info')
            
            @include('archives.partials._form-category', ['categories' => $categories])
            
            @include('archives.partials._form-entry-type', [
                'isEdit' => $isEdit,
                'archive' => $archive
            ])

        </div>

        @include('archives.partials._form-footer', ['isEdit' => $isEdit])
    </form>
</div>