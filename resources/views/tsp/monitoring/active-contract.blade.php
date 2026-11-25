@extends('layouts.tsp_master')

@section('title')
    Monitoring |
@endsection

@section('css')
@endsection

@section('content')
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        </div>
                        <h4 class="page-title">Monitoring</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Active Contracts</h4>
                            <br><br>
                            <table id="request-document-table" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document Number Agreement</th>
                                        <th>Customer Name</th>
                                        <th>Title</th>
                                        <th>Document Date</th>
                                        <th>Start Date</th>
                                        <th>Finish Date</th>
                                        <th>PIC Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>
                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
        </div> <!-- container -->
    </div>
    <div id="modal-container"></div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#request-document-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                scrollX: true,
                ajax: `{{ url('tsp/monitoring/active-contracts/data') }}`,
                columns: [{
                        data: null,
                        orderable: false,
                        sortable: false,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'document_date',
                        name: 'document_date'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'depthead_name',
                        name: 'depthead_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let viewUrl = "{{ url('tsp/monitoring/active-contracts') }}/" + row.id;

                            return `
                                <a href="${viewUrl}" target="_blank"
                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                    title="View Detail" tabindex="0" data-plugin="tippy"
                                    data-tippy-placement="top"><i class="fas fa-eye"></i>
                                </a>
                                <a href="#"
                                    class="btn btn-light btn-xs d-inline waves-effect waves-light history_process"
                                    title="History Process" tabindex="0" data-plugin="tippy"
                                    data-tippy-placement="top" data-id="${row.id}"><i
                                    class="mdi mdi-book-clock-outline"></i>
                                </a>
                              `;
                        }
                    },
                ]
            });
        })

        $(document).on('click', '.btn_cancel', function(e) {

            e.preventDefault();

            const id = $(this).data('id');

            const url =
                "{{ url('tsp/request-document') }}/cancel-confirmation/" + id;

            $.ajax({
                url: url,
                type: 'GET',

                beforeSend: function() {
                    console.log('AJAX STARTED');

                    $('#modal-container').html(`
                        <div class="text-center p-3">
                            Loading...
                        </div>
                    `);
                },

                success: function(response) {

                    $('#modal-container').html(response);

                    const modalElement =
                        document.getElementById('cancel-modal');

                    if (!modalElement) {
                        console.error('Element #cancel-modal tidak ditemukan!');
                        return;
                    }

                    const cancelModal =
                        new bootstrap.Modal(modalElement);

                    cancelModal.show();
                },

                error: function(xhr) {

                    console.error('AJAX ERROR:', xhr);

                    alert('Gagal memuat konfirmasi pembatalan.');
                }
            });

        });

        $(document).on('click', '#confirm-cancel', function() {

            const id = $(this).data('id');

            const button = $(this);

            button.prop('disabled', true);

            button.html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Processing...
        `);

            $.ajax({

                url: "{{ url('tsp/request-document') }}/cancel/" +
                    id,

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function(response) {

                    if (response.success) {

                        const modalElement =
                            document.getElementById('cancel-modal');

                        const modal =
                            bootstrap.Modal.getInstance(modalElement);

                        modal.hide();


                        /*Reload DataTable*/

                        $('#request-document-table')
                            .DataTable()
                            .ajax
                            .reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });


                        // alert(response.message);

                    } else {

                        alert(response.message);

                    }

                },

                error: function(xhr) {

                    console.error(xhr);

                    alert(
                        xhr.responseJSON?.message ??
                        'Terjadi kesalahan saat membatalkan Request Document.'
                    );

                },

                complete: function() {

                    button.prop('disabled', false);

                    button.html(`
                    <i class="fas fa-times me-1"></i>
                    Yes, Cancel Request
                `);

                }

            });

        });

        $(document).on('click', '.history_process', function(e) {

            e.preventDefault();

            const id = $(this).data('id');

            const url = "{{ url('tsp/request-document/show-history') }}/" + id;

            $.ajax({

                url: url,

                type: 'GET',

                beforeSend: function() {

                    $('#modal-container').html(`
                    <div class="text-center p-3">
                        Loading...
                    </div>
                `);

                },

                success: function(response) {

                    /*INSERT MODAL*/

                    $('#modal-container').html(response);

                    /*SHOW MODAL*/

                    const modalElement =
                        document.getElementById('history-modal');

                    if (!modalElement) {

                        console.error(
                            'Element #history-modal tidak ditemukan!'
                        );

                        return;

                    }

                    const historyModal =
                        new bootstrap.Modal(modalElement);

                    historyModal.show();


                    /*DESTROY DATATABLE JIKA SUDAH ADA*/

                    if ($.fn.DataTable.isDataTable('#history-table')) {

                        $('#history-table')
                            .DataTable()
                            .destroy();

                    }


                    /*INITIALIZE HISTORY DATATABLE*/
                    $('#history-table').DataTable({

                        processing: true,

                        serverSide: true,

                        destroy: true,

                        scrollX: true,

                        ajax: {
                            url: "{{ url('tsp/request-document/history/data') }}/" + id,
                            type: 'GET'
                        },

                        columns: [

                            {
                                data: null,

                                orderable: false,

                                searchable: false,

                                render: function(
                                    data,
                                    type,
                                    row,
                                    meta
                                ) {

                                    return meta.row +
                                        meta.settings._iDisplayStart +
                                        1;

                                }

                            },

                            {
                                data: 'date',

                                name: 'date',

                                render: function(data) {

                                    if (!data) {
                                        return '-';
                                    }

                                    return data;

                                }

                            },

                            {
                                data: 'action',

                                name: 'action',
                            },

                            {
                                data: 'action_by',

                                name: 'action_by',

                            }

                        ]

                    });

                },

                error: function(xhr) {

                    console.error(xhr);

                    $('#modal-container').html('');

                    alert(
                        xhr.responseJSON?.message ??
                        'Gagal memuat History Process.'
                    );

                }

            });

        });

        $(document).on('click', '.btn_decline', function() {

            const id = $(this).data('id');

            const url =
                "{{ url('tsp/request-document/decline-confirmation') }}/" + id;

            $.ajax({

                url: url,

                type: 'GET',

                beforeSend: function() {

                    $('#modal-container').html(`
                        <div class="text-center p-3">
                            Loading...
                        </div>
                    `);

                },

                success: function(response) {

                    $('#modal-container').html(response);

                    const modalElement =
                        document.getElementById('decline-modal');

                    if (!modalElement) {

                        console.error(
                            'Modal decline-modal tidak ditemukan.'
                        );

                        return;

                    }

                    const declineModal =
                        new bootstrap.Modal(modalElement);

                    declineModal.show();

                },

                error: function(xhr) {

                    console.error(xhr);

                    alert(
                        'Gagal memuat form Decline.'
                    );

                }

            });

        });

        $(document).on('submit', '#decline-form', function(e) {

            e.preventDefault();

            const form = $(this);

            const id = form.data('id');

            const button = $('#confirm-decline');

            const remarkInput = $('#remark');

            const remarkError = $('#remark-error');


            /*
            RESET ERROR
            */

            remarkInput.removeClass('is-invalid');

            remarkError.text('');


            /*
            LOADING BUTTON
            */

            button.prop('disabled', true);

            button.html(`
                <span class="spinner-border spinner-border-sm me-1"></span>
                Processing...
            `);


            /*
            AJAX DECLINE
            */

            console.log('Decline Request Document ID 2:', id);

            $.ajax({

                url: "{{ url('tsp/request-document') }}/decline/" + id,

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}",
                    remark: remarkInput.val()
                },

                success: function(response) {

                    if (response.success) {

                        const modalElement =
                            document.getElementById('decline-modal');

                        const modal =
                            bootstrap.Modal.getInstance(modalElement);

                        if (modal) {
                            modal.hide();
                        }


                        /*
                        RELOAD DATATABLE
                        */

                        $('#request-document-table')
                            .DataTable()
                            .ajax
                            .reload(null, false);


                        /*
                        SUCCESS ALERT
                        */

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });

                    }

                },

                error: function(xhr) {

                    console.error(xhr);


                    /*
                    VALIDATION ERROR
                    */

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON.errors;

                        if (errors.remark) {

                            remarkInput.addClass('is-invalid');

                            remarkError.text(
                                errors.remark[0]
                            );

                        }

                        return;
                    }


                    /*
                    SYSTEM ERROR
                    */

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ??
                            'Terjadi kesalahan saat menolak Request Document.'
                    });

                },

                complete: function() {

                    button.prop('disabled', false);

                    button.html(`
                        <i class="fas fa-times me-1"></i>
                        Yes, Decline Request
                    `);

                }

            });

        });
    </script>
@endsection
