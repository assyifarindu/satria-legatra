@extends('layouts.master')

@section('title')
    Request Extend Document |
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
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('request-extend.index') }}">Request Extend
                                        Document</a></li>
                                <li class="breadcrumb-item active">Edit Request Extend</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Extend Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Request Extend Document</h4>

                            <form action="{{ route('request-extend.update', $data['tracking']->id) }}" method="post"
                                id="fileUploadForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Jenis Dokumen</label>
                                        <input type="text" class="form-control bg-soft-secondary" name="type" readonly
                                            placeholder="Contract document" value="{{ $data['tracking']->type }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Dokumen</label>
                                        <select class="form-select js-example-basic-single" name="title">
                                            <option></option>
                                            @foreach ($data['base_document'] as $i)
                                                <option value="{{ $i->id }}"
                                                    @if ($i->id == $data['tracking']->base_document_id) selected @endif>{{ $i->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($data['tracking']->type == 'Contract')
                                    <div class="row advancedWrapper">
                                        <div class="col-lg-10 mt-3">
                                            <label for="simpleinput" class="form-label">Para Pihak <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="para_pihak[]"
                                                placeholder="Para pihak" value="{{ $data['first_pihak']->name }}" required>
                                        </div>
                                        <div class="col-lg-1 mt-3">
                                            <label for="simpleinput" class="form-label">Action <span
                                                    class="text-danger">*</span></label>
                                            <button class="btn btn-primary form-control add"><i
                                                    class="fas fa-plus"></i></button>
                                        </div>
                                        @if (count($data['all_pihak']) > 1)
                                            @foreach ($data['all_pihak'] as $i)
                                                @if ($data['first_pihak']->id != $i->id)
                                                    <div class="row numberOfDocument">
                                                        <div class="col-lg-10 mt-3">
                                                            <input type="text" class="form-control" name="para_pihak[]"
                                                                placeholder="Para pihak" id="document_1"
                                                                value="{{ $i->name }}" required>
                                                        </div>
                                                        <div class="col-lg-1 mt-3">
                                                            <button class="btn btn-danger form-control remove"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>

                                    @if ($data['tracking']->type == 'Contract')
                                        <div class="downloadWrapper">
                                            <div class="row mt-2">
                                                <div class="col-lg-12">
                                                    <div class="alert alert-warning alert-dismissible fade show"
                                                        role="alert">
                                                        Jika anda membutuhkan template untuk diupload pada request document,
                                                        silakan download pada halaman dashboard atau akses <a href="#"
                                                            data-bs-toggle="modal" data-bs-target="#template-document">link
                                                            ini</a>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">PIC Email <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select js-example-basic-single" name="email">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}"
                                                    @if ($data['tracking']->pic_id == $i->id) selected @endif>
                                                    {{ $i->Company->name }} - {{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Department In Charge <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control selectize" multiple name="doc_scope[]" required>
                                            <option value="">Select scope</option>
                                            @for ($i = 0; $i < count($data['department']); $i++)
                                                <option
                                                    value="{{ $data['department'][$i]['id'] }}-{{ $data['department'][$i]['nama'] }}"
                                                    @if (checkDocumentScope($data['tracking']->id, $data['department'][$i]['id'])) selected @endif>
                                                    {{ $data['department'][$i]['id'] }} -
                                                    {{ $data['department'][$i]['nama'] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" name="extend_automatically"
                                                @if ($data['tracking']->is_extend_automatically) checked @endif id="example-checkbox1">
                                            <label for="example-checkbox1" class="form-check-label"
                                                style="margin-left: 20px;">Extend Document
                                                Secara
                                                Otomatis
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" name="unlimited_duration"
                                                @if ($data['tracking']->is_unlimited_duration) checked @endif id="unlimited_duration">
                                            <label for="example-checkbox1" class="form-check-label"
                                                style="margin-left: 20px;">Unlimited Duration
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Notes </label>
                                        <textarea name="note" id="" cols="10" rows="3" class="form-control">{{ $data['tracking']->note }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label for="simpleinput" class="form-label">Dokumen Pendukung <span
                                            class="text-danger"></span></label>
                                    <a href="#"
                                        class="btn btn-success btn-xs d-inline waves-effect waves-light btn_upload"
                                        title="Add Document" tabindex="0" data-plugin="tippy"
                                        data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#add_modal"
                                        data-id="{{ $data['tracking']->id }}">Add Document <i
                                            class="fas fa-plus"></i></a>
                                </div>
                                @foreach ($data['document'] as $fb)
                                    <div class="border p-3 mb-3 rounded">
                                        @if ($fb->file != '')
                                            <div class="float-end">
                                                <a href="{{ route('request-extend.download-upload', $fb->id) }}"><i
                                                        class="mdi mdi-file-download-outline text-muted font-20"
                                                        title="Download" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"></i></a>
                                                <a
                                                    href="{{ route('request-extend.destroy-upload', Hashids::encode($fb->id)) }}"><i
                                                        class="mdi mdi-trash-can-outline text-muted font-20"
                                                        title="Hapus" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"></i></a>
                                            </div>
                                        @endif
                                        <div class="form-check">
                                            <label class="form-check-label font-16 fw-bold" for="BillingOptRadio2"><a
                                                    href="{{ route('request-extend.download-upload', $fb->id) }}"><i
                                                        class="mdi mdi-file-document"></i> {{ $fb->file }}</a></label>
                                        </div>

                                    </div>
                                @endforeach

                                <div class="form-group mt-2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 0%"></div>
                                    </div>
                                </div>

                                <div class="text-end mt-2">
                                    <button class="btn btn-primary waves-effect waves-light"
                                        type="submit">Submit</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                </div>
                            </form>
                            <!-- end row-->

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div><!-- end col -->
            </div>
        </div>
    </div>

    <div id="add_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Document</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('request-extend.add-upload') }}" method="POST" enctype="multipart/form-data"
                    id="fileUploadFormEdit">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_req">
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <label for="simpleinput" class="form-label">File Template <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="file" required data-plugins="dropify"
                                    accept=".doc,.docx,.pdf,.xlsx" data-height="150" />
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    id="progress-upload" style="width: 0%"></div>
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
    <div id="template-document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Template Document</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height: 400px; max: 400px;">
                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach (getTemplateByUser() as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->company_name }}</td>
                                        <td>
                                            <a href="{{ route('download.template', $item->id) }}"><i
                                                    class="fas fa-donwnload"></i> Download</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        // $(function() {
        //     $(document).ready(function() {
        //         $('#fileUploadForm').ajaxForm({
        //             beforeSend: function() {
        //                 var percentage = '0';
        //             },
        //             uploadProgress: function(event, position, total, percentComplete) {
        //                 var percentage = percentComplete;
        //                 $('.progress .progress-bar').css("width", percentage + '%', function() {
        //                     return $(this).attr("aria-valuenow", percentage) + "%";
        //                 })
        //             },
        //             complete: function(xhr) {
        //                 window.location.href = "{{ route('request-extend.index') }}";
        //             }
        //         });
        //     });
        // });

        $(function() {
            $(document).ready(function() {
                $('#fileUploadFormEdit').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('#progress-upload').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {
                        location.reload();
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.btn_upload').click(function() {
                document.getElementById("id_req").value = $(this).attr('data-id');
            });
        });

        $(document).ready(function() {
            var rowCount = $('.advancedWrapper tr').length;
            for (var i = 1; i <= rowCount; i++) {
                $("#document_" + i).empty();
            }

            var wrapper = $(".advancedWrapper");
            var i = 1;
            $(document).on("click", ".add", function(e) {
                e.preventDefault();
                i++;
                $(wrapper).append('<div class="row numberOfDocument"><div class="col-lg-10 mt-3">' +
                    '<input type="text" class="form-control" name="para_pihak[]" id="document_1" placeholder="Para pihak" required>' +
                    '</div>' +
                    '<div class="col-lg-1 mt-3">' +
                    '<button class="btn btn-danger form-control remove"><i class="fas fa-trash"></i></button>' +
                    '</div></div>');

            });

            $(document).on("click", ".remove", function() {
                $(this).parents("div .numberOfDocument").remove();
            });
        });
    </script>
@endsection
