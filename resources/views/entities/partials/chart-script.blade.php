<script>
    function entityChart(stats) {
        return {
            init() {
                const ctx = document.getElementById('tacticalChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Strength', 'Speed', 'Durability', 'Intelligence', 'Energy', 'Combat Skill'],
                        datasets: [{
                            label: 'Stats',
                            data: [stats.strength || 0, stats.speed || 0, stats.durability || 0, stats
                                .intelligence || 0, stats.energy || 0, stats.combat_skill || 0
                            ],
                            backgroundColor: 'rgba(16, 185, 129, 0.2)', // Primary Green Opacity
                            borderColor: '#10b981', // Primary Green
                            borderWidth: 2,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                pointLabels: {
                                    color: '#9ca3af',
                                    font: {
                                        size: 9,
                                        family: 'monospace'
                                    }
                                },
                                ticks: {
                                    display: false,
                                    backdropColor: 'transparent'
                                },
                                suggestedMin: 0,
                                suggestedMax: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        }
    }
</script>
