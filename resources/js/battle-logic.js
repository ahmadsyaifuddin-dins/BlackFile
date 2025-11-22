// Import Audio Manager & Chart
import AudioManager from './AudioManager';

let alphaChart = null;
let omegaChart = null;
const sfx = new AudioManager();

export default function battleSystem(entitiesData, routeUrl, csrfToken) {
    return {
        attackerId: '',
        defenderId: '',
        arena: 'NEUTRAL',
        attacker: { name: '', tier: '', image: '', type: '', stats: null },
        defender: { name: '', tier: '', image: '', type: '', stats: null },
        isSimulating: false,
        displayedLogs: [],
        winner: null,
        winnerIsAttacker: false,
        winProbability: 0,
        winReason: '',
        isMuted: false,

        entitiesData: entitiesData,

        // --- BAGIAN CHART & AUDIO SAMA SEPERTI SEBELUMNYA (TIDAK DIUBAH) ---
        toggleAudio() { this.isMuted = sfx.toggleMute(); },
        
        updatePreview(side) {
            sfx.play('hover');
            const id = side === 'attacker' ? this.attackerId : this.defenderId;
            const entity = this.entitiesData.find(e => e.id == id);
            const target = side === 'attacker' ? this.attacker : this.defender;

            if (entity) {
                target.name = entity.name;
                target.tier = entity.power_tier;
                target.type = entity.combat_type;
                target.stats = entity.combat_stats || { strength: 0, speed: 0, durability: 0, intelligence: 0, energy: 0, combat_skill: 0 };

                // Ambil objek gambar (Thumbnail atau gambar pertama)
                let imgObj = entity.thumbnail || (entity.images && entity.images.length > 0 ? entity.images[0] : null);

                // Langsung pakai .url (Magic dari Model Laravel)
                target.image = imgObj ? imgObj.url : null;

                this.renderChart(side, target.stats);
            } else {
                target.name = ''; target.tier = ''; target.image = ''; target.type = ''; target.stats = null;
                this.destroyChart(side);
            }
            this.winner = null;
            this.displayedLogs = [];
        },

        destroyChart(side) {
             if (side === 'attacker' && alphaChart) { alphaChart.destroy(); alphaChart = null; }
             if (side === 'defender' && omegaChart) { omegaChart.destroy(); omegaChart = null; }
        },
        
        renderChart(side, stats) {
            // ... (KODE CHART SAMA SEPERTI ASLINYA, TIDAK PERLU DIUBAH) ...
             this.destroyChart(side);
             const ctx = document.getElementById(side === 'attacker' ? 'chartAlpha' : 'chartOmega');
             if(!ctx) return;
             const color = side === 'attacker' ? 'rgba(16, 185, 129, ' : 'rgba(239, 68, 68, ';
             const config = {
                type: 'radar',
                data: {
                    labels: ['STR', 'SPD', 'DUR', 'INT', 'NRG', 'CBT'], // Disingkat biar rapi
                    datasets: [{
                        label: 'Stats',
                        data: [stats.strength, stats.speed, stats.durability, stats.intelligence, stats.energy, stats.combat_skill],
                        backgroundColor: color + '0.2)',
                        borderColor: color + '1)',
                        borderWidth: 2,
                        pointBackgroundColor: color + '1)',
                        pointBorderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            pointLabels: { color: '#9ca3af', font: { size: 10, family: 'monospace' } },
                            ticks: { display: false, backdropColor: 'transparent' }, // backdrop transparan
                            suggestedMin: 0, suggestedMax: 100
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            };
            if (side === 'attacker') alphaChart = new Chart(ctx, config);
            else omegaChart = new Chart(ctx, config);
        },

        randomizeFighters() {
            // ... (KODE RANDOMIZE SAMA SEPERTI ASLINYA) ...
            if (this.entitiesData.length < 2) { alert("INSUFFICIENT DATA."); return; }
            
            const randomIndexA = Math.floor(Math.random() * this.entitiesData.length);
            this.attackerId = this.entitiesData[randomIndexA].id;
            let randomIndexB;
            do { randomIndexB = Math.floor(Math.random() * this.entitiesData.length); } while (randomIndexB === randomIndexA);
            this.defenderId = this.entitiesData[randomIndexB].id;
            this.updatePreview('attacker');
            this.updatePreview('defender');
        },

        // --- BAGIAN UTAMA YANG DIMODIFIKASI ---
        async startSimulation() {
            if (this.attackerId === this.defenderId) { alert("CANNOT SIMULATE SELF-CONFLICT."); return; }
            if (!this.attackerId || !this.defenderId) return; // Safety check

            this.isSimulating = true;
            this.winner = null;
            this.displayedLogs = []; 
            
            sfx.play('process'); 

            // 1. LOADING EFFECT (Agar user tidak bengong nunggu Gemini)
            this.displayedLogs.push(">> ESTABLISHING SECURE UPLINK...");
            await new Promise(r => setTimeout(r, 300));
            this.displayedLogs.push(">> HANDSHAKE WITH BLACKFILE CORE: [OK]");
            this.scrollToBottom();

            try {
                // 2. FETCH DATA (Backend + Gemini AI)
                const response = await fetch(routeUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                    body: JSON.stringify({ 
                        attacker_id: this.attackerId, 
                        defender_id: this.defenderId,
                        arena: this.arena
                    })
                });
                const result = await response.json();
                
                // Hapus log loading manual tadi agar bersih, atau biarkan saja (opsional)
                // this.displayedLogs = []; 

                // 3. TAMPILKAN LOG HASIL (Efek Ngetik Per Baris)
                // Logika ini menampilkan baris per baris dengan delay agar user sempat membaca
                if (result.logs && result.logs.length > 0) {
                    for (const logText of result.logs) {
                        this.displayedLogs.push(logText);
                        sfx.play('typing'); 
                        this.scrollToBottom();
                        
                        // Delay dinamis: Teks panjang delay lebih lama, teks pendek lebih cepat
                        const delay = Math.max(300, Math.min(logText.length * 15, 800));
                        await new Promise(resolve => setTimeout(resolve, delay));
                    }
                }

                // 4. TAMPILKAN HASIL AKHIR
                this.winner = { name: result.winner_name };
                this.winnerIsAttacker = (result.winner_id == this.attackerId);
                this.winProbability = result.win_probability;
                this.winReason = result.reason;
                
                sfx.play('win'); 

                this.displayedLogs.push(">> SIMULATION COMPLETE.");
                this.scrollToBottom();

            } catch (e) {
                console.error(e); 
                this.displayedLogs.push("CRITICAL ERROR: NEURAL NETWORK UNRESPONSIVE.");
                this.displayedLogs.push(">> CHECK SERVER LOGS.");
                this.scrollToBottom();
            } finally { 
                this.isSimulating = false; 
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                // Pastikan ID elemen ini ada di Blade kamu
                const terminalContent = document.getElementById('terminal-content');
                if (terminalContent && terminalContent.parentElement) {
                    terminalContent.parentElement.scrollTop = terminalContent.parentElement.scrollHeight;
                }
            });
        }
    }
}