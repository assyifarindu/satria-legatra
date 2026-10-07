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

                console.log(
                    'Contract Customer All Site:',
                    response
                );

                if (!response.success) {
                    return;
                }

                const data = response.data ?? [];


                /*
                |--------------------------------------------------------------------------
                | Categories
                |--------------------------------------------------------------------------
                */

                const categories = data.map(function(item) {
                    return item.site;
                });


                /*
                |--------------------------------------------------------------------------
                | Values
                |--------------------------------------------------------------------------
                */

                const values = data.map(function(item) {
                    return Number(item.total_contract);
                });


                /*
                |--------------------------------------------------------------------------
                | Chart
                |--------------------------------------------------------------------------
                */

                const options = {

                    series: [{
                        name: 'Total Contract',
                        data: values
                    }],

                    chart: {
                        type: 'bar',
                        height: 180,

                        toolbar: {
                            show: false
                        }
                    },

                    plotOptions: {

                        bar: {

                            horizontal: false,

                            columnWidth: '45%',

                            borderRadius: 0,

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

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {

                        categories: categories,

                        labels: {
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

                        max: 120,

                        tickAmount: 4,

                        labels: {

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


                const chartElement =
                    document.querySelector(
                        '#contract-customer-all-site-chart'
                    );


                if (!chartElement) {

                    console.error(
                        '#contract-customer-all-site-chart tidak ditemukan'
                    );

                    return;
                }


                if (typeof ApexCharts === 'undefined') {

                    console.error(
                        'ApexCharts belum diload'
                    );

                    return;
                }


                const chart = new ApexCharts(
                    chartElement,
                    options
                );

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
