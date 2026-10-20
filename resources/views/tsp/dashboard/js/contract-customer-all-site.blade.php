<script>
    $(document).ready(function() {
        loadContractCustomerAllSite();
    });

    function loadContractCustomerAllSite() {
        $.ajax({
            url: "{{ route('tsp.dashboard.contract-customer-all-site') }}",
            type: "GET",
            dataType: "json",

            success: function(response) {
                console.log('Contract Customer All Site:', response);

                if (!response.success) {
                    return;
                }

                const data = response.data ?? [];

                /*Categories - Persingkat nama site*/

                const categories = data.map(function(item) {
                    return item.site
                        .replace(/^\d+\s*-\s*/, '')
                        .replace(/\s+/g, ' ')
                        .split(' ');
                });

                /*Values*/

                const values = data.map(function(item) {
                    return Number(item.total_contract);
                });

                // Max Y-axis menyesuaikan nilai tertinggi
                const maxValue = Math.max(...values, 0);
                const yAxisMax = Math.max(maxValue + 1, 3);

                /*Chart*/

                const options = {
                    series: [{
                        name: 'Total Contract',
                        data: values
                    }],

                    chart: {
                        type: 'bar',
                        height: 220,
                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            borderRadius: 2,
                            distributed: true
                        }
                    },

                    colors: [
                        '#2F80ED',
                        '#36B37E',
                        '#F5B335',
                        '#8B5CF6',
                        '#4DB6C5',
                        '#EF6461',
                        '#8E9AAF',
                        '#A78BFA'
                    ],

                    // Tampilkan angka di atas setiap batang
                    dataLabels: {
                        enabled: true,
                        offsetY: -15,
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: ['#374151']
                        }
                    },

                    xaxis: {
                        categories: categories,

                        labels: {
                            rotate: 0,
                            rotateAlways: false,
                            trim: false,
                            hideOverlappingLabels: false,
                            style: {
                                fontSize: '10px'
                            }
                        },

                        axisBorder: {
                            show: true
                        },

                        axisTicks: {
                            show: false
                        }
                    },

                    yaxis: {
                        min: 0,
                        max: yAxisMax,
                        tickAmount: yAxisMax <= 4 ? yAxisMax : 4,

                        labels: {
                            formatter: function(value) {
                                return Math.round(value);
                            },
                            style: {
                                fontSize: '9px'
                            }
                        }
                    },

                    grid: {
                        borderColor: '#edf0f4',
                        strokeDashArray: 0,

                        xaxis: {
                            lines: {
                                show: false
                            }
                        },

                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },

                    legend: {
                        show: false
                    },

                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return value + ' Contracts';
                            }
                        }
                    }
                };

                const chartElement = document.querySelector(
                    '#contract-customer-all-site-chart'
                );

                if (!chartElement) {
                    console.error(
                        '#contract-customer-all-site-chart tidak ditemukan'
                    );
                    return;
                }

                if (typeof ApexCharts === 'undefined') {
                    console.error('ApexCharts belum diload');
                    return;
                }

                // Hindari chart menumpuk jika fungsi dipanggil ulang
                if (chartElement._chartInstance) {
                    chartElement._chartInstance.destroy();
                }

                const chart = new ApexCharts(chartElement, options);
                chartElement._chartInstance = chart;
                chart.render();
            },

            error: function(xhr) {
                console.error(
                    'Failed to load Contract Customer All Site:',
                    xhr.status,
                    xhr.responseText
                );
            }
        });
    }
</script>
