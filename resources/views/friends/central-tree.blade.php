<x-app-layout>
    <x-slot:title>Central Tree Friend</x-slot>

    <div class="p-6">
        <h1 class="text-2xl mb-4 text-green-400 font-bold">
            Central Tree: {{ $rootUser->codename }}
        </h1>
        <div id="tree-container" style="width: 100%; height: 600px;"></div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var chart_config = {
            chart: {
                container: "#tree-container",
                connectors: { type: "step", style: { "stroke": "#00ff88", "stroke-width": 2 } },
                node: { HTMLclass: "intel-node" },
                levelSeparation: 50,
                siblingSeparation: 40,
                subTeeSeparation: 40
            },
            nodeStructure: {
                text: { name: "{{ $rootUser->codename }}", title: "{{ $rootUser->role->alias }}" },
                children: @json($treantChildren)
            }
        };
        new window.Treant(chart_config);
    });
    </script>

    <style>
        body { background-color: #000; }
        .intel-node {
            background-color: transparent;
            border: 2px solid #00ff88;
            color: #00ff88;
            padding: 5px 10px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
    @endpush
</x-app-layout>
