@extends('layouts.master')

@section('title')
    Pic Email Notifications |
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
                                <li class="breadcrumb-item active">Pic Email Notifications</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Pic Email Notifications</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#add_modal"><i class="fas fa-plus"></i> Add Pic</a>
                            </div>
                            <h4 class="header-title">Master Pic Email Notifications</h4>
                            <br><br>
                            <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['pic'] as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->user_id }}</td>
                                            <td>{{ $item->user_id }}</td>
                                            <td>
                                                <a href="#"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_edit"
                                                    title="Edit" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top" data-bs-toggle="modal"
                                                    data-bs-target="#edit_modal_{{ $no }}"><i
                                                        class="fas fa-pen"></i></a>
                                                <form action="{{ route('master-template.destroy', $item->id) }}"
                                                    method="POST" onclick="deleteFunction()" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                        title="Delete" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-trash-alt"
                                                            onsubmit="deleteFunction()"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @php
                                            $no++;
                                        @endphp
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

    <div id="add_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Pic Email</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pic-email.store') }}" method="POST" enctype="multipart/form-data"
                    id="fileUploadForm">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="simpleinput" class="form-label"> Employee <span
                                        class="text-danger">*</span></label>
                                <select class="form-control js-example-basic-single" name="user">
                                    @foreach ($data['user'] as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} - [{{ $item->email }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 0%"></div>
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
    </div>

    <!-- /.modal -->

    {{-- @php
        $order = 1;
    @endphp
    @foreach ($data['template'] as $template)
        <div id="edit_modal_{{ $order++ }}" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="standard-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Edit Template</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('master-template-update') }}" method="POST" id="fileUploadFormDua"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{ $template->id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="simpleinput" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        value="{{ $template->title }}" required placeholder="Template name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="simpleinput" class="form-label">Company <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="company" id="company_edit">
                                        @foreach ($data['company'] as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($template->company_id == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="simpleinput" class="form-label">Description <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" cols="30" rows="5" id="description" class="form-control" required
                                        placeholder="Description">{{ $template->description }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-3">
                                    <label for="simpleinput" class="form-label">File Template</label>
                                    <input type="file" name="file" data-plugins="dropify"
                                        accept=".doc,.docx,.pdf,.xlsx" data-height="150" />
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                        role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 0%"></div>
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
    @endforeach --}}
@endsection
@section('js')
    {{-- <script>
        $(document).ready(function() {
            $('.btn_edit').click(function() {
                document.getElementById("id_template").value = $(this).attr('data-id');
                document.getElementById("title").value = $(this).attr('data-title');
                $("textarea#description").val($(this).attr('data-description'));
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

        $(function() {
            $(document).ready(function() {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {
                        location.reload();
                    }
                });
            });
        });

        $(function() {
            $(document).ready(function() {
                $('#fileUploadFormDua').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {
                        location.reload();
                    }
                });
            });
        });
    </script> --}}
@endsection
