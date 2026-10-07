<script>
    $(document).ready(function() {

        loadPendingReview();

    });


    function loadPendingReview() {

        $.ajax({

            url: "{{ route('tsp.dashboard.pending-review') }}",

            type: "GET",

            dataType: "json",

            success: function(response) {

                console.log(
                    'Pending Review:',
                    response
                );

                if (!response.success) {
                    return;
                }

                const data = response.data;


                /*
                |--------------------------------------------------------------------------
                | Review Count
                |--------------------------------------------------------------------------
                */

                $('#user-review-value')
                    .text(data.user_review ?? 0);

                $('#committee-review-value')
                    .text(data.committee_review ?? 0);

                $('#bod-review-value')
                    .text(data.bod_review ?? 0);


                /*
                |--------------------------------------------------------------------------
                | SLA
                |--------------------------------------------------------------------------
                */

                const userSla =
                    Number(data.ach_sla_user ?? 0);

                const committeeSla =
                    Number(data.ach_sla_committee ?? 0);


                $('#ach-sla-user')
                    .text(`${userSla}%`);

                $('#ach-sla-committee')
                    .text(`${committeeSla}%`);


                /*
                |--------------------------------------------------------------------------
                | Update Circle
                |--------------------------------------------------------------------------
                */

                updateSlaCircle(
                    '#sla-user-circle',
                    userSla,
                    '#2196f3'
                );

                updateSlaCircle(
                    '#sla-committee-circle',
                    committeeSla,
                    '#8b5cf6'
                );

            },

            error: function(xhr) {

                console.error(
                    'Failed to load Pending Review:',
                    xhr.status,
                    xhr.responseText
                );

            }

        });

    }


    function updateSlaCircle(
        selector,
        percentage,
        color
    ) {

        percentage = Math.max(
            0,
            Math.min(100, percentage)
        );

        const degree =
            percentage * 3.6;

        $(selector).css(
            'background',
            `conic-gradient(
                ${color} 0deg,
                ${color} ${degree}deg,
                #e8edf3 ${degree}deg,
                #e8edf3 360deg
            )`
        );

    }
</script>
