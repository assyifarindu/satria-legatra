@extends('layouts.master')

@section('title')
    Add Filing |
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
                                <li class="breadcrumb-item"><a href="{{ route('contract.index') }}">Contract</a></li>
                                <li class="breadcrumb-item active">Add Filing</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Add Filing</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Add Filing Document</h4>

                            <form action="{{ route('contract.store') }}" method="post" id="fileUploadForm"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Document <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_title"
                                            placeholder="Contract document" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Nomor Contract <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contract_number"
                                            placeholder="Contract document" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Priority</label>
                                        <select class="form-select js-example-basic-single" name="priority">
                                            <option value="1">High</option>
                                            <option value="2">Medium</option>
                                            <option value="3">Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Yang Bertanda Tangan</label>
                                        <select class="form-select js-example-basic-single" name="title">
                                            @foreach ($data['title'] as $i)
                                                <option value="{{ $i->id }}">{{ $i->code }} -
                                                    {{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="example-select" class="form-label">Jenis Dokumen</label>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="category" checked
                                                        id="surat" value="Surat">
                                                    <label class="form-check-label" for="customCheck1">Surat</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="category"
                                                        id="agreement" value="Agreement">
                                                    <label class="form-check-label" for="customCheck2">Agreement</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row wrapperSurat">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Jenis Surat</label>
                                        <select class="form-select js-example-basic-single jenis_surat" name="jenis_surat"
                                            id="jenis_surat">
                                            <option value="Surat Keluar">Surat Keluar</option>
                                            <option value="Surat Kuasa">Surat Kuasa</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row wrapperTujuan">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Tujuan Surat</label>
                                        <select class="form-select js-example-basic-single" name="tujuan_surat">
                                            <option value="Internal">Internal</option>
                                            <option value="Eksternal">Eksternal</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Company</label>
                                        <select class="form-select js-example-basic-single" name="company">
                                            <option></option>
                                            @foreach ($data['company'] as $k)
                                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">PIC</label>
                                        <select class="form-control selectize" multiple name="pic[]">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}">{{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" name="extend_automatically"
                                                id="example-checkbox1">
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
                                                class="form-check-input" name="unlimited_duration"
                                                id="unlimited-duration">
                                            <label for="unlimited-duration" class="form-check-label"
                                                style="margin-left: 20px;">Unlimited Duration
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="durationWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Duration (Effective Date - End
                                            Contract Date)
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="duration" id="duration"
                                            class="form-control range-datepicker" required
                                            placeholder="2018-10-03 to 2018-10-10">
                                    </div>
                                </div>

                                <div class="row" id="alertWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Alert
                                            <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="alert" required
                                                placeholder="hari" id="alert_duration">
                                            <button class="btn btn-primary waves-effect waves-light" type="button">hari
                                                sebelumnya</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row d-none" id="contractDateWrapper">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Effective Date <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control basic-datepicker" id="contract_date"
                                            name="contract_date" placeholder="Contract Date" required>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="example-select" class="form-label">Duration (Duration - Alert) <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select js-example-basic-single" name="duration" required>
                                            <option></option>
                                            @foreach ($data['alert'] as $j)
                                                <option value="{{ $j->id }}">Durasi {{ $j->duration }} hari,
                                                    Alert {{ $j->start_alert }} hari sebelumnya</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Contract Date <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control basic-datepicker" id="contract_date"
                                            name="contract_date" placeholder="Contract Date" required>
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Department In Charge <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control selectize" multiple name="doc_scope[]" required>
                                            <option value="">Select scope</option>
                                            @for ($i = 0; $i < count($data['department']); $i++)
                                                <option
                                                    value="{{ $data['department'][$i]['id'] }}-{{ $data['department'][$i]['nama'] }}">
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
                                        <textarea name="note" id="" class="form-control" cols="30" rows="5" placeholder="Note"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title_ringkasan"
                                            placeholder="Judul Ringkasan" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Ringkasan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="ringkasan" class="form-control" cols="30" rows="5" required></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2 uploadWrapper">
                                    <div class="col-lg-11">
                                        <label for="simpleinput" class="form-label">Document Final <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" required name="file[]"
                                            accept=".pdf" placeholder="Attachment">
                                    </div>
                                    <div class="col-lg-1">
                                        <label for="simpleinput" class="form-label">Action <span
                                                class="text-danger">*</span></label>
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
                    beforeSend: function(xhr) {
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
                        window.location.href = "{{ route('contract.index') }}";
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
                    '<input type="file" class="form-control" required name="file[]" accept=".pdf" placeholder="Attachment">' +
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
