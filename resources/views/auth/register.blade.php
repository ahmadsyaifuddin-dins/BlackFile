<x-layout>
    <x-slot:title>REGISTER NEW FILE</x-slot:title>

    @php
        $i18n = [
            'en' => json_decode(file_get_contents(base_path('lang/en.json')), true) ?: [],
            'id' => json_decode(file_get_contents(base_path('lang/id.json')), true) ?: [],
        ];
    @endphp

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-8 bg-base">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- ============================ FORM (LEFT) ============================ --}}
            <div class="lg:col-span-3 bg-surface/80 border-2 border-border-color flex flex-col overflow-hidden shadow-2xl">

                {{-- Header + Language toggle --}}
                <div class="px-6 py-4 border-b border-border-color flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[10px] tracking-[0.3em] text-primary" data-i18n="// AUTHORIZED RECRUITMENT CHANNEL">{{ __('// AUTHORIZED RECRUITMENT CHANNEL') }}</p>
                        <h2 class="text-xl sm:text-2xl font-bold text-primary text-glow">
                            [ <span data-i18n="REGISTER NEW DOSSIER">{{ __('REGISTER NEW DOSSIER') }}</span> ]
                        </h2>
                    </div>

                    {{-- Pilihan bahasa (bendera + nama), bukan dropdown --}}
                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <button type="button" data-lang="id"
                            class="lang-btn cursor-pointer px-3 py-2 border-2 rounded text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors">
                            <span class="text-base leading-none">🇮🇩</span> <span>INDONESIA</span>
                        </button>
                        <button type="button" data-lang="en"
                            class="lang-btn cursor-pointer px-3 py-2 border-2 rounded text-xs font-bold tracking-wider flex items-center gap-1.5 transition-colors">
                            <span class="text-base leading-none">🇬🇧</span> <span>ENGLISH</span>
                        </button>
                    </div>
                </div>

                <div class="p-6 sm:p-8 flex-1">
                    @if(session('pending_approval'))
                        {{-- Sukses ajukan tanpa token --}}
                        <div class="text-center text-glow space-y-4 py-6">
                            <i class="fa-solid fa-circle-check text-5xl text-primary"></i>
                            <p class="text-primary-hover font-bold text-lg" data-i18n="APPLICATION SUBMITTED">{{ __('APPLICATION SUBMITTED') }}</p>
                            <p class="text-secondary text-sm max-w-md mx-auto" data-i18n="Your dossier has been successfully submitted. It is now awaiting review and confirmation from the Directorate. You will be notified via email upon approval.">{{ __('Your dossier has been successfully submitted. It is now awaiting review and confirmation from the Directorate. You will be notified via email upon approval.') }}</p>
                            <div class="pt-4">
                                <a href="{{ route('welcome') }}"
                                    class="inline-block px-4 py-2 bg-surface border border-border-color text-secondary hover:text-white font-bold tracking-widest rounded-md text-sm">
                                    [ <span data-i18n="RETURN TO WELCOME PAGE">{{ __('RETURN TO WELCOME PAGE') }}</span> ]
                                </a>
                            </div>
                        </div>
                    @else
                        @if($errors->any())
                            <div class="mb-4 bg-red-900/50 border-l-4 border-red-500 text-red-300 p-4 rounded-r-lg" role="alert">
                                <p class="font-bold">> <span data-i18n="DATA INPUT ANOMALY">{{ __('DATA INPUT ANOMALY') }}</span>:</p>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            {{-- REAL NAME --}}
                            <div>
                                <label for="name" class="block text-primary text-sm">> <span data-i18n="REAL NAME">{{ __('REAL NAME') }}</span></label>
                                <x-forms.input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="{{ __('REGISTER_EX_NAME') }}" data-i18n-placeholder="REGISTER_EX_NAME" />
                                <p class="text-xs text-secondary/70 mt-1" data-i18n="// Your real name as written on official records.">{{ __('// Your real name as written on official records.') }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                {{-- USERNAME --}}
                                <div>
                                    <label for="username" class="block text-primary text-sm">> <span data-i18n="USERNAME">{{ __('USERNAME') }}</span></label>
                                    <x-forms.input type="text" id="username" name="username" value="{{ old('username') }}" required
                                        placeholder="{{ __('REGISTER_EX_USERNAME') }}" data-i18n-placeholder="REGISTER_EX_USERNAME" />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Login ID you will use every time you enter the system.">{{ __('// Login ID you will use every time you enter the system.') }}</p>
                                </div>
                                {{-- CODENAME --}}
                                <div>
                                    <label for="codename" class="block text-primary text-sm">> <span data-i18n="CODENAME">{{ __('CODENAME') }}</span></label>
                                    <x-forms.input type="text" id="codename" name="codename" value="{{ old('codename') }}" required
                                        placeholder="{{ __('REGISTER_EX_CODENAME') }}" data-i18n-placeholder="REGISTER_EX_CODENAME" />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Your operative alias. Keep it sharp and unique.">{{ __('// Your operative alias. Keep it sharp and unique.') }}</p>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div>
                                <label for="email" class="block text-primary text-sm">> <span data-i18n="EMAIL">{{ __('EMAIL') }}</span></label>
                                <x-forms.input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    placeholder="{{ __('REGISTER_EX_EMAIL') }}" data-i18n-placeholder="REGISTER_EX_EMAIL" />
                                <p class="text-xs text-secondary/70 mt-1" data-i18n="// Recovery address. Dossier notifications are sent here.">{{ __('// Recovery address. Dossier notifications are sent here.') }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                {{-- PASSCODE --}}
                                <div>
                                    <label for="password" class="block text-primary text-sm">> <span data-i18n="PASSCODE">{{ __('PASSCODE') }}</span></label>
                                    <x-forms.input type="password" id="password" name="password" required
                                        placeholder="{{ __('REGISTER_EX_PASSCODE') }}" data-i18n-placeholder="REGISTER_EX_PASSCODE" />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Minimum 8 characters. Mix letters and numbers.">{{ __('// Minimum 8 characters. Mix letters and numbers.') }}</p>
                                </div>
                                {{-- CONFIRM PASSCODE --}}
                                <div>
                                    <label for="password_confirmation" class="block text-primary text-sm">> <span data-i18n="CONFIRM PASSCODE">{{ __('CONFIRM PASSCODE') }}</span></label>
                                    <x-forms.input type="password" id="password_confirmation" name="password_confirmation" required
                                        placeholder="{{ __('REGISTER_EX_PASSCODE') }}" data-i18n-placeholder="REGISTER_EX_PASSCODE" />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Repeat your passcode exactly.">{{ __('// Repeat your passcode exactly.') }}</p>
                                </div>
                            </div>

                            {{-- INVITE TOKEN --}}
                            <div>
                                <label for="invite_code" class="block text-primary text-sm">> <span data-i18n="INVITE TOKEN (Optional)">{{ __('INVITE TOKEN (Optional)') }}</span></label>
                                <x-forms.input type="text" id="invite_code" name="invite_code" value="{{ old('invite_code', $inviteCode) }}"
                                    placeholder="{{ __('REGISTER_EX_INVITE') }}" data-i18n-placeholder="REGISTER_EX_INVITE" />
                                <p class="text-xs text-secondary/70 mt-1" data-i18n="// Optional. A valid invite token skips the approval queue.">{{ __('// Optional. A valid invite token skips the approval queue.') }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-gray-700 pt-5">
                                {{-- GENDER (opsional) --}}
                                <div>
                                    <label class="block text-primary text-sm">> <span data-i18n="GENDER">{{ __('GENDER') }}</span></label>
                                    <x-forms.gender-picker name="gender" :value="old('gender')" :required="true" />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Required. Select your gender.">{{ __('// Required. Select your gender.') }}</p>
                                </div>
                                {{-- DATE OF BIRTH (opsional) --}}
                                <div>
                                    <label for="date_of_birth" class="block text-primary text-sm">> <span data-i18n="DATE OF BIRTH">{{ __('DATE OF BIRTH') }}</span></label>
                                    <x-forms.input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required />
                                    <p class="text-xs text-secondary/70 mt-1" data-i18n="// Required. Used for identity verification.">{{ __('// Required. Used for identity verification.') }}</p>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="cursor-pointer w-full px-6 py-3 bg-black text-primary text-base hover:brightness-150 transition-colors font-bold tracking-widest rounded-md border border-primary">
                                    [ <span data-i18n="SUBMIT APPLICATION">{{ __('SUBMIT APPLICATION') }}</span> ]
                                </button>
                            </div>
                        </form>

                        <p class="text-xs text-secondary mt-4 text-center">
                            <span data-i18n="Already have an account?">{{ __('Already have an account?') }}</span>
                            <a href="{{ route('login') }}" class="text-primary hover:underline" data-i18n="Login">{{ __('Login') }}</a>
                        </p>
                    @endif
                </div>

                {{-- Penjelasan singkat: form ini untuk apa --}}
                <div class="px-6 py-3 border-t border-border-color bg-black/40">
                    <p class="text-xs text-secondary/70 leading-relaxed" data-i18n="// This form registers your dossier into the BlackFile system. Review your data, then submit. Fields marked (OPTIONAL) are recommended but not required.">{{ __('// This form registers your dossier into the BlackFile system. Review your data, then submit. Fields marked (OPTIONAL) are recommended but not required.') }}</p>
                </div>
            </div>

            {{-- ============================ LOGO PANEL (RIGHT) ============================ --}}
            <div class="theme-terminal hidden lg:flex lg:col-span-2 relative bg-black border-2 border-primary overflow-hidden flex-col items-center justify-center p-8 text-center">
                {{-- corner brackets --}}
                <span class="absolute top-2 left-2 w-5 h-5 border-t-2 border-l-2 border-primary"></span>
                <span class="absolute top-2 right-2 w-5 h-5 border-t-2 border-r-2 border-primary"></span>
                <span class="absolute bottom-2 left-2 w-5 h-5 border-b-2 border-l-2 border-primary"></span>
                <span class="absolute bottom-2 right-2 w-5 h-5 border-b-2 border-r-2 border-primary"></span>

                {{-- scanline overlay --}}
                <div class="absolute inset-0 scan-overlay opacity-60 pointer-events-none"></div>

                <img src="{{ asset('app-icon.png') }}" alt="BLACKFILE"
                    class="relative z-10 w-40 h-40 object-contain rounded-full border-4 border-primary shadow-[0_0_45px_rgba(46,160,67,0.35)] animate-pulse">

                <h1 class="relative z-10 mt-6 text-2xl xl:text-3xl font-black text-white text-glow tracking-[0.3em]">BLACK FILE</h1>
                <p class="relative z-10 mt-2 text-primary font-mono text-sm tracking-widest" data-i18n="// SECURE SYSTEM">{{ __('// SECURE SYSTEM') }}</p>

                <div class="relative z-10 mt-8 space-y-2 text-xs text-secondary font-mono text-left w-full max-w-[220px]">
                    <p><span class="text-primary">&gt; </span><span data-i18n="ENCRYPTED DOSSIERS">{{ __('ENCRYPTED DOSSIERS') }}</span></p>
                    <p><span class="text-primary">&gt; </span><span data-i18n="REALTIME PRESENCE">{{ __('REALTIME PRESENCE') }}</span></p>
                    <p><span class="text-primary">&gt; </span><span data-i18n="CLEARANCE-CONTROLLED">{{ __('CLEARANCE-CONTROLLED') }}</span></p>
                </div>

                <p class="relative z-10 mt-10 text-[10px] tracking-[0.3em] text-secondary">© 2026 BLACKFILE</p>
            </div>
        </div>
    </div>

    <script>
        window.__BFLANGS = @json($i18n);

        (function () {
            function applyLang(lang) {
                const dict = (window.__BFLANGS && window.__BFLANGS[lang]) || {};

                document.querySelectorAll('[data-i18n]').forEach(function (el) {
                    const key = el.getAttribute('data-i18n');
                    if (dict[key]) el.textContent = dict[key];
                });

                document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
                    const key = el.getAttribute('data-i18n-placeholder');
                    if (dict[key]) el.setAttribute('placeholder', dict[key]);
                });

                document.querySelectorAll('.lang-btn').forEach(function (btn) {
                    const active = btn.getAttribute('data-lang') === lang;
                    btn.classList.toggle('border-primary', active);
                    btn.classList.toggle('text-primary', active);
                    btn.classList.toggle('bg-black/40', active);
                    btn.classList.toggle('border-border-color', !active);
                    btn.classList.toggle('text-secondary', !active);
                });

                document.documentElement.setAttribute('lang', lang);
                try { localStorage.setItem('bf_register_lang', lang); } catch (e) {}
            }

            document.addEventListener('DOMContentLoaded', function () {
                let saved = 'id';
                try { saved = localStorage.getItem('bf_register_lang') || 'id'; } catch (e) {}
                applyLang(saved);

                document.querySelectorAll('.lang-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        applyLang(btn.getAttribute('data-lang'));
                    });
                });
            });
        })();
    </script>
</x-layout>
