@extends('layouts.tsp_master')

@section('title')
    Request Document |
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
                        <h4 class="page-title">Request Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (getRoles(Auth::user()->id) !== 'Admin Legal TSP')
                                <div class="float-end">
                                    <a href="{{ route('tsp.request-document.create') }}" class="btn btn-sm btn-primary"><i
                                            class="fas fa-plus"></i> Create Request</a>
                                </div>
                            @endif
                            <h4 class="header-title">Request Document Contract</h4>
                            <br><br>
                            <table id="request-document-table" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Status</th>
                                        <th>Document Number</th>
                                        <th>Title</th>
                                        <th>Contract Type</th>
                                        <th>Requester</th>
                                        <th>Sign Status</th>
                                        <th>Project Category</th>
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
                ajax: `{{ url('tsp/request-document/data') }}`,
                columns: [{
                        data: null,
                        orderable: false,
                        sortable: false,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            $status = row.status_id;
                            if (row.is_cancel) {
                                return '<span class="badge bg-danger">Cancel</span>';
                            }

                            switch (parseInt($status)) {
                                case 1:
                                    return '<span class="badge bg-warning">Draft</span>';
                                case 2:
                                    return '<span class="badge bg-primary">Submitted</span>';
                                case 3:
                                    return '<span class="badge bg-danger">Cancel</span>';
                                case 4:
                                    return '<span class="badge bg-danger">Decline</span>';
                                case 5:
                                    return '<span class="badge bg-primary">Drafting</span>';
                                case 6:
                                    return '<span class="badge bg-primary">User Review</span>';
                                case 7:
                                    return '<span class="badge bg-primary">Verified By User</span>';
                                case 8:
                                    return '<span class="badge bg-primary">Committee Review</span>';
                                case 9:
                                    return '<span class="badge bg-primary">Verified By Committee</span>';
                                case 10:
                                    return '<span class="badge bg-primary">Need Revision</span>';
                                case 11:
                                    return '<span class="badge bg-primary">Final Check</span>';
                                case 12:
                                    return '<span class="badge bg-primary">Fully Approved</span>';
                                case 13:
                                    return '<span class="badge bg-success">Cleared for Delivery</span>';
                                default:
                                    return '-';
                            }
                        }
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            const viewUrl = "{{ url('tsp/request-document/tracking') }}/" + row.id;
                            return `<a href="${viewUrl}">${data ?? '-'}</a>`;
                        }
                    },
                    {
                        data: 'contract_type',
                        name: 'contract_type'
                    },
                    {
                        data: 'requester',
                        name: 'requester'
                    },
                    {
                        data: 'sign_status',
                        name: 'sign_status',
                        render: function(data) {
                            switch (data) {
                                case 'Not Signed':
                                    return `<span class="badge bg-danger">${data}</span>`;
                                case 'Partial Signed':
                                    return `<span class="badge bg-warning">${data}</span>`;
                                case 'Fully Signed':
                                    return `<span class="badge bg-success">${data}</span>`;
                                default:
                                    return "-";
                            }
                        }
                    },
                    {
                        data: 'project_category',
                        name: 'project_category'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let editUrl = "{{ url('tsp/request-document') }}/edit/" + row.id;
                            let viewUrl = "{{ url('tsp/request-document') }}/" + row.id;

                            return `
                                ${ (row.status_id == 1 || row.status_id == 2) ? `
                                                    <a href="${editUrl}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="Edit" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-pen"></i></a>
                                                ` : '' }
                                <a href="${viewUrl}"
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
                                ${row.status_id == 1 || row.status_id == 2 ? `
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-danger btn-xs d-inline waves-effect waves-light {{ getRoles(Auth::user()->id) === 'Admin Legal TSP' ? 'btn_decline' : 'btn_cancel' }}"
                                                        title="{{ getRoles(Auth::user()->id) === 'Admin Legal TSP' ? 'Decline Request' : 'Cancel Request' }}"
                                                        tabindex="0"
                                                        data-plugin="tippy"
                                                        data-tippy-placement="top"
                                                        data-id="${row.id}">

                                                        <i class="fas fa-times"></i>

                                                    </a>
                                                ` : ''}                            `;
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
