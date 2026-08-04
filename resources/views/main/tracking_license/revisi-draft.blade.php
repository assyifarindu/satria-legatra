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

                                {{-- <div class="row">
                                <div class="col-lg-6 mt-3">
                                    <label for="example-select" class="form-label">PIC</label>
                                    <select class="form-select js-example-basic-single" name="pic">
                                        <option></option>
                                        @foreach ($data['pic'] as $i)
                                            <option value="{{ $i->id }}" @if ($i->id == $data['document']->pic) selected @endif>{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">PIC</label>
                                        <select class="form-control selectize" multiple name="pic[]">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}"
                                                    @if (checkPicDocument($data['document']->id, $i->id)) selected @endif>{{ $i->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" name="extend_automatically"
                                                @if ($data['document']->is_extend_automatically) checked @endif id="example-checkbox1">
                                            <label for="example-checkbox1" class="form-check-label"
                                                style="margin-left: 20px;">Extend Document
                                                Secara
                                                Otomatis
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" @if ($data['document']->is_unlimited_duration) checked @endif
                                                name="unlimited_duration" id="unlimited-duration">
                                            <label for="unlimited-duration" class="form-check-label"
                                                style="margin-left: 20px;">Unlimited Duration
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row @if ($data['document']->is_unlimited_duration == 1) d-none @endif" id="durationWrapper">

                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Duration (Effective Date - End
                                            Contract Date)
                                            <span class="text-danger">*</span></label>
                                        @if ($data['document']->contract_date == '0000-00-00')
                                            <input type="text" name="duration" class="form-control range-datepicker"
                                                id="duration" value="{{ date('Y-m-d') }} to {{ date('Y-m-d') }}"
                                                placeholder="2018-10-03 to 2018-10-10">
                                        @else
                                            <input type="text" name="duration" class="form-control range-datepicker"
                                                id="duration"
                                                value="{{ $data['document']->contract_date }} to {{ $data['document']->end_contract_date }}"
                                                placeholder="2018-10-03 to 2018-10-10">
                                        @endif
                                    </div>
                                </div>

                                <div class="row @if ($data['document']->is_unlimited_duration == 1) d-none @endif" id="alertWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Alert
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="alert" placeholder="hari"
                                                value="{{ $data['document']->alert_days }}" id="" required>
                                            <button class="btn btn-primary waves-effect waves-light" type="button">hari
                                                sebelumnya</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row @if ($data['document']->is_unlimited_duration == 0) d-none @endif"
                                    id="contractDateWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Contract Date <span
                                                class="text-danger">*</span></label>
                                        @if ($data['document']->contract_date == '0000-00-00')
                                            <input type="text" class="form-control basic-datepicker"
                                                id="contract_date" name="contract_date" placeholder="Contract Date"
                                                required>
                                        @else
                                            <input type="text" class="form-control basic-datepicker"
                                                value="{{ $data['document']->contract_date }}" id="contract_date"
                                                name="contract_date" placeholder="Contract Date" required>
                                        @endif

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
                                                    @if (checkDocumentScope($data['document']->request_document_id, $data['department'][$i]['id'])) selected @endif>
                                                    {{ $data['department'][$i]['id'] }} -
                                                    {{ $data['department'][$i]['nama'] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Note <span
                                                class="text-danger">*</span></label>
                                        <textarea name="note" id="" class="form-control" cols="30" rows="5" placeholder="Note">{{ $data['document']->note }}</textarea>
                                    </div>
                                </div>

                                <div class="row uploadWrapper mt-3">
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
                        var contract_date = $('#contract_date').val();
                        const duration = $('#duration').val();

                        if (duration != '') {
                            let durationSplit = duration.split(" to ");
                            if (durationSplit.length < 2) {
                                Swal.fire({
                                    title: "Input duration tidak valid",
                                    text: "Periksa kembali",
                                    icon: "warning",
                                    confirmButtonColor: "#4a4fea",
                                });
                                xhr.abort()
                            }
                        }

                        if (contract_date == '') {
                            Swal.fire({
                                title: "Terdapat form yang belum diisi",
                                text: "Periksa kembali",
                                icon: "error",
                                confirmButtonColor: "#4a4fea",
                            });
                            xhr.abort()
                        }
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('#set-loader').addClass('loader_effect');
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

        $(document).ready(function() {
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

            const unlimited = $('#unlimited-duration');
            unlimited.on('change', function() {
                if (unlimited.is(':checked')) {
                    $('#durationWrapper').addClass('d-none');
                    $('#contractDateWrapper').removeClass('d-none');
                    $('#alertWrapper').addClass('d-none');
                    $('#alert_duration').removeAttr('required');
                    $('#duration').removeAttr('required');
                } else {
                    $('#durationWrapper').removeClass('d-none');
                    $('#contractDateWrapper').addClass('d-none');
                    $('#alertWrapper').removeClass('d-none');
                    $('#alert_duration').attr('required', 'required');
                    $('#duration').attr('required', 'required');
                }
            });
        });
    </script>
@endsection
