@extends('layouts.master')

@section('title')
    Closing Meeting |
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
                            <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('open-meeting.index') }}">Closing Meeting</a></li>
                            <li class="breadcrumb-item active">Add Closing Meeting</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Closing Meeting</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Add Closing Meeting</h4>

                        <form action="{{ route('closing-meeting.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mt-3">
                                    <label for="example-select" class="form-label">RKIA</label>
                                    <select class="form-select" name="rkia" id="example-select">
                                        <option value="">-- Select RKIA --</option>
                                        @foreach ($data['rkia'] as $item)
                                            <option value="{{ $item->id }}">{{ $item->rkia }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mt-3">
                                    <label for="simpleinput" class="form-label">Tanggal Closing Meeting <span class="text-danger">*</span></label>
                                    <input type="text" id="simpleinput" class="form-control basic-datepicker" name="meeting_date" placeholder="Choose Closing Meeting date.." required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8 mt-3">
                                    <label for="simpleinput" class="form-label">Peserta <span class="text-danger">*</span></label>
                                    <select class="form-control selectize" name="peserta[]" required>
                                        <option value="">Select peserta...</option>
                                        @foreach ($data['pic'] as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8 mt-3">
                                    <label for="simpleinput" class="form-label">Team Auditor <span class="text-danger">*</span></label>
                                    <select class="form-control selectize" name="auditor[]" required>
                                        <option value="">Select auditor...</option>
                                        @foreach ($data['pic'] as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-3">
                                    <label for="simpleinput" class="form-label">Paraf <span class="text-danger">*</span></label>
                                    <input type="file" name="file" required data-plugins="dropify" accept="image/*" data-height="200" />
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

@endsection
