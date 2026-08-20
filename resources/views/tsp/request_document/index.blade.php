@extends('layouts.master_tsp')

@section('title')
    Request Feedback/Drafting Document |
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
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Request Feedback/Drafting Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Feedback/Drafting Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('request-document.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Create Request</a>
                            </div>
                            <h4 class="header-title">Data Request Feedback/Drafting Document</h4>
                            <br><br>
                            <table id="request-document-table" class="table nowrap w-100 scroll-horizontal-datatable">

                                <thead>

                                    <tr>

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

                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->
    <div id="detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">History Process</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>History Process</h6>
                    <p>History Process Request Document.</p>
                    <div id="modal-table">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="cancel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Cancel Request</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('request-document.cancel') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <h3>Apakah anda yakin ?</h3>
                        <p>Tekan confirm untuk membatalkan request ini.</p>
                        <input type="hidden" name="id_cancel" id="id_cancel">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Confirm, cancel</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script>
        $(function() {

            $('#request-document-table').DataTable({

                processing: true,

                serverSide: true,

                scrollX: true,

                ajax: {
                    url: "{{ route('tsp.request-document.data') }}",
                    type: "GET"
                },

                columns: [

                    {
                        data: 'status'
                    },

                    {
                        data: 'document_number'
                    },

                    {
                        data: 'title'
                    },

                    {
                        data: 'contract_type'
                    },

                    {
                        data: 'requester'
                    },

                    {
                        data: 'sign_status'
                    },

                    {
                        data: 'project_category'
                    },

                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'></i>",
                        next: "<i class='mdi mdi-chevron-right'></i>"
                    }
                },

                drawCallback: function() {

                    $(".dataTables_paginate > .pagination")
                        .addClass("pagination-rounded");

                }

            });

        });

        $(document).ready(function() {
            $('.history_process').click(function() {
                var doc_id = $(this).data('id');

                $.ajax({
                    url: '{{ url('request-document/get-data') }}/' + doc_id,
                    type: 'get',
                    data: {
                        doc_id: doc_id
                    },
                    success: function(response) {
                        // Add response in Modal body
                        $('#modal-table').html(response);

                        // Display Modal
                        $('#detail-modal').modal('show');
                    }
                });
            });

            $('.btn_cancel').click(function() {
                document.getElementById("id_cancel").value = $(this).attr('data-id');
            });
        });
    </script>
@endsection
