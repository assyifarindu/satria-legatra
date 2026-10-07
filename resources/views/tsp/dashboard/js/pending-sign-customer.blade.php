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

                console.log(
                    'Pending Sign Customer:',
                    response
                );

                if (!response.success) {
                    return;
                }

                const data = response.data;

                const customers =
                    data.top_5_customers ?? [];


                /*
                |--------------------------------------------------------------------------
                | Customer Names
                |--------------------------------------------------------------------------
                */

                const labels = customers.map(function(customer) {
                    return customer.customer_name;
                });


                /*
                |--------------------------------------------------------------------------
                | Customer Values
                |--------------------------------------------------------------------------
                */

                const series = customers.map(function(customer) {
                    return Number(customer.pending_sign_count);
                });


                /*
                |--------------------------------------------------------------------------
                | Colors
                |--------------------------------------------------------------------------
                */

                const colors = [
                    '#2F80ED',
                    '#36B37E',
                    '#F5B335',
                    '#8B5CF6',
                    '#94A3B8'
                ];


                /*
                |--------------------------------------------------------------------------
                | Top 5 Customer Legend
                |--------------------------------------------------------------------------
                */

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


                /*
                |--------------------------------------------------------------------------
                | Donut Chart
                |--------------------------------------------------------------------------
                */

                const options = {

                    series: series,

                    labels: labels,

                    chart: {
                        type: 'donut',
                        height: 190
                    },

                    colors: colors.slice(
                        0,
                        customers.length
                    ),

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

                                    total: {

                                        show: true,

                                        showAlways: true,

                                        label: 'Total',

                                        fontSize: '11px',

                                        fontWeight: 500,

                                        color: '#536174',

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


                const chartElement =
                    document.querySelector(
                        '#pending-sign-customer-chart'
                    );


                if (!chartElement) {

                    console.error(
                        '#pending-sign-customer-chart tidak ditemukan'
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
                    'Failed to load Pending Sign Customer:',
                    xhr.status,
                    xhr.responseText
                );

            }

        });

    }
</script>
