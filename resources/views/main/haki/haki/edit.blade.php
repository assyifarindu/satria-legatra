@extends('layouts.master')

@section('title')
    Edit Filing |
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
                                <li class="breadcrumb-item"><a href="{{ route('haki.index') }}">HAKI</a></li>
                                <li class="breadcrumb-item active">Edit Filing</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Filing</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Filing <i class="fas fa-arrow-right"></i>
                                [{{ $data['document']->contract_number }}] {{ $data['document']->description }}</h4>

                            <form
                                action="{{ route('haki.update-document', Hashids::encode($data['document']->base_document_id)) }}"
                                method="post" id="fileUploadForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Document <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_title"
                                            placeholder="Contract document" required
                                            value="{{ $data['document']->description }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Nomor Contract <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contract_number"
                                            placeholder="Contract document" value="{{ $data['document']->contract_number }}"
                                            required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
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
                                        <label for="example-select" class="form-label">Company</label>
                                        {{-- <select class="form-select js-example-basic-single" name="company">
                                            <option></option>
                                            @foreach ($data['company'] as $k)
                                                <option value="{{ $k->id }}"
                                                    @if ($k->name == $data['document']->company) selected @endif>{{ $k->name }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                        <input type="hidden" name="company" value="{{ $data['company']->id }}">
                                        <input type="text" class="form-control" value="{{ $data['company']->name }}"
                                            required readonly>
                                    </div>
                                </div>

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

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Type</label>
                                        <select class="form-select js-example-basic-single" name="type">
                                            <option></option>
                                            @foreach ($data['type'] as $t)
                                                <option value="{{ $t->id }}"
                                                    @if ($t->id == $data['document']->haki_type_id) selected @endif>{{ $t->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="example-select" class="form-label">Duty</label>
                                        <select class="form-select js-example-basic-single" name="duty">
                                            <option></option>
                                            @foreach ($data['duty'] as $d)
                                                <option value="{{ $d->id }}"
                                                    @if ($d->id == $data['document']->duty_id) selected @endif>{{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Launch By <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control js-example-basic-single" name="launch_by" required>
                                            <option value="">Select launch by</option>
                                            @for ($i = 0; $i < count($data['department']); $i++)
                                                <option value="{{ $data['department'][$i]['nama'] }}"
                                                    @if ($data['department'][$i]['nama'] == $data['document']->launch_by) selected @endif>
                                                    {{ $data['department'][$i]['nama'] }}</option>
                                            @endfor
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
                                            <input type="checkbox" @if ($data['document']->is_unlimited_duration) checked @endif
                                                style="transform: scale(1.5); margin-left: 10px" class="form-check-input"
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
                                        @if ($data['document']->contract_date == null)
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
                                            @if ($data['document']->is_unlimited_duration == 1)
                                                <input type="number" class="form-control" name="alert"
                                                    placeholder="hari" value="{{ $data['document']->alert_days }}"
                                                    id="alert_duration">
                                            @else
                                                <input type="number" class="form-control" name="alert"
                                                    placeholder="hari" value="{{ $data['document']->alert_days }}"
                                                    id="alert_duration" required>
                                            @endif
                                            <button class="btn btn-primary waves-effect waves-light" type="button">hari
                                                sebelumnya</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row @if ($data['document']->is_unlimited_duration == 0) d-none @endif"
                                    id="contractDateWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Effective Date <span
                                                class="text-danger">*</span></label>
                                        @if ($data['document']->contract_date == null)
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
                                                    @if (checkDocumentScopeByDocument($data['document']->id, $data['department'][$i]['id'])) selected @endif>
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

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title_ringkasan"
                                            placeholder="Judul Ringkasan"
                                            value="{{ $data['document']->title_ringkasan }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="ringkasan" class="form-control" cols="30" rows="5" required>{{ $data['document']->ringkasan }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label for="simpleinput" class="form-label">Dokumen Final <span
                                            class="text-danger"></span></label>
                                    <a href="#"
                                        class="btn btn-success btn-xs d-inline waves-effect waves-light btn_upload"
                                        title="Add Document" tabindex="0" data-plugin="tippy"
                                        data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#add_modal"
                                        data-id="{{ $data['document']->id }}">Add Document <i
                                            class="fas fa-plus"></i></a>
                                </div>
                                @foreach ($data['document']->DocumentFinalAttachment as $fb)
                                    <div class="border p-3 mb-3 rounded">
                                        @if ($fb->file != '')
                                            <div class="float-end">
                                                <a href="{{ route('haki.download', Hashids::encode($fb->id)) }}"><i
                                                        class="mdi mdi-file-download-outline text-muted font-20"
                                                        title="Download" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"></i></a>
                                                <a
                                                    href="{{ route('contract-delete-edit-file', Hashids::encode($fb->id)) }}"><i
                                                        class="mdi mdi-trash-can-outline text-muted font-20"
                                                        title="Hapus" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"></i></a>
                                            </div>
                                        @endif
                                        <div class="form-check">
                                            <label class="form-check-label font-16 fw-bold" for="BillingOptRadio2"><a
                                                    href="{{ route('request-document.download-upload', $fb->id) }}"><i
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
                <form action="{{ route('contract-update-edit-file') }}" method="POST" enctype="multipart/form-data"
                    id="fileUploadFormEdit">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_req">
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <label for="simpleinput" class="form-label">File Template <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="file" required data-plugins="dropify" accept=".pdf"
                                    data-height="150" />
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
                        if ($('#unlimited-duration').is(':checked')) {
                            if (contract_date === '') {
                                Swal.fire({
                                    title: "Effective date belum diisi",
                                    text: "Periksa kembali",
                                    icon: "warning",
                                    confirmButtonColor: "#4a4fea",
                                });
                                xhr.abort()
                            }
                        } else {
                            let durationSplit = duration.split(" to ");
                            if (durationSplit.length < 2) {
                                Swal.fire({
                                    title: "Input duration tidak valid",
                                    text: "Periksa kembali",
                                    icon: "warning",
                                    confirmButtonColor: "#4a4fea",
                                });
                                xhr.abort();
                            }
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
                        window.location.href = "{{ route('haki.index') }}";
                    }
                });
            });
        });

        $(document).ready(function() {
            var wrapper = $(".wrapperSurat");
            var wrapperTujuan = $(".wrapperTujuan");
            $(document).on("click", "#surat", function(e) {
                $(wrapper).html("");
                $(wrapper).append('<div class="col-lg-6 mt-3">' +
                    '<label for="example-select" class="form-label">Jenis Surat</label>' +
                    '<select class="form-select js-example-basic-single jenis_surat" name="jenis_surat" id="jenis_surat" onchange="changeJenisSurat()">' +
                    '<option value="Surat Keluar">Surat Keluar</option>' +
                    '<option value="Surat Kuasa">Surat Kuasa</option>' +
                    '</select>' +
                    '</div>');
                $(wrapperTujuan).append('<div class="col-lg-6 mt-3">' +
                    '<label for="example-select" class="form-label">Tujuan Surat</label>' +
                    '<select class="form-select js-example-basic-single" name="tujuan_surat">' +
                    '<option value="Internal">Internal</option>' +
                    '<option value="Eksternal">Eksternal</option>' +
                    '</select>' +
                    '</div>');
            });

            $(document).on("click", "#agreement", function(e) {
                $(wrapper).html("");
                $(wrapperTujuan).html("");
            });

            $('.jenis_surat').on('change', function() {
                console.log('disini');
                if ($('.jenis_surat').find(":selected").val() == 'Surat Keluar') {
                    console.log($('.jenis_surat').find(":selected").val());
                    $(wrapperTujuan).html("");
                    $(wrapperTujuan).append('<div class="col-lg-6 mt-3">' +
                        '<label for="example-select" class="form-label">Tujuan Surat</label>' +
                        '<select class="form-select js-example-basic-single" name="tujuan_surat">' +
                        '<option value="Internal">Internal</option>' +
                        '<option value="Eksternal">Eksternal</option>' +
                        '</select>' +
                        '</div>');
                } else {
                    $(wrapperTujuan).html("");
                }
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

        $(document).ready(function() {
            $('.btn_upload').click(function() {
                document.getElementById("id_req").value = $(this).attr('data-id');
            });
        });

        function changeJenisSurat() {
            console.log('disitu');
            var wrapperTujuan = $(".wrapperTujuan");
            if ($('.jenis_surat').find(":selected").val() == 'Surat Keluar') {
                console.log($('.jenis_surat').find(":selected").val());
                $(wrapperTujuan).html("");
                $(wrapperTujuan).append('<div class="col-lg-6 mt-3">' +
                    '<label for="example-select" class="form-label">Tujuan Surat</label>' +
                    '<select class="form-select js-example-basic-single" name="tujuan_surat">' +
                    '<option value="Internal">Internal</option>' +
                    '<option value="Eksternal">Eksternal</option>' +
                    '</select>' +
                    '</div>');
            } else {
                $(wrapperTujuan).html("");
            }
        }
    </script>
@endsection
