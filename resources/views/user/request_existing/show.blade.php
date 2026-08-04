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
                                <li class="breadcrumb-item"><a href="{{ route('request-existing.index') }}">Request Existing
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
                                        <a class="nav-link @if ($data['tracking']->status == 1 || $data['tracking']->status == 2) active show @endif py-2"
                                            id="request-document-tab" data-bs-toggle="pill" href="#request-document"
                                            role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">
                                            Request Document
                                        </a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 3) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 3 || $data['tracking']->status == 4) active show @endif @if ($data['tracking']->status < 3) disabled @endif"
                                            id="legal-drafting-tab" data-bs-toggle="pill" href="#legal-drafting"
                                            role="tab" aria-controls="custom-v-pills-shipping" aria-selected="false">
                                            Process</a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 4) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 5) active show @endif @if ($data['tracking']->status < 5) disabled @endif"
                                            id="send-draft-tab" data-bs-toggle="pill" href="#send-draft" role="tab"
                                            aria-controls="custom-v-pills-payment" aria-selected="false">
                                            Complete</a>
                                    </div>

                                </div> <!-- end col-->
                                <div class="col-lg-10">
                                    <div class="tab-content p-3">
                                        <div class="tab-pane fade @if ($data['tracking']->status == 2 || $data['tracking']->status == 1) active show @endif"
                                            id="request-document" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div>
                                                <h4 class="header-title">Request Existing Document</h4>

                                                <p class="sub-header">Berikut adalah data permintaan dokumen yang telah
                                                    dibuat.</p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            @if ($data['tracking']->title != null)
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Judul
                                                                        Dokumen, </span> <br>
                                                                    <strong>{{ $data['tracking']->title }}</strong>
                                                                </p>
                                                            @endif
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

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 3 || $data['tracking']->status == 4) active show @endif"
                                            id="legal-drafting" role="tabpanel"
                                            aria-labelledby="custom-v-pills-shipping-tab">
                                            <div>
                                                <h4 class="header-title">Uploaded Document</h4>

                                                <p class="sub-header">Berikut data document yang telah di upload oleh legal.
                                                </p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            @if ($data['tracking']->title != null)
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Judul
                                                                        Dokumen, </span> <br>
                                                                    <strong>{{ $data['tracking']->title }}</strong>
                                                                </p>
                                                            @endif
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
                                                                @if ($data['tracking']->is_cancel)
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
                                                                    @elseif ($data['tracking']->status == 3)
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
                                                                <div class="border p-3 mb-3 rounded">
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
                                                                            for="BillingOptRadio2"><b>{{ $fb->description }}</b></label>
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


                                                        @foreach ($data['tracking']->RequestExistingDocument as $red)
                                                            <div class="border p-3 mb-3 rounded">

                                                                <div class="form-check">
                                                                    <label class="form-check-label font-16 fw-bold"
                                                                        for="BillingOptRadio2">{{ $red->title }}</label>
                                                                    @if ($red->file != null)
                                                                        <div class="row">

                                                                            <a
                                                                                href="{{ route('request-existing.download', Hashids::encode($red->id)) }}"><i
                                                                                    class="mdi mdi-file-download-outline text-muted font-20"
                                                                                    title="Download" tabindex="0"
                                                                                    data-plugin="tippy"
                                                                                    data-tippy-placement="top"></i>
                                                                                Download Document</a>
                                                                        </div>
                                                                    @else
                                                                        <h5>No Document</h5>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        <div class="row mt-4">
                                                            <div class="col-sm-6">
                                                            </div> <!-- end col -->
                                                            @if (($data['tracking']->status == 2 || $data['tracking']->status == 3) && $data['tracking']->is_cancel == false)
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <button type="button"
                                                                            class="btn btn-danger d-inline waves-effect waves-light btn_revision"
                                                                            title="Revisi" tabindex="0"
                                                                            data-plugin="tippy" data-tippy-placement="top"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#feedback_modal"
                                                                            data-id="{{ $data['tracking']->id }}"><i
                                                                                class="fas fa-times"></i> Dokumen Belum
                                                                            Sesuai</button>

                                                                        <form
                                                                            action="{{ route('request-existing.update', Hashids::encode($data['tracking']->id)) }}"
                                                                            method="POST" onclick="confirmFunction()"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            <input type="hidden" name="_method"
                                                                                value="put">
                                                                            <button type="submit"
                                                                                class="btn btn-success d-inline waves-effect waves-light"
                                                                                title="Confirm" tabindex="0"
                                                                                data-plugin="tippy"
                                                                                data-tippy-placement="top"><i
                                                                                    class="fas fa-check"
                                                                                    onsubmit="confirmFunction()"></i>
                                                                                Dokumen Sesuai</button>
                                                                        </form>
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
                                                            @if ($data['tracking']->title != null)
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Judul
                                                                        Dokumen, </span> <br>
                                                                    <strong>{{ $data['tracking']->title }}</strong>
                                                                </p>
                                                            @endif
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
                                                            <div class="border p-3 mt-2 rounded">

                                                                <div class="form-check">
                                                                    <label class="form-check-label font-16 fw-bold"
                                                                        for="BillingOptRadio2">{{ $red->title }}</label>
                                                                    @if ($red->file != null)
                                                                        <div class="row">

                                                                            @if ($data['existing']->expired_date)
                                                                                @php
                                                                                    $expiredDate = \Carbon\Carbon::parse(
                                                                                        $data['existing']->expired_date,
                                                                                    );
                                                                                    $today = \Carbon\Carbon::parse(
                                                                                        $data['today'],
                                                                                    );
                                                                                @endphp

                                                                                @if ($today->gt($expiredDate))
                                                                                    <span class="text-danger">File expired
                                                                                        on
                                                                                        {{ $expiredDate->format('d-m-Y') }}</span><br>
                                                                                @else
                                                                                    <a
                                                                                        href="{{ route('request-existing.download', Hashids::encode($red->id)) }}"><i
                                                                                            class="mdi mdi-file-download-outline text-muted font-20"
                                                                                            title="Download"
                                                                                            tabindex="0"
                                                                                            data-plugin="tippy"
                                                                                            data-tippy-placement="top"></i>
                                                                                        Download Document</a>
                                                                                @endif
                                                                            @else
                                                                                <a
                                                                                    href="{{ route('request-existing.download', Hashids::encode($red->id)) }}"><i
                                                                                        class="mdi mdi-file-download-outline text-muted font-20"
                                                                                        title="Download" tabindex="0"
                                                                                        data-plugin="tippy"
                                                                                        data-tippy-placement="top"></i>
                                                                                    Download Document</a>
                                                                            @endif
                                                                        </div>
                                                                    @else
                                                                        <h5>No Document</h5>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach

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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Feedback dari dokumen</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('request-existing.feedback') }}" method="POST" id="fileUploadForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_request_document">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="simpleinput" class="form-label">Feedback <span
                                        class="text-danger">*</span></label>
                                <textarea name="description" id="" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Dimohon untuk upload Dokumen Pendukung, apabila dokumen tidak sesuai.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mt-1">
                                <label for="simpleinput" class="form-label">Dokumen Pendukung</label>
                                <input type="file" name="file" data-plugins="dropify"
                                    accept=".doc,.docx,.pdf,.xlsx" data-height="200" />
                            </div>
                        </div>

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
                            "{{ route('request-existing.show', Hashids::encode($data['tracking']->id)) }}";
                    }
                });
            });
        });
    </script>
@endsection
