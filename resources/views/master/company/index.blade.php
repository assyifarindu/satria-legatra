@extends('layouts.master')

@section('title')
    Company |
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
                            <li class="breadcrumb-item active">Company</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Master Company</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal"><i class="fas fa-plus"></i> Add Company</a>
                        </div>
                        <h4 class="header-title">Master Company</h4>
                        <br><br>
                        <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Short Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data['company'] as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->short_name }}</td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_edit" title="Edit" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#edit_modal"
                                                data-name="{{ $item->name }}" data-shortname={{ $item->short_name }} data-id="{{ $item->id }}"><i class="fas fa-pen"></i></a>
                                            <form action="{{ route('master-company.destroy', $item->id)}}" method="POST" onclick="deleteFunction()" class="d-inline">
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

<div id="add_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Company</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-company.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="PT United Tractor Pandu Engineering">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Short Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="short_name" required placeholder="UTPE">
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


<div id="edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Edit Company</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-company-update') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_company">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="PT United Tractor Pandu Engineering">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Short Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="short_name" name="short_name" required placeholder="UTPE">
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
            $('.btn_edit').click(function(){
                document.getElementById("id_company").value = $(this).attr('data-id');
                document.getElementById("name").value = $(this).attr('data-name');
                document.getElementById("short_name").value = $(this).attr('data-shortname');
            });
        });

        function deleteFunction() {
            event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1,
            }).then(function(e) {
                e.value ?
                    form.submit() :
                    e.dismiss === Swal.DismissReason.cancel &&
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your data is safe :)",
                        icon: "error",
                        confirmButtonColor: "#4a4fea",
                    });
            });
        }
    </script>
@endsection
