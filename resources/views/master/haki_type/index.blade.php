@extends('layouts.master')

@section('title')
    Tipe Haki |
@endsection

@section('content')
<div class="content">

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page Tipe Haki -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Tipe Haki</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Master Tipe Haki</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal"><i class="fas fa-plus"></i> Add Tipe Haki</a>
                        </div>
                        <h4 class="header-title">Master Tipe Haki</h4>
                        <br><br>
                        <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data['haki_type'] as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_edit" title="Edit" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#edit_modal"
                                                data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-description="{{ $item->description }}"><i class="fas fa-pen"></i></a>
                                            <form action="{{ route('master-haki-type.destroy', $item->id)}}" method="POST" onclick="deleteFunction()" class="d-inline">
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
                <h4 class="modal-title" id="standard-modalLabel">Add Tipe Haki</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-haki-type.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Tipe Haki name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description"  required placeholder="Description"></textarea>
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
                <h4 class="modal-title" id="standard-modalLabel">Edit Tipe Haki</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-haki-type-update') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_haki">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required placeholder="Tipe Haki">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="description" required placeholder="description"></textarea>
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
                document.getElementById("id_haki").value = $(this).attr('data-id');
                document.getElementById("name").value = $(this).attr('data-name');
                document.getElementById("description").value = $(this).attr('data-description');
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
