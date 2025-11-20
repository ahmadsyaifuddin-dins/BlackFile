<x-app-layout title="EXIF INTEL // OSINT">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 min-h-screen flex flex-col gap-6">

        <div class="flex justify-between items-end border-b border-cyan-900/50 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-cyan-400 tracking-widest">
                    <i class="fa-solid fa-crosshairs mr-2"></i>EXIF_INTEL
                </h2>
                <p class="text-xs text-cyan-600/70 font-mono mt-1">Digital Forensics & Metadata Extraction Module</p>
            </div>
            <x-button variant="outline" href="{{ route('tools.index') }}" class="text-xs border-cyan-900 text-cyan-700 hover:border-cyan-500 hover:text-cyan-400">
                [ RETURN TO BASE ]
            </x-button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-surface border border-cyan-900/30 rounded-lg p-1 shadow-[0_0_20px_rgba(8,145,178,0.1)]">
                    <div class="border-2 border-dashed border-cyan-900/50 rounded-lg p-8 text-center hover:bg-cyan-900/10 transition-colors relative" id="dropZone">
                        
                        <input type="file" id="imageInput" accept="image/jpeg,image/jpg,image/tiff" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleFileSelect(this)">
                        
                        <div id="uploadPrompt">
                            <i class="fa-solid fa-camera text-4xl text-cyan-700 mb-3"></i>
                            <h3 class="text-cyan-100 font-bold text-sm">DROP IMAGE SOURCE</h3>
                            <p class="text-[10px] text-cyan-600 mt-2">JPG/TIFF Formats Only.<br>Max Size: 10MB.</p>
                        </div>

                        <div id="filePreview" class="hidden">
                            <img id="previewImg" src="" class="max-h-40 mx-auto rounded border border-cyan-500/50 shadow-lg mb-3 object-contain">
                            <p id="fileName" class="text-xs text-cyan-300 font-mono truncate"></p>
                            <button type="button" onclick="resetUpload()" class="mt-2 text-[10px] text-red-400 hover:text-red-300 underline z-20 relative">Remove File</button>
                        </div>
                    </div>
                </div>

                <button id="btnAnalyze" onclick="analyzeImage()" class="cursor-pointer w-full bg-cyan-900/20 border border-cyan-500 text-cyan-400 font-bold py-3 rounded hover:bg-cyan-500 hover:text-black transition-all disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider text-sm">
                    <i class="fa-solid fa-microchip mr-2"></i> Initiate Scan
                </button>

                <div class="bg-black/30 border-l-2 border-amber-500 p-3 rounded-r text-[10px] text-gray-400 font-mono">
                    <strong class="text-amber-500 block mb-1">⚠ INTELLIGENCE NOTE:</strong>
                    Images from WhatsApp, Facebook, or Instagram usually have metadata STRIPPED (Cleaned). Use "Document" mode or original camera files for best results.
                </div>
            </div>

            <div class="lg:col-span-8 bg-black border border-cyan-900/50 rounded-lg p-4 font-mono text-sm relative overflow-hidden min-h-[400px]">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-0 pointer-events-none bg-[length:100%_2px,3px_100%]"></div>
                
                <div class="relative z-10 h-full flex flex-col">
                    <div class="flex justify-between border-b border-cyan-900 pb-2 mb-4">
                        <span class="text-cyan-500">./ANALYSIS_OUTPUT</span>
                        <span id="statusIndicator" class="text-gray-600">IDLE</span>
                    </div>

                    <div id="emptyState" class="flex-grow flex flex-col items-center justify-center text-cyan-900/50">
                        <i class="fa-solid fa-binary text-6xl mb-4 animate-pulse"></i>
                        <p>AWAITING DATA STREAM...</p>
                    </div>

                    <div id="loadingState" class="hidden flex-grow flex flex-col items-center justify-center text-cyan-400">
                        <i class="fa-solid fa-gear fa-spin text-4xl mb-4"></i>
                        <p class="animate-pulse">EXTRACTING METADATA...</p>
                        <p class="text-xs text-cyan-700 mt-2">Deciphering GPS Coordinates...</p>
                    </div>

                    <div id="resultContent" class="hidden space-y-6 overflow-y-auto pr-2 max-h-[500px]">
                        
                        <div id="gpsSection" class="hidden">
                            <h4 class="text-xs font-bold text-black bg-cyan-500 inline-block px-2 py-0.5 mb-2">Target Location Detected</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-cyan-900/10 p-4 border border-cyan-500/30 rounded">
                                <div>
                                    <div class="mb-2">
                                        <span class="text-gray-500 text-xs block">LATITUDE</span>
                                        <span id="resLat" class="text-white font-bold tracking-wider"></span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-gray-500 text-xs block">LONGITUDE</span>
                                        <span id="resLon" class="text-white font-bold tracking-wider"></span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 text-xs block">ALTITUDE</span>
                                        <span id="resAlt" class="text-cyan-300"></span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-center">
                                    <a id="mapLink" href="#" target="_blank" class="group flex flex-col items-center justify-center w-full h-full border border-dashed border-cyan-600 hover:bg-cyan-900/30 transition-colors rounded p-4">
                                        <i class="fa-solid fa-map-location-dot text-3xl text-cyan-500 mb-2 group-hover:scale-110 transition-transform"></i>
                                        <span class="text-xs text-cyan-400 underline">OPEN SATELLITE VIEW</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-cyan-500 border-b border-cyan-900 inline-block mb-2">Device Signature</h4>
                            <div class="grid grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-gray-500 block">MANUFACTURER</span>
                                    <span id="resMake" class="text-cyan-100"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">MODEL</span>
                                    <span id="resModel" class="text-cyan-100 font-bold"></span>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-gray-500 block">SOFTWARE / OS</span>
                                    <span id="resSoftware" class="text-cyan-100"></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-cyan-500 border-b border-cyan-900 inline-block mb-2">File Attributes</h4>
                            <div class="grid grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-gray-500 block">ORIGINAL TIMESTAMP</span>
                                    <span id="resDate" class="text-amber-400 font-bold"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500 block">RESOLUTION</span>
                                    <span id="resRes" class="text-cyan-100"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const uploadPrompt = document.getElementById('uploadPrompt');
        const filePreview = document.getElementById('filePreview');
        const previewImg = document.getElementById('previewImg');
        const fileName = document.getElementById('fileName');
        const btnAnalyze = document.getElementById('btnAnalyze');
        const imageInput = document.getElementById('imageInput');

        function handleFileSelect(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate Type
                if(!['image/jpeg', 'image/jpg', 'image/tiff'].includes(file.type)) {
                    alert('INVALID FORMAT. Only JPG/TIFF allowed for EXIF extraction.');
                    resetUpload();
                    return;
                }

                // Validate Size (10MB)
                if(file.size > 10485760) {
                    alert('FILE TOO LARGE. Maximum 10MB.');
                    resetUpload();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = file.name;
                    
                    uploadPrompt.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                    btnAnalyze.disabled = false;
                };
                reader.readAsDataURL(file);
            }
        }

        function resetUpload() {
            imageInput.value = '';
            uploadPrompt.classList.remove('hidden');
            filePreview.classList.add('hidden');
            btnAnalyze.disabled = true;
            previewImg.src = '';
            
            // Reset Results
            document.getElementById('resultContent').classList.add('hidden');
            document.getElementById('emptyState').classList.remove('hidden');
            document.getElementById('statusIndicator').textContent = 'IDLE';
        }

        async function analyzeImage() {
            const file = imageInput.files[0];
            if (!file) return;

            // UI Update
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('resultContent').classList.add('hidden');
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('statusIndicator').textContent = 'PROCESSING...';
            document.getElementById('statusIndicator').className = 'text-cyan-400 animate-pulse';
            btnAnalyze.disabled = true;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch("{{ route('tools.exif.analyze') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: formData
                });

                const data = await response.json();

                document.getElementById('loadingState').classList.add('hidden');
                
                if (!response.ok) {
                    throw new Error(data.message || 'Extraction failed');
                }

                // POPULATE DATA
                document.getElementById('resultContent').classList.remove('hidden');
                document.getElementById('statusIndicator').textContent = 'SUCCESS';
                document.getElementById('statusIndicator').className = 'text-green-500';

                // Device
                document.getElementById('resMake').textContent = data.device.make;
                document.getElementById('resModel').textContent = data.device.model;
                document.getElementById('resSoftware').textContent = data.device.software || 'N/A';

                // File
                document.getElementById('resDate').textContent = data.file.datetime;
                document.getElementById('resRes').textContent = data.file.resolution;

                // GPS Logic
                const gpsSection = document.getElementById('gpsSection');
                if (data.gps) {
                    gpsSection.classList.remove('hidden');
                    document.getElementById('resLat').textContent = data.gps.latitude.toFixed(6);
                    document.getElementById('resLon').textContent = data.gps.longitude.toFixed(6);
                    document.getElementById('resAlt').textContent = data.gps.altitude;
                    document.getElementById('mapLink').href = data.gps.map_url;
                } else {
                    gpsSection.classList.add('hidden');
                    // Optional: Show message that GPS was not found
                    const noGpsMsg = document.createElement('div');
                    noGpsMsg.className = 'text-xs text-amber-500 mb-4 font-mono border border-amber-900/30 bg-amber-900/10 p-2 rounded';
                    noGpsMsg.innerHTML = '<i class="fa-solid fa-triangle-exclamation mr-1"></i> NO GPS DATA IN FILE';
                    document.getElementById('resultContent').prepend(noGpsMsg);
                }

            } catch (error) {
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('statusIndicator').textContent = 'ERROR';
                document.getElementById('statusIndicator').className = 'text-red-500';
                
                alert('SYSTEM ERROR: ' + error.message);
            } finally {
                btnAnalyze.disabled = false;
            }
        }
    </script>
    @endpush
</x-app-layout>