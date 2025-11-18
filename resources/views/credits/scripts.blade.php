<script>
    function creditsForm({ initialCredits, initialMusicPath }) {
        return {
            credits: [],
            
            init() {
                this.credits = (initialCredits && initialCredits.length > 0 ? initialCredits : [{ id: null, role: '', names: [''], logos: [] }])
                    .map((c, i) => ({
                        ...c,
                        uniqueKey: c.id || Date.now() + i,
                        names: c.names && c.names.length > 0 ? c.names : [''],
                        namesJson: JSON.stringify(c.names && c.names.length > 0 ? c.names : [''], null, 4),
                        logos: (c.logos || []).map(l => ({ type: l.startsWith('http') ? 'url' : 'file', path: l }))
                    }));
            },
            
            // --- FUNGSI SINKRONISASI JSON ---
            switchToRaw(creditIndex) {
                this.credits[creditIndex].namesJson = JSON.stringify(this.credits[creditIndex].names, null, 4);
            },
            switchToVisual(creditIndex) {
                this.updateNamesFromJson(creditIndex);
            },
            updateNamesFromJson(creditIndex) {
                try {
                    const parsed = JSON.parse(this.credits[creditIndex].namesJson);
                    if (Array.isArray(parsed)) {
                        this.credits[creditIndex].names = parsed.map(String);
                    }
                } catch (e) {
                    console.error("Invalid JSON format:", e);
                }
            },
            formatJson(creditIndex) {
                try {
                    const parsed = JSON.parse(this.credits[creditIndex].namesJson);
                    this.credits[creditIndex].namesJson = JSON.stringify(parsed, null, 4);
                } catch (e) {}
            },
    
            // --- FUNGSI MANAJEMEN ---
            addCredit() {
                this.credits.push({ 
                    id: null, role: '', names: [''], logos: [], 
                    uniqueKey: Date.now(),
                    namesJson: JSON.stringify([''], null, 4)
                });
            },
            removeCredit(index) {
                if (confirm('Delete this entire section?')) {
                    this.credits.splice(index, 1);
                }
            },
            addName(creditIndex) {
                this.credits[creditIndex].names.push('');
            },
            removeName(creditIndex, nameIndex) {
                if (this.credits[creditIndex].names.length > 1) {
                    this.credits[creditIndex].names.splice(nameIndex, 1);
                }
            },
            addLogo(creditIndex) {
                this.credits[creditIndex].logos.push({ type: 'file', path: '' });
            },
            removeLogo(creditIndex, logoIndex) {
                this.credits[creditIndex].logos.splice(logoIndex, 1);
            }
        }
    }
    </script>