<script>
    $(document).ready(function() {

        // Basic datatable
        $(".basic-datatable").DataTable({
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        });

        var scroll = $(".scroll-horizontal-datatable").DataTable({
            scrollX: !0,
            stateSave: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
        });

        scroll.columns.adjust().draw();

        // button datatable
        var a = $(".datatable-buttons").DataTable({
            lengthChange: !1,
            buttons: [{
                    extend: "copy",
                    className: "btn-light"
                },
                {
                    extend: "print",
                    className: "btn-light"
                },
                {
                    extend: "pdf",
                    className: "btn-light"
                },
            ],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        });

        // state saving datatable
        $(".state-saving-datatable").DataTable({
                stateSave: !0,
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>",
                    },
                },
                drawCallback: function() {
                    $(".dataTables_paginate > .pagination").addClass(
                        "pagination-rounded"
                    );
                },
            }),
            $(".dataTables_length select").addClass("form-select form-select-sm"),
            $(".dataTables_length select").removeClass(
                "custom-select custom-select-sm"
            ),
            $(".dataTables_length label").addClass("form-label");

        $(".range-datepicker").flatpickr({
            mode: "range"
        });

        $(".basic-datepicker").flatpickr({
            "disable": [
                function(date) {
                    // return true to disable
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            "locale": {
                "firstDayOfWeek": 1 // start week on Monday
            }
        });

        $(".yearpicker").yearpicker();

        $('.js-example-basic-multiple').select2({
            placeholder: "Select a option",
            allowClear: true
        });
        $('.js-example-basic-single').select2({
            placeholder: "Select a option",
            allowClear: true
        });

        $('#freeze-table').DataTable({
            fixedColumns: {
                left: 3
            },
            scrollCollapse: true,
            scrollX: true,
            scrollY: '1000px'
        });

        $("#selectize-tags").selectize({
            persist: !1,
            createOnBlur: !0,
            create: !0,
        });
        $(".selectize").selectize({
            maxItems: 10
        });

        $('.summernote-complete').summernote({
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['bold', 'italic']], //Specific toolbar display
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ],
        });
    });

    !(function(t) {
        "use strict";

        function e() {
            this.$body = t("body");
        }
        (e.prototype.init = function() {
            (Dropzone.autoDiscover = !1),
            t('[data-plugin="dropzone"]').each(function() {
                var e = t(this).attr("action"),
                    o = t(this).data("previewsContainer"),
                    i = {
                        url: e
                    };
                o && (i.previewsContainer = o);
                var r = t(this).data("uploadPreviewTemplate");
                r && (i.previewTemplate = t(r).html());
                t(this).dropzone(i);
            });
        }),
        (t.FileUpload = new e()),
        (t.FileUpload.Constructor = e);
    })(window.jQuery),
    (function() {
        "use strict";
        window.jQuery.FileUpload.init();
    })(),
    0 < $('[data-plugins="dropify"]').length &&
        $('[data-plugins="dropify"]').dropify({
            messages: {
                default: "Drag and drop a file here or click",
                replace: "Drag and drop or click to replace",
                remove: "Remove",
                error: "Ooops, something wrong appended.",
            },
            error: {
                fileSize: "The file size is too big (1M max)."
            },
        });

    $(document).ready(function() {
        $(document).on('click', '#btn-logout', function() {
            event.preventDefault();
            const url = $(this).attr("data-url");
            Swal.fire({
                title: 'Are you sure?',
                text: "You are attempting to log out of system",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logout-form').submit();
                } else {
                    return false;
                }
            })
        });
    });
</script>
