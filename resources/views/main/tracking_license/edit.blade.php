@extends('layouts.master')

@section('title')
    Tracking |
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
                                <li class="breadcrumb-item"><a href="{{ route('tracking.index') }}">Tracking</a></li>
                                <li class="breadcrumb-item active">Edit Tracking</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Tracking</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Tracking</h4>

                            <form action="{{ route('tracking.update', $data['tracking']->id) }}" method="post"
                                id="fileUploadForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="Contract document" value="{{ $data['tracking']->title }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Jenis Dokumen</label>
                                        <select class="form-select js-example-basic-single" name="type">
                                            <option value="Contract" @if ($data['tracking']->type == 'Contract') checked @endif>
                                                Contract</option>
                                            <option value="License" @if ($data['tracking']->type == 'License') checked @endif>License
                                            </option>
                                            <option value="HAKI" @if ($data['tracking']->type == 'HAKI') checked @endif>HAKI
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Para Pihak <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="para_pihak"
                                            placeholder="Para pihak" value="{{ $data['tracking']->para_pihak }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Ruang Lingkup (TOP, Delivery Time,
                                            Penalty)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="scope"
                                            placeholder="Ruang lingkup (TOP, Delivery Time, Penalty)"
                                            value="{{ $data['tracking']->scope }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Send Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="Email"
                                            value="{{ $data['tracking']->email }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="simpleinput" class="form-label">Dokumen Pendukung <span
                                                class="text-danger"></span></label>
                                        <input type="file" name="file" data-plugins="dropify" accept="application/pdf"
                                            data-height="200" />
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
                        window.history.back();
                    }
                });
            });
        });
    </script>
@endsection
