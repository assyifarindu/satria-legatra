@extends('layouts.master')

@section('title')
    Title |
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
                            <li class="breadcrumb-item active">Title</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Master Title</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add_modal"><i class="fas fa-plus"></i> Add Title</a>
                        </div>
                        <h4 class="header-title">Master Title</h4>
                        <br><br>
                        <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data['title'] as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_edit" title="Edit" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#edit_modal"
                                                data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-code="{{ $item->code }}"><i class="fas fa-pen"></i></a>
                                            <form action="{{ route('master-title.destroy', $item->id)}}" method="POST" onclick="deleteFunction()" class="d-inline">
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
                <h4 class="modal-title" id="standard-modalLabel">Add Title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-title.store') }}" method="POST" enctype="multipart/form-data" id="fileUploadForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required placeholder="Title name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required placeholder="code">
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
                <h4 class="modal-title" id="standard-modalLabel">Edit Title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('master-title-update') }}" method="POST" id="fileUploadFormDua" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_title">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="simpleinput" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" required placeholder="Title name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="simpleinput" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="code" required placeholder="code">
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
                document.getElementById("id_title").value = $(this).attr('data-id');
                document.getElementById("title").value = $(this).attr('data-name');
                document.getElementById("code").value = $(this).attr('data-code');
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
