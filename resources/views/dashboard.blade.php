<x-app-layout>
    <x-slot:title>
        {{ __('SYSTEM COMMAND DASHBOARD') }}
    </x-slot:title>

    {{-- Header & Time --}}
    <x-dashboard.header />

    {{-- Direct Actions --}}
    <x-dashboard.actions />

    {{-- Global Monitoring --}}
    <x-dashboard.monitoring />

    {{-- Grid Layout untuk Widget --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 font-mono">
        
        {{-- Key Metrics --}}
        <x-dashboard.metrics 
            :totalAgents="$totalAgents"
            :totalEntities="$totalEntities"
            :totalPrototypes="$totalPrototypes"
            :totalPublicArchives="$totalPublicArchives"
            :totalUrlArchives="$totalUrlArchives"
            :totalFileArchives="$totalFileArchives"
            :totalPhysicalStorage="$totalPhysicalStorage"
        />

        {{-- Project Status --}}
        <x-dashboard.project-status 
            :statuses="$projectStatuses" 
            :total="$totalPrototypes" 
        />

        {{-- Data Classification --}}
        <x-dashboard.data-classification 
            :ranks="$entityRankDistribution" 
        />

        {{-- Recent Activity --}}
        <x-dashboard.activity-log 
            :activities="$recentActivities" 
        />

        {{-- System Alert --}}
        <x-dashboard.system-alert 
            :alert="$systemAlert" 
        />

        {{-- Agent Network --}}
        <x-dashboard.agent-network 
            :agents="$activeAgents" 
        />

    </div>
</x-app-layout>