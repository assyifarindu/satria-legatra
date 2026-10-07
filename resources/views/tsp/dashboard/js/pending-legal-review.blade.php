<script>
    $(document).ready(function() {

        loadPendingLegalReview();

    });


    function loadPendingLegalReview() {

        $.ajax({

            url: "{{ route('tsp.dashboard.pending-legal-review') }}",

            type: "GET",

            dataType: "json",

            success: function(response) {

                console.log(
                    'Pending Legal Review:',
                    response
                );

                if (!response.success) {
                    return;
                }

                const data = response.data;

                /*
                |--------------------------------------------------------------------------
                | Pending Legal
                |--------------------------------------------------------------------------
                */

                $('#pending-legal-value')
                    .text(data.pending_legal ?? 0);


                /*
                |--------------------------------------------------------------------------
                | Achievement SLA Legal
                |--------------------------------------------------------------------------
                */

                $('#ach-sla-legal-value')
                    .text(`${data.ach_sla_legal ?? 0}%`);


                /*
                |--------------------------------------------------------------------------
                | Aging Due Date Agreement
                |--------------------------------------------------------------------------
                */

                const aging =
                    data.aging_due_date_agreement ?? {};


                $('#aging-less-30')
                    .text(
                        aging.less_than_30_days ?? 0
                    );


                $('#aging-30-60')
                    .text(
                        aging.between_30_and_60_days ?? 0
                    );


                $('#aging-60-90')
                    .text(
                        aging.between_60_and_90_days ?? 0
                    );

            },

            error: function(xhr) {

                console.error(
                    'Failed to load Pending Legal Review:',
                    xhr.status,
                    xhr.responseText
                );

            }

        });

    }
</script>
