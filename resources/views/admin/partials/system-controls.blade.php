<div class="bg-surface border border-border-color rounded-lg p-4 mt-6 mb-6">
    <h3 class="text-xl font-bold text-white mb-4 border-b border-border-color pb-2">> SYSTEM OVERRIDE</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Panggil Komponen Modular Kita --}}
        <x-terminal-toggle label="ENTITY REGISTRATION ALERT" key="entity_notify_enabled" :checked="$entityNotifyEnabled"
            url="{{ route('admin.setting.toggle') }}" />

        {{-- Contoh Placeholder untuk Masa Depan (Modular!) --}}
        {{-- 
        <x-terminal-toggle 
            label="AUTO-ARCHIVE PROTOCOL" 
            key="auto_archive"
            :checked="false"
            url="{{ route('admin.setting.toggle') }}"
        /> 
        --}}
    </div>
</div>
