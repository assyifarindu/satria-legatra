@extends('layouts.master')

@section('title')
Document Filing |
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
                            <li class="breadcrumb-item"><a href="{{ route('tracking-haki.index') }}">Tracking HAKI</a></li>
                            <li class="breadcrumb-item active">Document Filing</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Document Filing</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Add Document Filing</h4>

                        <form action="{{ route('tracking-haki-drafting.filing') }}" method="post"  enctype="multipart/form-data" id="fileUploadForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $data['document'] }}" id="">

                            <div class="row">
                                <div class="col-lg-10 mt-3">
                                    <label for="simpleinput" class="form-label">HAKI Number  <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="haki_number" placeholder="HAKI Number" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-10 mt-3">
                                    <label for="simpleinput" class="form-label">Judul Ringkasan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" placeholder="Judul Ringkasan" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-10 mt-3">
                                    <label for="simpleinput" class="form-label">Ringkasan <span class="text-danger">*</span></label>
                                    <textarea name="ringkasan" id="summernote-basic" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mt-3">
                                    <label for="simpleinput" class="form-label">Document Final <span class="text-danger">*</span></label>
                                    <input type="file" name="file" required data-plugins="dropify" accept=".doc,.docx,.pdf,.xlsx" data-height="200" />
                                </div>
                            </div>

                            <div class="form-group mt-2">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
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
    $(function () {
        $(document).ready(function () {
            $('#fileUploadForm').ajaxForm({
                beforeSend: function () {
                    var percentage = '0';
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    var percentage = percentComplete;
                    $('.progress .progress-bar').css("width", percentage+'%', function() {
                        return $(this).attr("aria-valuenow", percentage) + "%";
                    })
                },
                complete: function (xhr) {
                    window.location.href = "{{ route('tracking-haki.show', Hashids::encode($data['doc']->request_document_id)) }}";
                }
            });
        });
    });
</script>
@endsection
