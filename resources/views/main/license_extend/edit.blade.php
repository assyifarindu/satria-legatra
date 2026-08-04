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
                                <li class="breadcrumb-item"><a href="{{ route('closing-meeting.index') }}">Closing Meeting</a></li>
                                <li class="breadcrumb-item active">Edit Closing Meeting</li>
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
                            <h4 class="header-title">Edit Closing Meeting</h4>

                            <form action="{{ route('closing-meeting.update', $data['closing_meeting']->id) }}" method="post">
                                <input name="_method" type="hidden" value="PUT">

                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">RKIA</label>
                                        <input type="text" name="rkia" class="form-control" readonly value="{{ $data['closing_meeting']->Rkia->rkia }}" id="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <label for="simpleinput" class="form-label">Tanggal Closing Meeting <span class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control basic-datepicker" name="meeting_date" value="{{ $data['closing_meeting']->meeting_date }}" placeholder="Choose Closing Meeting date.." required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Peserta <span class="text-danger">*</span></label>
                                        <select class="form-control js-example-basic-multiple" multiple name="peserta[]">
                                            @foreach ($data['pic'] as $item)
                                                <option value="{{ $item->id }}" @if (checkParticipant($item->id, 'peserta')) selected @endif >{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Team Auditor <span class="text-danger">*</span></label>
                                        <select class="form-control js-example-basic-multiple" multiple name="auditor[]">
                                            @foreach ($data['pic'] as $item)
                                                <option value="{{ $item->id }}" @if (checkParticipant($item->id, 'auditor')) selected @endif>{{ $item->name }}</option>
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

                                <div class="text-end">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                    <a href="{{ route('closing-meeting.index') }}"
                                        class="btn btn-secondary waves-effect">Cancel</a>
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
