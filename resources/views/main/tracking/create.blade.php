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
                                <li class="breadcrumb-item active">Add Tracking</li>
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
                            <h4 class="header-title">Add Tracking</h4>

                            <form action="{{ route('tracking.store') }}" method="post" id="fileUploadForm"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="Contract document" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Jenis Dokumen</label>
                                        <select class="form-select js-example-basic-single" name="type">
                                            <option></option>
                                            <option value="Contract">Contract</option>
                                            <option value="License">License</option>
                                            <option value="HAKI">HAKI</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Para Pihak <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="para_pihak"
                                            placeholder="Para pihak" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Ruang Lingkup (TOP, Delivery Time,
                                            Penalty) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="scope"
                                            placeholder="Ruang lingkup (TOP, Delivery Time, Penalty)" required>
                                    </div>
                                </div>

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

                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="simpleinput" class="form-label">Dokumen Pendukung <span
                                                class="text-danger">*</span> <a href="{{ route('master-template.index') }}"
                                                target="_blank"> Download Template</a></label>
                                        <input type="file" name="file" required data-plugins="dropify"
                                            accept="application/pdf" data-height="200" />
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
            // $(document).ready(function () {
            //     $('#fileUploadForm').ajaxForm({
            //         beforeSend: function () {
            //             var percentage = '0';
            //         },
            //         uploadProgress: function (event, position, total, percentComplete) {
            //             var percentage = percentComplete;
            //             $('.progress .progress-bar').css("width", percentage+'%', function() {
            //               return $(this).attr("aria-valuenow", percentage) + "%";
            //             })
            //         },
            //         complete: function (xhr) {
            //             window.history.back();
            //         }
            //     });
            // });
        });
    </script>
@endsection
