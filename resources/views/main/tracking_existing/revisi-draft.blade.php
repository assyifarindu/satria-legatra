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
                                <li class="breadcrumb-item active">Revisi Drafting Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Drafting Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Revisi Drafting Document</h4>

                            <form action="{{ route('tracking-drafting.revisi-update') }}" method="post" id="fileUploadForm"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $data['document']->id }}" id="">

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Document <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="Contract document" required
                                            value="{{ $data['document']->description }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Priority</label>
                                        <select class="form-select js-example-basic-single" name="priority">
                                            <option value="1" @if ($data['document']->priority == 1) selected @endif>High
                                            </option>
                                            <option value="2" @if ($data['document']->priority == 2) selected @endif>Medium
                                            </option>
                                            <option value="3" @if ($data['document']->priority == 3) selected @endif>Low
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Contract Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contract_number"
                                            placeholder="Contract Number" required
                                            value="{{ $data['document']->contract_number }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Company</label>
                                        <select class="form-select js-example-basic-single" name="company">
                                            <option></option>
                                            @foreach ($data['company'] as $k)
                                                <option value="{{ $k->id }}"
                                                    @if ($k->name == $data['document']->company) selected @endif>{{ $k->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">PIC</label>
                                        <select class="form-select js-example-basic-single" name="pic">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}"
                                                    @if ($i->id == $data['document']->pic) selected @endif>{{ $i->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Duration (Duration - Alert)</label>
                                        <select class="form-select js-example-basic-single" name="duration">
                                            <option></option>
                                            @foreach ($data['alert'] as $j)
                                                <option value="{{ $j->id }}"
                                                    @if ($j->id == $data['document']->alert_id) selected @endif>Durasi
                                                    {{ $j->duration }} hari, Alert {{ $j->start_alert }} hari sebelumnya
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Contract Date <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control basic-datepicker" name="contract_date"
                                            placeholder="Contract Date" required
                                            value="{{ $data['document']->contract_date }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Note <span
                                                class="text-danger">*</span></label>
                                        <textarea name="note" id="" class="form-control" cols="30" rows="5" placeholder="Note">{{ $data['document']->note }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="simpleinput" class="form-label">Dokumen Pendukung <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="file" data-plugins="dropify"
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
