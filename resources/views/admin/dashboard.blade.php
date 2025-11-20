<x-app-layout>
    <x-slot:title>
        Command Center
    </x-slot:title>

    <h2 class="text-xl text-glow md:text-2xl font-bold text-primary mb-6"> > [ COMMAND CENTER ] </h2>

    {{-- 1. ALERTS SECTION --}}
    @include('admin.partials.alerts')

    {{-- Grid Container dengan Alpine Data Scope untuk Modal --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ isModalOpen: false, applicant: null }">

        {{-- 2. PENDING APPLICATIONS (KOLOM KIRI) --}}
        @include('admin.partials.pending-apps')

        {{-- 3. INVITE TOKENS (KOLOM KANAN) --}}
        @include('admin.partials.invite-tokens')

        {{-- 4. APPLICANT MODAL (POPUP) --}}
        {{-- Kita include di sini agar tetap dalam scope x-data --}}
        @include('admin.partials.applicant-modal')

    </div>

    @include('admin.partials.system-controls')

    {{-- 5. AGENT BROADCAST (BAWAH) --}}
    @include('admin.partials.broadcast-form')

</x-app-layout>
