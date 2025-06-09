<div 
    x-data="{
        init() {
            if (typeof window.Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }
            
            const ctx = this.$refs.canvas.getContext('2d');
            new window.Chart(ctx, {
                type: 'line',
                data: {
                    labels: {{ Js::from($labels) }},
                    datasets: {{ Js::from($datasets) }}
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: Rp${context.raw.toLocaleString('id-ID')}`;
                                }
                            }
                        }
                    
                    },

                    layout: {
                        padding: 20
                    }, 

                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { 
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }
    }"
    class="relative w-full h-full"
    wire:ignore
>
    <canvas x-ref="canvas"></canvas>
</div>