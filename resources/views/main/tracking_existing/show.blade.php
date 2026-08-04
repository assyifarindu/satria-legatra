@extends('layouts.master')

@section('title')
    Request Existing Document |
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
                                <li class="breadcrumb-item"><a href="{{ route('tracking-existing.index') }}">Request Existing
                                        Document</a></li>
                                <li class="breadcrumb-item active">Show Request</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Existing Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="nav nav-pills flex-column navtab-bg nav-pills-tab text-center"
                                        id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link @if ($data['tracking']->status == 1) active show @endif py-2"
                                            id="request-document-tab" data-bs-toggle="pill" href="#request-document"
                                            role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">
                                            Request Document
                                        </a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 1) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 2 || $data['tracking']->status == 3 || $data['tracking']->status == 4) active show @endif @if ($data['tracking']->status < 2) disabled @endif"
                                            id="legal-drafting-tab" data-bs-toggle="pill" href="#legal-drafting"
                                            role="tab" aria-controls="custom-v-pills-shipping" aria-selected="false">
                                            Process</a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 5) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 5) active show @endif @if ($data['tracking']->status < 5) disabled @endif"
                                            id="send-draft-tab" data-bs-toggle="pill" href="#send-draft" role="tab"
                                            aria-controls="custom-v-pills-payment" aria-selected="false">
                                            Complete</a>
                                    </div>

                                </div> <!-- end col-->
                                <div class="col-lg-10">
                                    <div class="tab-content p-3">
                                        <div class="tab-pane fade @if ($data['tracking']->status == 1) active show @endif"
                                            id="request-document" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div>
                                                <h4 class="header-title">Request Existing Document</h4>

                                                <p class="sub-header">Berikut adalah data permintaan dokumen yang telah
                                                    dibuat.</p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            <p class="mb-2 ps-3 pt-1"><span class="fw-semibold me-2">Tipe
                                                                    Dokumen, </span> <br>
                                                                <strong>{{ $data['tracking']->type }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span class="fw-semibold me-2">Request
                                                                    By, </span> <br>
                                                                <strong>{{ getUserName($data['tracking']->created_by)->name }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span class="fw-semibold me-2">Tujuan
                                                                    Permintaan, </span> <br>
                                                                <strong>{{ $data['tracking']->purpose }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1">
                                                                @if ($data['tracking']->is_cancel == true)
                                                                    <span class="badge bg-danger">Cancel</span>
                                                                @else
                                                                    @if ($data['tracking']->status == 1)
                                                                        <span class="badge bg-primary">Submitted</span>
                                                                    @elseif ($data['tracking']->status == 2)
                                                                        <span class="badge bg-primary">Need Upload
                                                                            Document</span>
                                                                    @elseif ($data['tracking']->status == 3)
                                                                        <span class="badge bg-info">Uploaded, Need
                                                                            Confirmation</span>
                                                                    @elseif ($data['tracking']->status == 4)
                                                                        <span class="badge bg-danger">Reject, Need
                                                                            Reupload</span>
                                                                    @elseif ($data['tracking']->status == 5)
                                                                        <span class="badge bg-success">Done</span>
                                                                    @endif
                                                                @endif
                                                            </p>
                                                        </div>

                                                        @foreach ($data['tracking']->RequestExistingDocument as $red)
                                                            <div class="border p-3 mb-3 mt-3 rounded">
                                                                <div class="form-check">
                                                                    <label class="form-check-label font-16 fw-bold"
                                                                        for="BillingOptRadio2">{{ $red->title }}</label>
                                                                </div>
                                                                <p class="mb-0 ps-3 pt-1"></p>
                                                            </div>
                                                        @endforeach

                                                        <div class="row mt-4">
                                                            <div class="col-sm-6">
                                                            </div> <!-- end col -->
                                                            @if ($data['tracking']->status == 1 && $data['tracking']->is_deleted == false && $data['tracking']->is_cancel == false)
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <button type="button"
                                                                            class="btn btn-primary d-inline waves-effect waves-light btn_revision"
                                                                            title="Revisi" tabindex="0"
                                                                            data-plugin="tippy" data-tippy-placement="top"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#feedback_modal"
                                                                            data-id="{{ $data['tracking']->id }}"><i
                                                                                class="mdi mdi-mail me-1"></i>Upload
                                                                            Document Request</button>
                                                                    </div>
                                                                </div> <!-- end col -->
                                                            @endif
                                                        </div> <!-- end row -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 2 || $data['tracking']->status == 3 || $data['tracking']->status == 4) active show @endif"
                                            id="legal-drafting" role="tabpanel"
                                            aria-labelledby="custom-v-pills-shipping-tab">
                                            <div>
                                                <h4 class="header-title">Uploaded Document</h4>

                                                <p class="sub-header">Berikut data document yang telah di upload oleh
                                                    legal.
                                                </p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            <p class="mb-2 ps-3 pt-1"><span class="fw-semibold me-2">Tipe
                                                                    Dokumen, </span> <br>
                                                                <strong>{{ $data['tracking']->type }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span
                                                                    class="fw-semibold me-2">Request
                                                                    By, </span> <br>
                                                                <strong>{{ getUserName($data['tracking']->created_by)->name }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span
                                                                    class="fw-semibold me-2">Tujuan
                                                                    Permintaan, </span>
                                                                <br> <strong>{{ $data['tracking']->purpose }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1">
                                                                @if ($data['tracking']->is_cancel == true)
                                                                    <span class="badge bg-danger">Cancel</span>
                                                                @else
                                                                    @if ($data['tracking']->status == 1)
                                                                        <span class="badge bg-primary">Submitted</span>
                                                                    @elseif ($data['tracking']->status == 2)
                                                                        <span class="badge bg-primary">Need Upload
                                                                            Document</span>
                                                                    @elseif ($data['tracking']->status == 3)
                                                                        <span class="badge bg-info">Uploaded, Need
                                                                            Confirmation</span>
                                                                    @elseif ($data['tracking']->status == 4)
                                                                        <span class="badge bg-danger">Reject, Need
                                                                            Reupload</span>
                                                                    @elseif ($data['tracking']->status == 5)
                                                                        <span class="badge bg-success">Done</span>
                                                                    @endif
                                                                @endif
                                                            </p>

                                                            @php
                                                                $feedback = getExistingDocument($data['tracking']->id);
                                                            @endphp
                                                            @foreach ($feedback as $fb)
                                                                <div class="border p-3 rounded">
                                                                    @if ($fb->file != '-')
                                                                        <div class="float-end">
                                                                            <a
                                                                                href="{{ route('request-existing.download', Hashids::encode($fb->id)) }}"><i
                                                                                    class="mdi mdi-file-download-outline text-muted font-20"
                                                                                    title="Download" tabindex="0"
                                                                                    data-plugin="tippy"
                                                                                    data-tippy-placement="top"></i></a>
                                                                        </div>
                                                                    @endif

                                                                    <div class="form-check">
                                                                        <label class="form-check-label font-16 fw-bold"
                                                                            for="BillingOptRadio2"><b>
                                                                                {{ $fb->description }}</b></label>
                                                                    </div>
                                                                    <p class="mb-0 ps-3 pt-1">
                                                                        <span class="badge bg-warning">Dokumen belum
                                                                            sesuai</span>
                                                                        {{ getUserName($fb->created_by)->name }} -
                                                                        {{ formatDate($fb->created_at) }}.
                                                                    </p>

                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <br>

                                                        <p>Apakah request dokumen ini termasuk dokumen PDP ?</p>
                                                        <div class="row mt-3">
                                                            <div class="col-lg-12">
                                                                <div class="form-checl">
                                                                    <input type="checkbox"
                                                                        style="transform: scale(1.5); margin-left: 10px"
                                                                        class="form-check-input" name="dokumen_pdp"
                                                                        id="dokumen-pdp">
                                                                    <label for="example-checkbox1"
                                                                        class="form-check-label"
                                                                        style="margin-left: 20px;">Dokumen PDP
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3">
                                                            <div class="col-lg-12">
                                                                <div class="form-checl">
                                                                    <input type="checkbox"
                                                                        style="transform: scale(1.5); margin-left: 10px"
                                                                        class="form-check-input" name="dokumen_not_pdp"
                                                                        id="not-pdp">
                                                                    <label for="unlimited-duration"
                                                                        class="form-check-label"
                                                                        style="margin-left: 20px;">Bukan Dokumen PDP
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" id="expired-select-container"
                                                            style="display: none;">
                                                            <div class="col-lg-8 mt-3">
                                                                <label for="example-select" class="form-label">Waktu
                                                                    Expired Dokumen</label>
                                                                <select class="form-select js-example-basic-single"
                                                                    name="expired" id="expired">
                                                                    <option value="">Pilih</option>
                                                                    @foreach ($data['expired'] as $i)
                                                                        <option value="{{ $i->id }}">
                                                                            {{ $i->note }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <br>

                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                            </div> <!-- end col -->
                                                            @if (
                                                                ($data['tracking']->status == 2 || $data['tracking']->status == 3) &&
                                                                    $data['tracking']->position == Auth::user()->id)
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <button type="button"
                                                                            class="btn btn-info d-inline waves-effect waves-light btn_revision"
                                                                            title="Revisi" tabindex="0"
                                                                            data-plugin="tippy" data-tippy-placement="top"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#feedback_modal"
                                                                            data-id="{{ $data['tracking']->id }}"><i
                                                                                class="mdi mdi-file me-1"></i>Re-upload
                                                                            Document Request</button>
                                                                    </div>
                                                                </div> <!-- end col -->
                                                            @endif
                                                        </div> <!-- end row -->

                                                        @foreach ($data['tracking']->RequestExistingDocument as $red)
                                                            <div class="border p-3 mb-3 rounded">
                                                                @if ($red->file != null)
                                                                    <div class="float-end">
                                                                        <a
                                                                            href="{{ route('request-existing.download', Hashids::encode($red->id)) }}"><i
                                                                                class="mdi mdi-file-download-outline text-muted font-20"
                                                                                title="Download" tabindex="0"
                                                                                data-plugin="tippy"
                                                                                data-tippy-placement="top"></i>
                                                                            Download Document</a>
                                                                    </div>
                                                                @endif
                                                                <div class="form-check">
                                                                    <label class="form-check-label font-16 fw-bold"
                                                                        for="BillingOptRadio2">{{ $red->title }}</label>
                                                                    <form action="{{ route('tracking-existing.store') }}"
                                                                        class="mt-1" method="POST"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="row">
                                                                            <input type="hidden"
                                                                                name="request_document_id"
                                                                                value="{{ $red->id }}"
                                                                                id="">
                                                                            <input type="hidden"
                                                                                name="request_existing_id"
                                                                                value="{{ $data['tracking']->id }}"
                                                                                id="">
                                                                            <input type="hidden" name="is_pdp"
                                                                                id="is_pdp_value">
                                                                            <input type="hidden" name="expired_date"
                                                                                id="expired_date_value">
                                                                            <div class="col-sm-10">
                                                                                <input type="file" name="file[]"
                                                                                    multiple required class="form-control">
                                                                            </div>
                                                                            <div class="col-sm-2">
                                                                                <button type="submit" id="submitBtn"
                                                                                    class="btn btn-primary">Upload</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <p class="mb-0 ps-3 pt-1"></p>

                                                            </div>
                                                        @endforeach

                                                        <div class="row mt-4">
                                                            <div class="col-sm-6">
                                                            </div> <!-- end col -->
                                                            @if ($data['tracking']->is_cancel == false)
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <button type="button"
                                                                            class="btn btn-primary d-inline waves-effect waves-light btn_send"
                                                                            title="send to requester" tabindex="0"
                                                                            data-plugin="tippy" data-tippy-placement="top"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#send_modal"
                                                                            data-id="{{ $data['tracking']->id }}"><i
                                                                                class="mdi mdi-mail me-1"></i>Send to
                                                                            requester</button>
                                                                    </div>
                                                                </div> <!-- end col -->
                                                            @endif
                                                        </div> <!-- end row -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 5) active show @endif"
                                            id="send-draft" role="tabpanel" aria-labelledby="custom-v-pills-payment-tab">
                                            <div>
                                                <h4 class="header-title">Complete</h4>

                                                <p class="sub-header">Document request cycle has finished.</p>


                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            <p class="mb-2 ps-3 pt-1"><span class="fw-semibold me-2">Tipe
                                                                    Dokumen, </span> <br>
                                                                <strong>{{ $data['tracking']->type }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span
                                                                    class="fw-semibold me-2">Request By, </span> <br>
                                                                <strong>{{ getUserName($data['tracking']->created_by)->name }}</strong>
                                                            </p>
                                                            <p class="mb-2 ps-3 pt-1"><span
                                                                    class="fw-semibold me-2">Tujuan Permintaan, </span>
                                                                <br> <strong>{{ $data['tracking']->purpose }}</strong>
                                                            </p>
                                                            @if ($data['tracking']->status == 4)
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Document, </span> <br>
                                                                    <strong></strong>
                                                                </p>
                                                            @endif
                                                            <p class="mb-2 ps-3 pt-1">
                                                                @if ($data['tracking']->is_cancel == true)
                                                                    <span class="badge bg-danger">Cancel</span>
                                                                @else
                                                                    @if ($data['tracking']->status == 1)
                                                                        <span class="badge bg-primary">Submitted</span>
                                                                    @elseif ($data['tracking']->status == 2)
                                                                        <span class="badge bg-primary">Need Upload
                                                                            Document</span>
                                                                    @elseif ($data['tracking']->status == 3)
                                                                        <span class="badge bg-info">Uploaded, Need
                                                                            Confirmation</span>
                                                                    @elseif ($data['tracking']->status == 4)
                                                                        <span class="badge bg-danger">Reject, Need
                                                                            Reupload</span>
                                                                    @elseif ($data['tracking']->status == 5)
                                                                        <span class="badge bg-success">Done</span>
                                                                    @endif
                                                                @endif
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                            </div> <!-- end row-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="feedback_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Confirmation Dialog</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tracking-existing.step-process') }}" method="POST" id="fileUploadForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_request_document">
                        <h5>Do you want to continue this request ?</h5>
                        <div class="form-group mt-2">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes, Continue</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="send_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Confirmation Dialog</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tracking-existing.step-process-requester') }}" method="POST"
                    id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_request_document_send">
                        <h5>Do you want to send to requester ?</h5>
                        <div class="form-group mt-2">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Yes, Continue</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="filing_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Tambahkan Ringkasan</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master-company.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="simpleinput" class="form-label">Judul Ringkasan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required
                                    placeholder="PT United Tractor Pandu Engineering">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="simpleinput" class="form-label">Ringkasan <span
                                        class="text-danger">*</span></label>
                                <div id="summernote-basic"></div>
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
        function confirmFunction() {
            event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, Continue!",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1,
            }).then(function(e) {
                e.value ?
                    form.submit() :
                    e.dismiss === Swal.DismissReason.cancel &&
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your data is safe :)",
                        icon: "error",
                        confirmButtonColor: "#4a4fea",
                    });
            });
        }

        $(document).ready(function() {
            $('.btn_revision').click(function() {
                document.getElementById("id_request_document").value = $(this).attr('data-id');
            });

            $('.btn_send').click(function() {
                document.getElementById("id_request_document_send").value = $(this).attr('data-id');
            });

        });

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
                        window.location.href =
                            "{{ route('tracking-existing.show', Hashids::encode($data['tracking']->id)) }}";
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#expired').select2();
            submitBtn.disabled = true;
            $('#expired-select-container').hide();

            $('#dokumen-pdp').change(function() {
                if ($(this).is(':checked')) {
                    $('#expired-select-container').show();
                    $('#expired').select2(); // Inisialisasi Select2 setelah tampil
                } else {
                    $('#expired-select-container').hide();
                    $('#expired').select2('destroy'); // Hancurkan jika disembunyikan
                }
            });

            $('#not-pdp').change(function() {
                $('#expired-select-container').hide();
                $('#expired').select2('destroy');
            });




            // Event listener untuk mengambil nilai dari select2 dan memasukkan ke hidden input
            $('#expired').on('change', function() {
                var selectedValue = $(this)
                    .val(); // Mendapatkan nilai terpilih dari select2
                $('#expired_date_value').val(
                    selectedValue); // Memasukkan nilai terpilih ke input tersembunyi
                console.log("Expired value selected: " + selectedValue);
            });


            $('#dokumen-pdp').on('change', function() {
                if (this.checked) {
                    $('#not-pdp').prop('checked', false); // Uncheck checkbox lainnya
                    submitBtn.disabled = false;
                }
            });

            $('#not-pdp').on('change', function() {
                if (this.checked) {
                    $('#dokumen-pdp').prop('checked', false); // Uncheck checkbox lainnya
                    submitBtn.disabled = false;
                }
            });

            $('#submitBtn').on('click', function() {

                // Set PDP value based on checkbox
                var isPdpChecked = $('#dokumen-pdp').is(':checked');
                var notPdpChecked = $('#not-pdp').is(':checked');

                console.log('pdp checked:', isPdpChecked);

                if (isPdpChecked) {
                    $('#is_pdp_value').val(1);
                } else if (notPdpChecked) {
                    $('#is_pdp_value').val(0);
                } else {
                    $('#is_pdp_value').val('');
                    alert('Please select whether the document is PDP or not.');
                    return;
                }

                // Set expired date value
                var selectedValue = $('#expired').val();
                $('#expired_date_value').val(selectedValue);
            });




        });
    </script>
@endsection
