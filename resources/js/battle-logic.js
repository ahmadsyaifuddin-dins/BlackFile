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
        isMuted: false, // State Mute

        entitiesData: entitiesData,

        // Toggle Mute Button
        toggleAudio() {
            this.isMuted = sfx.toggleMute();
        },

        updatePreview(side) {
            sfx.play('hover'); // <--- EFEK SUARA BLIP SAAT PILIH HERO

            const id = side === 'attacker' ? this.attackerId : this.defenderId;
            const entity = this.entitiesData.find(e => e.id == id);
            const target = side === 'attacker' ? this.attacker : this.defender;

            if (entity) {
                target.name = entity.name;
                target.tier = entity.power_tier;
                target.type = entity.combat_type;
                target.stats = entity.combat_stats || {strength:0, speed:0, durability:0, intelligence:0, energy:0, combat_skill:0};

                let imgObj = entity.thumbnail || (entity.images && entity.images.length > 0 ? entity.images[0] : null);
                if (imgObj) {
                    target.image = imgObj.path.startsWith('http') ? imgObj.path : '/uploads/' + imgObj.path;
                } else {
                    target.image = null;
                }
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
             this.destroyChart(side);
             const ctx = document.getElementById(side === 'attacker' ? 'chartAlpha' : 'chartOmega');
             if(!ctx) return;
             const color = side === 'attacker' ? 'rgba(16, 185, 129, ' : 'rgba(239, 68, 68, ';
             const config = {
                type: 'radar',
                data: {
                    labels: ['Strength', 'Speed', 'Durability', 'Intelligence', 'Energy', 'Combat Skill'],
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
                            ticks: { display: false },
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
            // Cek jumlah data
            if (this.entitiesData.length < 2) {
                alert("INSUFFICIENT DATA FOR RANDOMIZATION PROTOCOL.");
                return;
            }
            
            // Efek Suara (Opsional, pakai suara 'typing' atau 'hover' biar terasa techy)
            // sfx.play('typing'); 

            // 1. Pilih Index Acak untuk Attacker
            const randomIndexA = Math.floor(Math.random() * this.entitiesData.length);
            this.attackerId = this.entitiesData[randomIndexA].id;

            // 2. Pilih Index Acak untuk Defender (Loop sampai beda dengan A)
            let randomIndexB;
            do {
                randomIndexB = Math.floor(Math.random() * this.entitiesData.length);
            } while (randomIndexB === randomIndexA);

            this.defenderId = this.entitiesData[randomIndexB].id;

            // 3. Update Tampilan Kartu & Chart
            this.updatePreview('attacker');
            this.updatePreview('defender');
            
            // Reset status
            this.winner = null;
            this.displayedLogs = [];
        },

        async startSimulation() {
            if (this.attackerId === this.defenderId) { alert("CANNOT SIMULATE SELF-CONFLICT."); return; }
            
            this.isSimulating = true;
            this.winner = null;
            this.displayedLogs = []; 
            
            sfx.play('process'); // <--- SUARA START (Proses Hitung)

            try {
                const response = await fetch(routeUrl, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken },
                    // Tambahkan arena ke body request
                    body: JSON.stringify({ 
                        attacker_id: this.attackerId, 
                        defender_id: this.defenderId,
                        arena: this.arena
                    })
                });
                const result = await response.json();
                
                for (const logText of result.logs) {
                    this.displayedLogs.push(logText);
                    sfx.play('typing'); // <--- SUARA KETIK (Setiap baris log muncul)
                    
                    this.scrollToBottom();
                    await new Promise(resolve => setTimeout(resolve, Math.random() * 300 + 100));
                }

                this.winner = { name: result.winner_name };
                this.winnerIsAttacker = (result.winner_id == this.attackerId);
                this.winProbability = result.win_probability;
                this.winReason = result.reason;
                
                sfx.play('win'); // <--- SUARA FINISH (Dramatis)

                this.displayedLogs.push(">> SIMULATION COMPLETE.");
                this.displayedLogs.push(">> WINNER: [" + result.winner_name + "]");
                this.scrollToBottom();

            } catch (e) {
                console.error(e); 
                this.displayedLogs.push("CRITICAL ERROR: CONNECTION LOST.");
            } finally { 
                this.isSimulating = false; 
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const terminal = document.getElementById('terminal-content').parentElement;
                terminal.scrollTop = terminal.scrollHeight;
            });
        }
    }
}