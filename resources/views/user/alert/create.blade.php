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
                                <li class="breadcrumb-item active">Add Request Extend</li>
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

                            <form action="{{ route('request-extend.store') }}" method="post" id="fileUploadForm"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Jenis Dokumen</label>
                                        @php
                                            if ($data['base_document']->category == 1) {
                                                $category = 'Contract / Alert';
                                            } elseif ($data['base_document']->category == 2) {
                                                $category = 'License';
                                            } else {
                                                $category = 'HAKI';
                                            }
                                        @endphp
                                        <input class="form-control" value="{{ $category }}" readonly>
                                        <input type="hidden" name="type" value="{{ $data['base_document']->category }}">
                                        <input type="hidden" name="email_alert" value="{{ $data['email']->id }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Dokumen</label>
                                        <input class="form-control"
                                            value="{{ $data['base_document']->contract_number }} - {{ $data['base_document']->description }}"
                                            readonly>
                                        <input type="hidden" name="title" value="{{ $data['base_document']->id }}">
                                    </div>
                                </div>

                                @if ($data['base_document']->category == 1)
                                    <div class="row advancedWrapper">
                                        <div class="col-lg-10 mt-3">
                                            <label for="simpleinput" class="form-label">Para Pihak <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="para_pihak[]"
                                                placeholder="Para pihak" value="" required>
                                        </div>
                                        <div class="col-lg-1 mt-3">
                                            <label for="simpleinput" class="form-label">Action <span
                                                    class="text-danger">*</span></label>
                                            <button class="btn btn-primary form-control add"><i
                                                    class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">PIC Email <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select js-example-basic-single" name="email">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}">{{ $i->Company->name }} -
                                                    {{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 uploadWrapper">
                                    <div class="col-lg-11">
                                        <label for="simpleinput" class="form-label">Dokumen Pendukung</label>
                                        <input type="file" class="form-control" name="attachment[]"
                                            accept=".doc,.docx,.pdf,.xlsx" placeholder="Attachment">
                                    </div>
                                    <div class="col-lg-1">
                                        <label for="simpleinput" class="form-label">Action</label>
                                        <button class="btn btn-primary form-control" id="add_upload"><i
                                                class="fas fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 0%"></div>
                                    </div>
                                </div>

                                <div class="text-end mt-2">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
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
@endsection

@section('js')
    <script>
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
                        window.location.href = "{{ route('request-extend.index') }}";
                    }
                });
            });
        });

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

            // Document
            let upload_wrapper = $('.uploadWrapper');
            $(document).on("click", "#add_upload", function(e) {
                e.preventDefault();
                $(upload_wrapper).append('<div class="row numberUpload">' +
                    '<div class="col-lg-11 mt-3">' +
                    '<input type="file" class="form-control" required name="attachment[]" accept=".doc,.docx,.pdf,.xlsx" placeholder="Attachment" required>' +
                    '</div>' +
                    '<div class="col-lg-1 mt-3">' +
                    '<button class="btn btn-danger form-control remove_upload"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                    '</div>');
            });

            $(document).on("click", ".remove_upload", function() {
                $(this).parents("div .numberUpload").remove();
            });
        });
    </script>
@endsection
