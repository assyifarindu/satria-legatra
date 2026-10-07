<script>
    $(document).ready(function() {

        loadContractTypeComposition();

    });

    function loadContractTypeComposition() {

        $.ajax({
            url: "{{ route('tsp.dashboard.contract-type-composition') }}",
            type: "GET",
            dataType: "json",

            success: function(response) {

                if (!response.success) {
                    return;
                }

                const data = response.data;

                // Update legend
                $('#contract-unit').text(data.unit);
                $('#contract-service').text(data.service);
                $('#contract-parts').text(data.parts);
                $('#contract-reman').text(data.reman);

                // Donut Chart
                const options = {
                    series: [
                        data.unit,
                        data.service,
                        data.parts,
                        data.reman
                    ],

                    labels: [
                        'Unit',
                        'Service',
                        'Parts',
                        'Reman'
                    ],

                    chart: {
                        type: 'donut',
                        height: 200
                    },

                    colors: [
                        '#36B37E',
                        '#8B5CF6',
                        '#FBB03B',
                        '#94A3B8'
                    ],

                    stroke: {
                        width: 2,
                        colors: ['#ffffff']
                    },

                    dataLabels: {
                        enabled: false
                    },

                    legend: {
                        show: false
                    },

                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',

                                labels: {
                                    show: true,

                                    name: {
                                        show: true
                                    },

                                    value: {
                                        show: true
                                    },

                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '16px',
                                        fontWeight: 600,
                                        color: '#333',

                                        formatter: function() {
                                            return data.total;
                                        }
                                    }
                                }
                            }
                        }
                    },

                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return value;
                            }
                        }
                    }
                };

                const chart = new ApexCharts(
                    document.querySelector('#contract-type-composition-chart'),
                    options
                );

                chart.render();
            },

            error: function(xhr) {

                console.error(
                    'Failed to load Contract Type Composition:',
                    xhr.responseText
                );

            }
        });

    }
</script>
