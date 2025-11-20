<x-app-layout title="IDENTITY SEEKER // OSINT">
    <div class="max-w-7xl mx-auto py-3 sm:py-6 px-3 sm:px-6 lg:px-8 min-h-screen flex flex-col">

        <!-- HEADER -->
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-primary tracking-wider">
                    <span class="text-white">./</span>IDENTITY_SEEKER
                </h2>
                <p class="text-[10px] sm:text-xs text-secondary font-mono mt-1">
                    Targeting Username Across Multiple Platforms
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <x-button 
                    variant="outline" 
                    href="{{ route('tools.index') }}" 
                    class="text-secondary hover:text-white text-xs sm:text-sm transition-colors flex-1 sm:flex-none">
                    [ BACK TO ARSENAL ]
                </x-button>
            </div>
        </div>

        <!-- SEARCH BOX + HISTORY TOGGLE -->
        <div class="bg-surface border border-border-color p-4 sm:p-6 rounded-lg shadow-lg mb-4 sm:mb-6">
            <label class="block text-xs font-bold text-primary mb-2 uppercase tracking-widest">
                Target Username
            </label>
            <div class="flex flex-col gap-3">
                <!-- INPUT + BUTTON -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <x-forms.input 
                            type="text"
                            id="usernameInput" 
                            name="name" 
                            class="w-full bg-base border border-border-color text-white pl-8 pr-4 py-3 rounded font-mono focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder-gray-700 text-sm sm:text-base" 
                            placeholder="johndoe" 
                            autocomplete="off"
                            onkeypress="if(event.key === 'Enter') startScan()"/>
                    </div>
                    <button 
                        onclick="startScan()" 
                        id="btnScan" 
                        class="bg-primary hover:bg-green-600 text-black font-bold py-3 px-6 sm:px-8 rounded transition-colors uppercase tracking-wider flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap">
                        <i class="fa-solid fa-satellite-dish"></i> 
                        <span class="hidden sm:inline">Start Scan</span>
                        <span class="sm:hidden">Scan</span>
                    </button>
                </div>

                <!-- HISTORY & EXPORT BUTTONS -->
                <div class="flex gap-2">
                    <button 
                        onclick="toggleHistory()" 
                        class="flex-1 sm:flex-none bg-surface-light border border-border-color hover:border-primary text-secondary hover:text-primary py-2 px-4 rounded text-xs transition-colors">
                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> History
                    </button>
                    <button 
                        onclick="exportResults()" 
                        id="btnExport"
                        class="flex-1 sm:flex-none bg-surface-light border border-border-color hover:border-blue-500 text-secondary hover:text-blue-400 py-2 px-4 rounded text-xs transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                        disabled>
                        <i class="fa-solid fa-download mr-1"></i> Export
                    </button>
                    <button 
                        onclick="clearHistory()" 
                        class="sm:flex-none bg-surface-light border border-red-900/50 hover:border-red-500 text-red-800 hover:text-red-500 py-2 px-4 rounded text-xs transition-colors">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>

                <!-- HISTORY DROPDOWN (Hidden by default) -->
                <div id="historyContainer" class="hidden">
                    <div class="bg-black/50 border border-gray-800 rounded p-3 max-h-40 overflow-y-auto">
                        <div id="historyList" class="space-y-1 text-xs font-mono">
                            <!-- History items akan muncul di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-grow grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 min-h-0">

            <!-- TERMINAL LOG (Hidden on mobile by default) -->
            <div class="hidden lg:flex lg:col-span-1 bg-black border border-border-color rounded p-4 font-mono text-xs overflow-y-auto flex-col" id="terminal-window">
                <div class="text-secondary mb-2 border-b border-gray-800 pb-2">
                    <i class="fa-solid fa-terminal mr-2"></i>SYSTEM LOG
                </div>
                <div id="scanLog" class="space-y-1 font-mono">
                    <div class="text-green-900">System ready...</div>
                    <div class="text-green-900">Waiting for input...</div>
                </div>
            </div>

            <!-- RESULTS GRID -->
            <div class="lg:col-span-2 overflow-y-auto">
                <!-- PROGRESS BAR -->
                <div class="mb-4 hidden" id="progressContainer">
                    <div class="flex justify-between text-xs text-secondary mb-1">
                        <span class="text-[10px] sm:text-xs">SCANNING PROGRESS</span>
                        <span id="progressText" class="text-[10px] sm:text-xs">0%</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5 sm:h-2">
                        <div id="progressBar" class="bg-primary h-1.5 sm:h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- RESULTS CARDS -->
                <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="col-span-full text-center py-10 sm:py-20 text-secondary opacity-50">
                        <i class="fa-solid fa-magnifying-glass text-3xl sm:text-4xl mb-4"></i>
                        <p class="text-sm sm:text-base">Enter a username to begin OSINT scan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE LOG TOGGLE (Only on mobile) -->
        <button 
            onclick="toggleMobileLog()" 
            class="lg:hidden fixed bottom-4 right-4 bg-primary text-black p-3 rounded-full shadow-lg z-50 hover:bg-green-600 transition-colors">
            <i class="fa-solid fa-terminal text-lg"></i>
        </button>

        <!-- MOBILE LOG MODAL -->
        <div id="mobileLogModal" class="lg:hidden fixed inset-0 bg-black/90 z-50 hidden flex items-end">
            <div class="w-full bg-surface border-t-2 border-primary rounded-t-2xl p-4 max-h-[70vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-primary font-bold text-sm uppercase tracking-wider">
                        <i class="fa-solid fa-terminal mr-2"></i>System Log
                    </h3>
                    <button onclick="toggleMobileLog()" class="text-gray-500 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div id="mobileLogContent" class="font-mono text-xs space-y-1">
                    <!-- Log content will be synced here -->
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ===== CONFIGURATION =====
        const SITES = [
            { name: 'GitHub', url: 'https://github.com/{name}', icon: 'fa-github', color: 'text-white' },
            { name: 'Instagram', url: 'https://www.instagram.com/{name}/', icon: 'fa-instagram', color: 'text-pink-500' },
            { name: 'Facebook', url: 'https://www.facebook.com/{name}', icon: 'fa-facebook', color: 'text-blue-500' },
            { name: 'Twitter/X', url: 'https://twitter.com/{name}', icon: 'fa-x-twitter', color: 'text-gray-300' },
            { name: 'TikTok', url: 'https://www.tiktok.com/@{name}', icon: 'fa-tiktok', color: 'text-pink-400' },
            { name: 'YouTube', url: 'https://www.youtube.com/@{name}', icon: 'fa-youtube', color: 'text-red-500' },
            { name: 'Pinterest', url: 'https://www.pinterest.com/{name}/', icon: 'fa-pinterest', color: 'text-red-600' },
            { name: 'Reddit', url: 'https://www.reddit.com/user/{name}', icon: 'fa-reddit', color: 'text-orange-500' },
            { name: 'Medium', url: 'https://medium.com/@{name}', icon: 'fa-medium', color: 'text-white' },
            { name: 'Spotify', url: 'https://open.spotify.com/user/{name}', icon: 'fa-spotify', color: 'text-green-500' },
            { name: 'SoundCloud', url: 'https://soundcloud.com/{name}', icon: 'fa-soundcloud', color: 'text-orange-600' },
            { name: 'Twitch', url: 'https://www.twitch.tv/{name}', icon: 'fa-twitch', color: 'text-purple-500' },
            { name: 'Steam', url: 'https://steamcommunity.com/id/{name}', icon: 'fa-steam', color: 'text-blue-300' },
            { name: 'Vimeo', url: 'https://vimeo.com/{name}', icon: 'fa-vimeo', color: 'text-blue-400' },
            { name: 'GitLab', url: 'https://gitlab.com/{name}', icon: 'fa-gitlab', color: 'text-orange-400' }
        ];

        // Current scan results (untuk export)
        let currentResults = [];

        // ===== HELPER FUNCTIONS =====
        function sanitizeInput(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function getDomain(url) {
            try {
                return url.split('/')[2];
            } catch (e) {
                return '';
            }
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        }

        // ===== HISTORY MANAGEMENT =====
        function saveToHistory(username) {
            let history = JSON.parse(localStorage.getItem('osint_history') || '[]');
            
            // Remove duplicate
            history = history.filter(item => item.username !== username);
            
            // Add new entry at beginning
            history.unshift({
                username: username,
                timestamp: Date.now(),
                date: new Date().toLocaleString('id-ID', { 
                    day: '2-digit', 
                    month: 'short', 
                    hour: '2-digit', 
                    minute: '2-digit' 
                })
            });
            
            // Keep only last 10
            history = history.slice(0, 10);
            
            localStorage.setItem('osint_history', JSON.stringify(history));
            renderHistory();
        }

        function renderHistory() {
            const historyList = document.getElementById('historyList');
            const history = JSON.parse(localStorage.getItem('osint_history') || '[]');
            
            if (history.length === 0) {
                historyList.innerHTML = '<div class="text-gray-700 text-center py-2">No search history</div>';
                return;
            }
            
            historyList.innerHTML = history.map(item => `
                <div class="flex items-center justify-between bg-surface-light border border-gray-800 hover:border-primary rounded p-2 cursor-pointer transition-colors group"
                     onclick="loadFromHistory('${item.username.replace(/'/g, "\\'")}')">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <i class="fa-solid fa-clock text-gray-600 text-[10px]"></i>
                        <span class="text-gray-300 truncate">@${item.username}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] text-gray-600">${item.date}</span>
                        <i class="fa-solid fa-arrow-right text-primary opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                </div>
            `).join('');
        }

        function toggleHistory() {
            const container = document.getElementById('historyContainer');
            container.classList.toggle('hidden');
            if (!container.classList.contains('hidden')) {
                renderHistory();
            }
        }

        function loadFromHistory(username) {
            document.getElementById('usernameInput').value = username;
            document.getElementById('historyContainer').classList.add('hidden');
            startScan();
        }

        function clearHistory() {
            if (confirm('Clear all search history?')) {
                localStorage.removeItem('osint_history');
                renderHistory();
            }
        }

        // ===== MOBILE LOG TOGGLE =====
        function toggleMobileLog() {
            const modal = document.getElementById('mobileLogModal');
            const content = document.getElementById('mobileLogContent');
            const mainLog = document.getElementById('scanLog');
            
            modal.classList.toggle('hidden');
            
            if (!modal.classList.contains('hidden')) {
                // Sync log content
                content.innerHTML = mainLog.innerHTML;
            }
        }

        // Sync mobile log realtime
        function syncMobileLog() {
            const modal = document.getElementById('mobileLogModal');
            if (!modal.classList.contains('hidden')) {
                const content = document.getElementById('mobileLogContent');
                const mainLog = document.getElementById('scanLog');
                content.innerHTML = mainLog.innerHTML;
            }
        }

        // ===== EXPORT FUNCTIONALITY =====
        function exportResults() {
            if (currentResults.length === 0) {
                alert('No results to export!');
                return;
            }

            const username = document.getElementById('usernameInput').value.trim();
            const timestamp = new Date().toISOString().split('T')[0];
            
            // Export as JSON
            const dataStr = JSON.stringify(currentResults, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `osint_${username}_${timestamp}.json`;
            link.click();
            URL.revokeObjectURL(url);
        }

        // ===== MAIN SCAN FUNCTION =====
        async function startScan() {
            const inputRaw = document.getElementById('usernameInput').value.trim();
            const grid = document.getElementById('resultsGrid');
            const log = document.getElementById('scanLog');
            const btn = document.getElementById('btnScan');
            const btnExport = document.getElementById('btnExport');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            if (!inputRaw) {
                alert('ERROR: Target name or username required.');
                return;
            }

            const sanitizedInput = sanitizeInput(inputRaw);

            // Save to history
            saveToHistory(sanitizedInput);

            // Reset
            currentResults = [];
            grid.innerHTML = '';
            log.innerHTML = '<div class="text-primary animate-pulse">⚡ Initializing OSINT scan...</div>';
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span class="hidden sm:inline">Scanning...</span><span class="sm:hidden">...</span>';
            btnExport.disabled = true;
            progressContainer.classList.remove('hidden');

            let foundCount = 0;
            let manualCount = 0;
            let notFoundCount = 0;

            const BATCH_SIZE = 3;
            const DELAY_BETWEEN_BATCHES = 800;

            for (let i = 0; i < SITES.length; i += BATCH_SIZE) {
                const batch = SITES.slice(i, i + BATCH_SIZE);
                
                const batchPromises = batch.map(site => checkSite(site, sanitizedInput, log, grid));
                const batchResults = await Promise.all(batchPromises);

                batchResults.forEach(result => {
                    if (result.status === 'FOUND') foundCount++;
                    else if (result.status === 'MANUAL_CHECK') manualCount++;
                    else if (result.status === 'NOT_FOUND') notFoundCount++;
                    
                    currentResults.push(result);
                });

                const progress = Math.min(100, Math.round(((i + batch.length) / SITES.length) * 100));
                progressBar.style.width = `${progress}%`;
                progressText.innerText = `${progress}%`;

                if (i + BATCH_SIZE < SITES.length) {
                    await sleep(DELAY_BETWEEN_BATCHES);
                }
            }

            // Selesai
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fa-solid fa-satellite-dish"></i> <span class="hidden sm:inline">Start Scan</span><span class="sm:hidden">Scan</span>';
            btnExport.disabled = false;

            const finalLog = document.createElement('div');
            finalLog.className = 'text-primary mt-2 border-t border-gray-800 pt-2 font-bold';
            finalLog.innerHTML = `
                ✓ SCAN COMPLETE<br>
                <span class="text-green-400">→ ${foundCount} confirmed</span><br>
                <span class="text-amber-500">→ ${manualCount} manual</span><br>
                <span class="text-gray-600">→ ${notFoundCount} not found</span>
            `;
            log.prepend(finalLog);
            syncMobileLog();

            if (foundCount === 0 && manualCount === 0) {
                const globalSearch = `https://www.google.com/search?q=${encodeURIComponent(sanitizedInput)}`;
                grid.innerHTML = `
                    <div class="col-span-full text-center py-10 text-gray-500">
                        <i class="fa-solid fa-ghost text-3xl sm:text-4xl mb-4 opacity-50"></i>
                        <p class="text-sm sm:text-lg mb-2">No accounts found for "<span class="text-white">${sanitizedInput}</span>"</p>
                        <p class="text-xs text-gray-600 mb-4">Try different username or use global search</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="${globalSearch}" target="_blank" rel="noopener noreferrer" class="inline-block bg-surface border border-border-color hover:border-primary px-4 py-2 rounded text-sm transition-colors text-secondary hover:text-primary">
                                <i class="fa-brands fa-google mr-2"></i> Google Search
                            </a>
                            <a href="https://www.bing.com/search?q=${encodeURIComponent(sanitizedInput)}" target="_blank" rel="noopener noreferrer" class="inline-block bg-surface border border-border-color hover:border-blue-500 px-4 py-2 rounded text-sm transition-colors text-secondary hover:text-blue-400">
                                <i class="fa-brands fa-microsoft mr-2"></i> Bing Search
                            </a>
                        </div>
                    </div>
                `;
            }
        }

        // ===== CHECK SITE FUNCTION =====
        async function checkSite(site, username, logElement, gridElement) {
            const usernameForUrl = username.replace(/\s+/g, '').replace('@', '');
            const targetUrl = site.url.replace('{name}', usernameForUrl);
            
            const queryForDork = username.replace('@', '');
            const domain = getDomain(site.url);
            const googleDorkUrl = `https://www.google.com/search?q=${encodeURIComponent(`site:${domain} ${queryForDork}`)}`;

            const logItem = document.createElement('div');
            logItem.className = 'text-gray-500';
            logItem.innerHTML = `<span class="text-gray-700">⟳</span> Checking ${site.name}...`;
            logElement.prepend(logItem);
            syncMobileLog();

            try {
                const response = await fetch("{{ route('tools.check') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        url: targetUrl,
                        username: username 
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                const renderCard = (statusText, statusClass, borderColor, showInfo = false) => {
                    const infoTooltip = showInfo ? `
                        <div class="text-[9px] text-gray-500 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>${data.reason || 'Verification needed'}</span>
                        </div>
                    ` : '';

                    const card = `
                        <div class="block bg-surface-light border ${borderColor} p-3 sm:p-4 rounded transition-all group hover:shadow-[0_0_10px_rgba(0,0,0,0.5)] animate-pulse-once">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <i class="fa-brands ${site.icon} text-lg sm:text-xl ${site.color}"></i>
                                    <span class="font-bold text-gray-200 text-xs sm:text-sm">${site.name}</span>
                                </div>
                                <span class="text-[8px] sm:text-[9px] font-bold ${statusClass} px-1.5 sm:px-2 py-1 rounded border uppercase tracking-wider">
                                    ${statusText}
                                </span>
                            </div>
                            ${infoTooltip}
                            <div class="flex gap-2 mt-2 sm:mt-3">
                                <a href="${targetUrl}" target="_blank" rel="noopener noreferrer" class="flex-1 text-center bg-black/50 border border-gray-700 hover:border-gray-400 text-[10px] sm:text-xs py-1.5 sm:py-2 rounded text-gray-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-link mr-1"></i> <span class="hidden sm:inline">Visit</span><span class="sm:hidden">Go</span>
                                </a>
                                <a href="${googleDorkUrl}" target="_blank" rel="noopener noreferrer" class="flex-1 text-center bg-blue-900/20 border border-blue-800/50 hover:border-blue-500 text-[10px] sm:text-xs py-1.5 sm:py-2 rounded text-blue-300 hover:text-blue-100 transition-colors">
                                    <i class="fa-brands fa-google mr-1"></i> Dork
                                </a>
                            </div>
                        </div>
                    `;
                    gridElement.insertAdjacentHTML('beforeend', card);
                };

                if (data.status === 'FOUND') {
                    logItem.innerHTML = `<span class="text-primary">✓</span> <span class="text-green-400 font-bold">${site.name}</span> - Account found`;
                    logItem.className = 'text-green-400';
                    renderCard('FOUND', 'text-primary bg-primary/10 border-primary/20', 'border-primary/30 hover:border-primary');
                    syncMobileLog();
                    return { platform: site.name, status: 'FOUND', url: targetUrl };

                } else if (data.status === 'MANUAL_CHECK') {
                    const reason = data.reason || 'Needs verification';
                    logItem.innerHTML = `<span class="text-amber-500">⚠</span> ${site.name} - ${reason}`;
                    logItem.className = 'text-amber-500';
                    renderCard('MANUAL', 'text-amber-500 bg-amber-500/10 border-amber-500/20', 'border-amber-500/30 hover:border-amber-500', true);
                    syncMobileLog();
                    return { platform: site.name, status: 'MANUAL_CHECK', url: targetUrl, reason };

                } else if (data.status === 'ERROR') {
                    logItem.innerHTML = `<span class="text-red-500">✗</span> ${site.name} - Error`;
                    logItem.className = 'text-red-800';
                    syncMobileLog();
                    return { platform: site.name, status: 'ERROR', url: targetUrl };

                } else {
                    logItem.innerHTML = `<span class="text-gray-700">○</span> ${site.name} - Not found`;
                    logItem.className = 'text-gray-600';
                    syncMobileLog();
                    return { platform: site.name, status: 'NOT_FOUND', url: targetUrl };
                }

            } catch (error) {
                console.error(`Error checking ${site.name}:`, error);
                logItem.innerHTML = `<span class="text-red-500">✗</span> ${site.name} - Request failed`;
                logItem.className = 'text-red-500';
                syncMobileLog();
                return { platform: site.name, status: 'ERROR', url: targetUrl, error: error.message };
            }
        }

        // Initialize history on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderHistory();
        });
    </script>

    <style>
        @keyframes pulse-once {
            0% {
                transform: scale(0.95);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-pulse-once {
            animation: pulse-once 0.3s ease-out forwards;
        }

        #terminal-window::-webkit-scrollbar,
        #historyContainer::-webkit-scrollbar {
            width: 4px;
        }

        #terminal-window::-webkit-scrollbar-track,
        #historyContainer::-webkit-scrollbar-track {
            background: #000;
        }

        #terminal-window::-webkit-scrollbar-thumb,
        #historyContainer::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 2px;
        }

        #terminal-window::-webkit-scrollbar-thumb:hover,
        #historyContainer::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }

        /* Mobile smooth scroll */
        @media (max-width: 1023px) {
            #resultsGrid {
                scroll-behavior: smooth;
            }
        }

        /* Touch feedback */
        @media (hover: none) {
            button:active,
            a:active {
                transform: scale(0.95);
            }
        }
    </style>
    @endpush
</x-app-layout>