@extends('layouts.master')

@section('title')
    Request Feedback/Drafting Document |
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
                                <li class="breadcrumb-item"><a href="{{ route('tracking.index') }}">Request Feedback/Drafting
                                        Document</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Feedback/Drafting Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Add Request Feedback/Drafting Document</h4>

                            <form action="{{ route('request-document.store') }}" method="post" id="fileUploadForm"
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
                                        {{-- <select class="form-select js-example-basic-single" name="type" id="type">
                                            <option></option>
                                            <option value="Contract">Contract/Letter</option>
                                            <option value="License">License</option>
                                            <option value="HAKI">HAKI</option>
                                        </select> --}}
                                        <select class="form-select js-example-basic-single" name="type" id="type">
                                            <option></option>
                                            @if ($data['company_name'] === 'PT Patria Maritim Perkasa')
                                                <option value="Contract">Contract/Letter</option>
                                            @else
                                                <option value="Contract">Contract/Letter</option>
                                                <option value="License">License</option>
                                                <option value="HAKI">HAKI</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="contractSpace">
                                    <div class="row advancedWrapper">
                                        <div class="col-lg-10 mt-3">
                                            <label for="simpleinput" class="form-label">Para Pihak <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="para_pihak[]"
                                                placeholder="Para pihak" required>
                                        </div>
                                        <div class="col-lg-1 mt-3">
                                            <label for="simpleinput" class="form-label">Action <span
                                                    class="text-danger">*</span></label>
                                            <button class="btn btn-primary form-control add"><i
                                                    class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-10 mt-3">
                                            <input type="text" class="form-control" name="para_pihak[]"
                                                placeholder="Para pihak" value="PT UNITED TRACTORS PANDU ENGINEERING">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-10 mt-3">
                                            <label for="simpleinput" class="form-label">Ruang Lingkup (TOP, Delivery Time,
                                                Penalty, dan lain-lain contoh: NDA)<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="scope"
                                                placeholder="Ruang lingkup (TOP, Delivery Time, Penalty)" required>
                                        </div>
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
                                    <div class="col-lg-8 mt-3">
                                        <label for="simpleinput" class="form-label">Department In Charge <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control selectize" multiple name="doc_scope[]" required>
                                            <option value="">Select scope...</option>
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
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Untuk attachment dimohon melampirkan dokumen pendukung.
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
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

                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <div class="form-checl">
                                            <input type="checkbox" style="transform: scale(1.5); margin-left: 10px"
                                                class="form-check-input" name="unlimited_duration"
                                                id="unlimited_duration">
                                            <label for="unlimited_duration" class="form-check-label"
                                                style="margin-left: 20px;">Unlimited Duration
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-10 mt-3">
                                        <label for="simpleinput" class="form-label">Notes </label>
                                        <textarea name="note" id="" cols="10" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="row uploadWrapper">
                                    <div class="col-lg-11">
                                        <label for="simpleinput" class="form-label">Dokumen Pendukung</label>
                                        <input type="file" class="form-control" name="attachment[]"
                                            accept=".doc,.docx,.pdf,.xlsx" placeholder="Attachment">
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

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div><!-- end col -->
            </div>
        </div>
    </div>

    <div id="template-document" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Template Document</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" style="max-height: 400px; max: 400px;">
                        <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach (getTemplateByUser() as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->company_name }}</td>
                                        <td>
                                            <a href="{{ route('download.template', $item->id) }}"><i
                                                    class="fas fa-donwnload"></i> Download</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/form-fileuploads.init.js') }}"></script>

    <script>
        $(function() {
            $(document).ready(function() {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function(xhr) {
                        var percentage = '0';
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('#set-loader').addClass('loader_effect');
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    error: function(xhr, status, error) {
                        console.error("Terjadi error: ", error);
                        // alert("Terjadi error: " + error);
                    },

                    complete: function(xhr) {
                        window.location.href = "{{ route('request-document.index') }}";
                    }


                });
            });
        });

        $(document).ready(function() {
            let main_wrapper = $('.contractSpace');
            let download_wrapper = $('.downloadWrapper');
            $(main_wrapper).html("");
            $(download_wrapper).html("");
            const companyName = @json($data['company_name']);

            $('#type').on('change', function() {
                if ($('#type').find(":selected").val() == 'Contract') {
                    $(main_wrapper).append('<div class="row">' +
                        '<div class="col-lg-10 mt-3">' +
                        '<label for="simpleinput" class="form-label">Para Pihak <span class="text-danger">*</span></label>' +
                        '<input type="text" class="form-control" name="para_pihak[]" placeholder="Para pihak" value="' +
                        companyName + '" required>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row advancedWrapper">' +
                        '<div class="col-lg-10 mt-1">' +
                        '<input type="text" class="form-control" name="para_pihak[]" placeholder="Para pihak" required>' +
                        '</div>' +
                        '<div class="col-lg-1 mt-1">' +
                        '<button class="btn btn-primary form-control" onclick="addWrapper()" type="button"><i class="fas fa-plus"></i></button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row mt-2">' +
                        '<div class="col-lg-12">' +
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                        'Jika anda membutuhkan template untuk diupload pada request document, silakan download pada halaman dashboard atau akses <a href="#" data-bs-toggle="modal" data-bs-target="#template-document">link ini</a>.' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-lg-10 mt-3">' +
                        '<label for="simpleinput" class="form-label">Ruang Lingkup (TOP, Delivery Time, Penalty, dan lain-lain contoh: NDA) <span class="text-danger">*</span></label>' +
                        '<input type="text" class="form-control" name="scope" placeholder="Ruang lingkup (TOP, Delivery Time, Penalty)" required>' +
                        '</div>' +
                        '</div>');
                } else {
                    $(main_wrapper).html("");
                    $(download_wrapper).html("");
                }
            });


            $(document).on("click", ".remove", function() {
                $(this).parents("div .numberOfDocument").remove();
            });

            // Document
            let upload_wrapper = $('.uploadWrapper');
            $(document).on("click", "#add_upload", function(e) {
                e.preventDefault();
                $(upload_wrapper).append('<div class="row numberUpload">' +
                    '<div class="col-lg-11 mt-1">' +
                    '<input type="file" class="form-control" required name="attachment[]" accept=".doc,.docx,.pdf,.xlsx" placeholder="Attachment" required>' +
                    '</div>' +
                    '<div class="col-lg-1 mt-1">' +
                    '<button class="btn btn-danger form-control remove_upload"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                    '</div>');
            });

            $(document).on("click", ".remove_upload", function() {
                $(this).parents("div .numberUpload").remove();
            });
        });

        function addWrapper() {
            var wrapper = $(".advancedWrapper");
            $(wrapper).append('<div class="row numberOfDocument"><div class="col-lg-10 mt-3">' +
                '<input type="text" class="form-control" name="para_pihak[]" id="document_1" placeholder="Para pihak" required>' +
                '</div>' +
                '<div class="col-lg-1 mt-3">' +
                '<button class="btn btn-danger form-control remove"><i class="fas fa-trash"></i></button>' +
                '</div></div>');
        }
    </script>
@endsection
