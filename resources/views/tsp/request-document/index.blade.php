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
                columns: [
                    {
                        data: null,
                        orderable: false,
                        sortable: false,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                            return `
                                <a href="/tsp/request-document/${row.id}/edit" class="btn btn-sm btn-primary">Edit</a>
                                <form action="/tsp/request-document/${row.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            `;
                        }
                    },
                ]
            });
        })
    </script>
@endsection
