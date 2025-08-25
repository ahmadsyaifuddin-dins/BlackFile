// resources/js/forms/archive-form.js

export default function archiveForm() {
    return {
        // Form data dengan nilai default dari Laravel old() helpers
        formData: {
            name: window.archiveFormData?.name || '',
            description: window.archiveFormData?.description || '',
            is_public: window.archiveFormData?.is_public || false,
            type: window.archiveFormData?.type || 'file',
            links: window.archiveFormData?.links || '',
            _token: window.archiveFormData?.csrf_token || ''
        },
        
        // State untuk file upload
        fileToUpload: null,
        
        // State untuk loading dan progress
        isUploading: false,
        progress: 0,
        
        // State untuk error dan success messages
        errors: {},
        successMessage: '',
        
        // URLs dari window object
        storeUrl: window.archiveFormUrls?.store || '',
        
        /**
         * Handle form submission
         */
        submitForm() {
            // Reset state
            this.isUploading = true;
            this.progress = 0;
            this.errors = {};
            this.successMessage = '';

            // Debug: log form data
            console.log('Form Data:', this.formData);
            console.log('File to upload:', this.fileToUpload);
            console.log('Store URL:', this.storeUrl);

            // Prepare form data
            const data = new FormData();
            
            // Append form data sesuai dengan yang diharapkan Controller
            data.append('name', this.formData.name || '');
            data.append('description', this.formData.description || '');
            data.append('type', this.formData.type || 'file');
            data.append('_token', this.formData._token);
            
            // Handle is_public: Controller menggunakan $request->has('is_public')
            // Jadi kita hanya kirim field ini jika checkbox dicentang
            if (this.formData.is_public) {
                data.append('is_public', '1');
            }
            
            // Handle links untuk URL type
            if (this.formData.type === 'url' && this.formData.links) {
                data.append('links', this.formData.links);
            }

            // Handle file upload validation
            if (this.formData.type === 'file') {
                if (this.fileToUpload) {
                    data.append('archive_file', this.fileToUpload);
                } else {
                    this.errors = { 
                        archive_file: ['Please select a file to upload.'] 
                    };
                    this.isUploading = false;
                    return;
                }
            }

            // Debug: log FormData content
            console.log('FormData entries:');
            for (let [key, value] of data.entries()) {
                console.log(key, value);
            }
            
            // Make axios request
            axios.post(this.storeUrl, data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                onUploadProgress: (event) => {
                    if (event.lengthComputable) {
                        this.progress = Math.round((event.loaded * 100) / event.total);
                    }
                },
            })
            .then(response => {
                this.handleSuccess(response);
            })
            .catch(error => {
                this.handleError(error);
            })
            .finally(() => {
                this.isUploading = false;
            });
        },

        /**
         * Handle successful form submission
         */
        handleSuccess(response) {
            this.successMessage = response.data.message + ' Redirecting...';
            
            // Redirect after delay
            setTimeout(() => {
                if (response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else {
                    // Fallback redirect
                    window.location.href = window.archiveFormUrls?.index || '/archives';
                }
            }, 1500);
        },

        /**
         * Handle form submission errors
         */
        handleError(error) {
            console.error('Archive form error:', error);
            
            // Log response details for debugging
            if (error.response) {
                console.error('Error status:', error.response.status);
                console.error('Error data:', error.response.data);
                console.error('Error headers:', error.response.headers);
            }
            
            if (error.response && error.response.status === 422) {
                // Validation errors
                this.errors = error.response.data.errors || {};
                
                // Also log validation errors to console for debugging
                console.error('Validation errors:', this.errors);
                
                // Show first validation error as alert for user feedback
                const firstError = Object.values(this.errors)[0];
                if (firstError && firstError.length > 0) {
                    alert('Validation Error: ' + firstError[0]);
                }
            } else if (error.response && error.response.data?.message) {
                // Server error with message
                alert('Error: ' + error.response.data.message);
            } else if (error.message) {
                // Network or other axios error
                alert('Network Error: ' + error.message);
            } else {
                // Generic error
                alert('An unexpected error occurred. Please try again.');
            }
        },

        /**
         * Handle file selection
         */
        handleFileSelect(event) {
            this.fileToUpload = event.target.files[0];
            
            // Clear file upload errors when file is selected
            if (this.errors.archive_file) {
                delete this.errors.archive_file;
            }
        },

        /**
         * Reset form to initial state
         */
        resetForm() {
            this.formData = {
                name: '',
                description: '',
                is_public: false,
                type: 'file',
                links: '',
                _token: window.archiveFormData?.csrf_token || ''
            };
            this.fileToUpload = null;
            this.errors = {};
            this.successMessage = '';
            this.progress = 0;
        },

        /**
         * Check if form has any errors
         */
        hasErrors() {
            return Object.keys(this.errors).length > 0;
        },

        /**
         * Get error message for specific field
         */
        getError(fieldName) {
            return this.errors[fieldName] ? this.errors[fieldName][0] : '';
        }
    }
}