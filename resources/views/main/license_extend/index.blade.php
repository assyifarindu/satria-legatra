@extends('layouts.master')

@section('title')
    Extend Licenses |
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
                            <li class="breadcrumb-item active">Extend Licenses</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Extend Licenses</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Data Extend Licenses</h4>
                        <br><br>
                        <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Extended Licenses Number</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Company</th>
                                    <th>Licenses Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                
                                @foreach ($data['license'] as $item)
                                    <tr>

                                        <td class="align-middle">{{ $no++ }}</td>
                                        <td class="align-middle">{{ $item->extend_contract_number }}</td>
                                        <td class="align-middle">{{ $item->note }}</td>
                                        <td class="align-middle">Licenses</td>
                                        <td class="align-middle">{{ $item->Document->company }}</td>
                                        <td class="align-middle">{{ formatDate($item->extended_date) }}</td>
          
                                        <td class="align-middle">
                                            <a href="{{ route('extended-license.show', Hashids::encode($item->id)) }}" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_extend" title="View Detail" tabindex="0" data-plugin="tippy" data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view" title="Update Company" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#view_modal" 
                                            data-company="{{ $item->Document->company }}" data-id="{{ $item->id }}"><i class="fas fa-pencil-alt"></i></a>
                                            <form action="" method="POST" onclick="deleteFunction()" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-light btn-xs d-inline waves-effect waves-light" title="Delete" tabindex="0" data-plugin="tippy" data-tippy-placement="top"><i class="fas fa-trash-alt" onsubmit="deleteFunction()"></i></button>
                                            </form>
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

@endsection

@section('js')
<script>
    $(document).ready(function(){
        $('.btn_view').click(function(){
            document.getElementById("doc_id").value = $(this).attr('data-id');
            document.getElementById("company").value = $(this).attr('data-company');

        });
    });
</script>
@endsection
