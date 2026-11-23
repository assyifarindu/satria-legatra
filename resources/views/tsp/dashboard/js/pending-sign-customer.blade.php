<script>
    $(document).ready(function() {

        loadPendingSignCustomer();

    });


    function loadPendingSignCustomer() {

        $.ajax({

            url: "{{ route('tsp.dashboard.pending-sign-customer') }}",

            type: "GET",

            dataType: "json",

            success: function(response) {

                if (!response.success) {
                    return;
                }

                const data = response.data;

                const customers =
                    data.top_5_customers ?? [];


                /*Customer Names*/

                const labels = customers.map(function(customer) {
                    return customer.customer_name;
                });


                /*Customer Values*/

                const series = customers.map(function(customer) {
                    return Number(customer.pending_sign_count);
                });


                /*Colors*/

                const colors = [
                    '#2F80ED',
                    '#36B37E',
                    '#F5B335',
                    '#8B5CF6',
                    '#94A3B8'
                ];


                /*Top 5 Customer Legend*/

                let legendHtml = '';

                customers.forEach(function(customer, index) {

                    legendHtml += `
                        <div class="pending-customer-item">

                            <span
                                class="pending-customer-dot"
                                style="background-color: ${colors[index]};"
                            ></span>

                            <span
                                class="pending-customer-name"
                                title="${customer.customer_name}"
                            >
                                ${customer.customer_name}
                            </span>

                        </div>
                    `;

                });

                $('#pending-sign-customer-legend')
                    .html(legendHtml);


                /*Donut Chart*/

                const chartElement = document.querySelector('#pending-sign-customer-chart');

                if (!chartElement) {
                    console.error('#pending-sign-customer-chart tidak ditemukan');
                    return;
                }

                if (typeof ApexCharts === 'undefined') {
                    console.error('ApexCharts belum diload');
                    return;
                }

                // Jika tidak ada customer, gunakan data dummy bernilai 0
                const chartSeries = series.length > 0 ? series : [0];
                const chartLabels = labels.length > 0 ? labels : ['No Data'];

                const options = {

                    series: chartSeries,
                    labels: chartLabels,

                    chart: {
                        type: 'donut',
                        height: 190
                    },

                    colors: series.length > 0 ?
                        colors.slice(0, customers.length) : ['#E9ECEF'],

                    stroke: {
                        width: 1,
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

                                        showAlways: true,

                                        label: 'Total',

                                        fontSize: '16px',

                                        fontWeight: 600,

                                        color: '#536174',

                                        formatter: function() {

                                            return Number(data.total ?? 0);

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
                    chartElement,
                    options
                );

                chart.render();

            },

            error: function(xhr) {

                console.error(
                    'Failed to load Pending Sign Customer:',
                    xhr.status,
                    xhr.responseText
                );

            }

        });

    }
</script>
