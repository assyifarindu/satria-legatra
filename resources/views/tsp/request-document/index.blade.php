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
                            <div class="float-end">
                                <a href="{{ route('tsp.request-document.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Create Request</a>
                            </div>
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
                        name: 'title'
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
                        name: 'sign_status'
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
                                                                            class="btn btn-danger btn-xs d-inline waves-effect waves-light btn_cancel"
                                                                            title="Cancel Request"
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

            console.log('CANCEL BUTTON CLICKED');

            const id = $(this).data('id');

            console.log('ID:', id);

            const url =
                "{{ url('tsp/request-document') }}/cancel-confirmation/" + id;

            console.log('URL:', url);

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

                    console.log('AJAX SUCCESS:', response);

                    $('#modal-container').html(response);

                    const modalElement =
                        document.getElementById('cancel-modal');

                    console.log('MODAL ELEMENT:', modalElement);

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
    </script>
@endsection
