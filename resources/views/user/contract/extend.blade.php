@extends('layouts.master')

@section('title')
    Extend Contract |
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
                            <li class="breadcrumb-item active">Extend Contract</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Extend Contract</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Data Extend Contract</h4>
                        <br><br>
                        <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Extended Contract Number</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Company</th>
                                    <th>Deal Date</th>
                                    <th>Renew Due Date</th>
                                    <th>Days Remaining</th>
                                    <th>Start Alert</th>
                                    <th>Views</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                
                                @foreach ($data['contract'] as $item)
                                    <tr>

                                        <td class="align-middle">{{ $no++ }}</td>
                                        <td class="align-middle"></td>
                                        <td class="align-middle"></td>
                                        <td class="align-middle">{{ $item->extend_contract_number }}</td>
                                        <td class="align-middle">{{ $item->note }}</td>
                                        <td class="align-middle">Contract</td>
                                        <td class="align-middle">{{ $item->Document->company }}</td>
                                        <td class="align-middle">{{ formatDate($item->extended_date) }}</td>
                                        <td class="align-middle"></td>
                                        <td class="align-middle"></td>
                                        <td class="align-middle">{{ $item->Alert->start_alert }}</td>
                                        <td class="align-middle"><a href="#" class="d-inline waves-effect waves-light viewer" title="Views" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-id="{{ $item->id }}"><i class="fas fa-eye"></i></a> <span class="d-inline"> {{ countViewExtendDocument($item->id) }} views</span></td>
                                        <td class="align-middle">
                                            <a href="{{ route('extend-contract-user.show', Hashids::encode($item->id)) }}" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_extend" title="View Detail" tabindex="0" data-plugin="tippy" data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('extend-contract-user.show-ringkasan',  Hashids::encode($item->id )) }}" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view" title="Ringkasan" tabindex="0" data-plugin="tippy" data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->

</div> <!-- content -->

<div id="view_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Detail Contract</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('extended-contract.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="doc_id" id="doc_id">
                    <div class="row mb-2">
                        <div class="col-lg-12 mt-1">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company" id="company" placeholder="Company" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Viewer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>List Viewer Document.</p>
                <div id="modal-table">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('.btn_view').click(function(){
                document.getElementById("doc_id").value = $(this).attr('data-id');
                document.getElementById("company").value = $(this).attr('data-company');

            });
        });

        $(document).ready(function(){
            $('.viewer').click(function(){
                var doc_id = $(this).data('id');

                $.ajax({
                    url: '{{ url("extended-contract/get-data") }}/'+doc_id,
                    type: 'get',
                    success: function(response){
                        // Add response in Modal body
                        $('#modal-table').html(response);

                        // Display Modal
                        $('#detail-modal').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
