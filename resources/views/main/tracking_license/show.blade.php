@extends('layouts.master')

@section('title')
    Request License |
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
                                <li class="breadcrumb-item"><a href="{{ route('tracking-license.index') }}">Request
                                        License</a></li>
                                <li class="breadcrumb-item active">Show Request License</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request License</h4>
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
                                        <a class="nav-link @if ($data['tracking']->status == 0) active show @endif py-2"
                                            id="request-document-tab" data-bs-toggle="pill" href="#request-document"
                                            role="tab" aria-controls="custom-v-pills-billing" aria-selected="true">
                                            Request Document
                                        </a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 1) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 1) active show @endif @if ($data['tracking']->status < 1) disabled @endif"
                                            id="legal-drafting-tab" data-bs-toggle="pill" href="#legal-drafting"
                                            role="tab" aria-controls="custom-v-pills-shipping" aria-selected="false">
                                            Preparation</a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 2) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 2) active show @endif @if ($data['tracking']->status < 2) disabled @endif"
                                            id="send-draft-tab" data-bs-toggle="pill" href="#send-draft" role="tab"
                                            aria-controls="custom-v-pills-payment" aria-selected="false">
                                            Registration</a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 3) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 3) active show @endif @if ($data['tracking']->status < 3) disabled @endif"
                                            id="feedback-tab" data-bs-toggle="pill" href="#feedback" role="tab"
                                            aria-controls="custom-v-pills-payment" aria-selected="false">
                                            Complete</a>
                                        <i
                                            class="fas fa-arrow-circle-down @if ($data['tracking']->status >= 4) text-primary @endif mt-2"></i>
                                        <a class="nav-link mt-2 py-2 @if ($data['tracking']->status == 4) active show @endif @if ($data['tracking']->status < 4) disabled @endif"
                                            id="revisi-tab" data-bs-toggle="pill" href="#document-filing" role="tab"
                                            aria-controls="custom-v-pills-payment" aria-selected="false">
                                            Filing</a>
                                    </div>

                                </div> <!-- end col-->
                                <div class="col-lg-10">
                                    <div class="tab-content p-3">
                                        <div class="tab-pane fade @if ($data['tracking']->status == 0) active show @endif"
                                            id="request-document" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div>
                                                <h4 class="header-title">Request Document</h4>

                                                <p class="sub-header">Berikut adalah data permintaan dokumen yang telah
                                                    dibuat.</p>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="border p-3 rounded mb-3 mb-md-0">
                                                            <h5 class="mt-3 ps-3 pt-1">{{ $data['tracking']->title }}</h5>
                                                            <p class="mb-1 ps-3 pt-1"><span class="fw-semibold me-2">Email
                                                                    :</span> {{ $data['tracking']->email }}</p>
                                                            <p class="mb-1 ps-3 pt-1"><span class="fw-semibold me-2">Created
                                                                    By :</span>
                                                                {{ getUserName($data['tracking']->created_by)->name }}</p>
                                                            @if ($data['tracking']->is_unlimited_duration)
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Duration :</span>
                                                                    Unlimited</p>
                                                            @endif
                                                            <p class="mb-2 ps-3 pt-1"><span
                                                                    class="fw-semibold me-2">Department In Charge :
                                                                    <br></span>
                                                                @if ($data['tracking']->is_extend == true)
                                                                    @foreach (getDocumentScopeByBase($data['tracking']->base_document_id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                @else
                                                                    @foreach (getDocumentScope($data['tracking']->id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                @endif
                                                            </p>
                                                            <p class="mb-1 ps-3 pt-1"><span class="fw-semibold me-2">Notes :
                                                                </span><b>
                                                                    {{ $data['tracking']->note }}</b></p>
                                                            <p class="mb-2 ps-3 pt-1">
                                                                @if ($data['tracking']->is_cancel)
                                                                    <span class="badge bg-danger">Cancel</span>
                                                                @else
                                                                    @if ($data['tracking']->status == 0)
                                                                        <span class="badge bg-primary">Submitted</span>
                                                                    @elseif ($data['tracking']->status == 1)
                                                                        <span class="badge bg-primary">Preparation</span>
                                                                    @elseif ($data['tracking']->status == 2)
                                                                        <span class="badge bg-primary">Registration</span>
                                                                    @elseif ($data['tracking']->status == 3)
                                                                        <span class="badge bg-primary">Complete</span>
                                                                    @elseif ($data['tracking']->status == 4)
                                                                        <span class="badge bg-primary">Filing</span>
                                                                    @endif
                                                                @endif
                                                            </p>
                                                            @if ($data['tracking']->is_extend_automatically)
                                                                <p class="mb-0 ps-3 pt-1">
                                                                    <span class="badge bg-warning"><i
                                                                            class="fas fa-history"></i> Extend
                                                                        Automatically</span>
                                                                </p>
                                                            @endif
                                                        </div>
                                                        @if (count($data['document']) > 0)
                                                            <div class="row mt-4">
                                                                <div class="col-sm-6">
                                                                </div> <!-- end col -->
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <a href="{{ url('request-document-download-all-attachment', Hashids::encode($data['tracking']->id)) }}"
                                                                            class="btn btn-sm btn-primary">
                                                                            <i class="mdi mdi-download me-1"></i> Download
                                                                            All Attachment</a>
                                                                    </div>
                                                                </div> <!-- end col -->
                                                            </div> <!-- end row -->
                                                        @endif
                                                        <br>
                                                        @foreach ($data['document'] as $fb)
                                                            <div class="border p-3 mb-3 rounded">
                                                                @if ($fb->file != '')
                                                                    <div class="float-end">
                                                                        <a
                                                                            href="{{ route('request-document.download-upload', $fb->id) }}"><i
                                                                                class="mdi mdi-file-download-outline text-muted font-20"
                                                                                title="Download" tabindex="0"
                                                                                data-plugin="tippy"
                                                                                data-tippy-placement="top"></i></a>
                                                                    </div>
                                                                @endif
                                                                <div class="form-check">
                                                                    <label class="form-check-label font-16 fw-bold"
                                                                        for="BillingOptRadio2"><a
                                                                            href="{{ route('request-document.download-upload', $fb->id) }}"><i
                                                                                class="mdi mdi-file-document"></i>
                                                                            {{ $fb->file }}</a></label>
                                                                </div>

                                                            </div>
                                                        @endforeach

                                                        <div class="row mt-4">
                                                            <div class="col-sm-6">
                                                            </div> <!-- end col -->
                                                            @if ($data['tracking']->status == 0 && $data['tracking']->is_cancel == 0)
                                                                <div class="col-sm-6">
                                                                    <div class="text-sm-end mt-2 mt-sm-0">
                                                                        <a href="{{ route('tracking-license-drafting', Hashids::encode($data['tracking']->id)) }}"
                                                                            class="btn btn-success">
                                                                            <i class="mdi mdi-file me-1"></i> Continue to
                                                                            preparation </a>
                                                                    </div>
                                                                </div> <!-- end col -->
                                                            @endif
                                                        </div> <!-- end row -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 1) active show @endif"
                                            id="legal-drafting" role="tabpanel"
                                            aria-labelledby="custom-v-pills-shipping-tab">
                                            <div>
                                                <h4 class="header-title">Data Draft Document</h4>

                                                <p class="sub-header">Berikut data draft document yang telah dibuat oleh
                                                    legal.</p>
                                                @if ($data['tracking']->status >= 1)
                                                    @php
                                                        $feedback = getFeedback($data['tracking']->id);
                                                    @endphp
                                                    @foreach ($feedback as $fb)
                                                        <div class="border p-3 mb-3 rounded">
                                                            @if ($fb->file != '')
                                                                <div class="float-end">
                                                                    <a
                                                                        href="{{ route('tracking-drafting.feedback-download', $fb->id) }}"><i
                                                                            class="mdi mdi-file-download-outline text-muted font-20"
                                                                            title="Download" tabindex="0"
                                                                            data-plugin="tippy"
                                                                            data-tippy-placement="top"></i></a>
                                                                </div>
                                                            @endif

                                                            <div class="form-check">
                                                                <label class="form-check-label font-16 fw-bold"
                                                                    for="BillingOptRadio2">Feedback dari
                                                                    <b>{{ getUserName($fb->created_by)->name }}</b> -
                                                                    {{ formatDate($fb->created_at) }}</label>
                                                            </div>
                                                            <p class="mb-0 ps-3 pt-1">{{ $fb->feedback }}.</p>

                                                        </div>
                                                    @endforeach
                                                    @php
                                                        $document = getDocumentDrafting($data['tracking']->id);
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="border p-3 rounded mb-3 mb-md-0">
                                                                <div class="float-end">
                                                                    @if ($data['tracking']->status == 1)
                                                                        <a
                                                                            href="{{ route('tracking-license-drafting.edit', Hashids::encode($document->id)) }}"><i
                                                                                class="mdi mdi-square-edit-outline text-muted"
                                                                                style="font-size: 30px" title="Edit"
                                                                                tabindex="0" data-plugin="tippy"
                                                                                data-tippy-placement="top"></i></a>
                                                                    @endif
                                                                </div>
                                                                <h5>{{ $document->contract_number }} -
                                                                    {{ $document->description }}</h5>

                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Effective Date </span>
                                                                    <b>{{ $document->contract_date }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Perusahaan
                                                                    </span><b>{{ $document->company }}</b></p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">PIC </span><br>
                                                                    @foreach (getPicDocument($document->id) as $pd)
                                                                        <span
                                                                            class="badge bg-primary">{{ $pd->name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Revisi Ke </span><b>
                                                                        {{ $document->revision }}</b></p>
                                                                @if ($document->is_unlimited_duration)
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration
                                                                        </span><b>Unlimited</b></p>
                                                                @else
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration </span><b>
                                                                            {{ $document->duration_days }} hari</b></p>
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Alert </span><b>
                                                                            {{ $document->alert_days }} hari
                                                                            sebelumnya</b></p>
                                                                @endif
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Created By
                                                                    </span><b>{{ getUserName($document->created_by)->name }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Note
                                                                    </span><b>{{ $document->note }}</b></p>
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Department In Charge
                                                                        <br></span>
                                                                    @foreach (getDocumentScope($data['tracking']->id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                </p>

                                                                @if ($document->is_extend_automatically)
                                                                    <p class="mb-0 ps-3 pt-1">
                                                                        <span class="badge bg-warning"><i
                                                                                class="fas fa-history"></i> Extend
                                                                            Automatically</span>
                                                                    </p>
                                                                @endif

                                                                @if ($data['tracking']->status_license == 1 && $data['tracking']->status == 1)
                                                                    <div class="row mt-4">
                                                                        <div class="col-sm-6">
                                                                        </div> <!-- end col -->
                                                                        <div class="col-sm-6">
                                                                            <div class="text-sm-end mt-2 mt-sm-0">
                                                                                <button type="button"
                                                                                    class="btn btn-warning d-inline waves-effect waves-light btn_revision"
                                                                                    title="Revisi" tabindex="0"
                                                                                    data-plugin="tippy"
                                                                                    data-tippy-placement="top"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#feedback_modal"
                                                                                    data-id="{{ $data['tracking']->id }}"
                                                                                    data-iddocument="{{ $document->id }}"><i
                                                                                        class="mdi mdi-mail me-1"></i>Add
                                                                                    Feedback</button>
                                                                            </div>

                                                                        </div> <!-- end col -->
                                                                    </div> <!-- end row -->
                                                                @endif
                                                            </div>

                                                            <div class="row mt-4">
                                                                <div class="col-sm-6">
                                                                </div> <!-- end col -->
                                                                @if ($data['tracking']->status == 1 && $data['tracking']->is_cancel == 0)
                                                                    @if ($data['tracking']->status_license == 1)
                                                                        <div class="col-sm-6">
                                                                            <div class="text-sm-end mt-2 mt-sm-0">
                                                                                <form
                                                                                    action="{{ route('tracking-license-drafting.registration', Hashids::encode($data['tracking']->id)) }}"
                                                                                    method="POST"
                                                                                    onclick="confirmFunction()"
                                                                                    class="d-inline">
                                                                                    @csrf
                                                                                    <button type="submit"
                                                                                        class="btn btn-success d-inline waves-effect waves-light"
                                                                                        title="Send" tabindex="0"
                                                                                        data-plugin="tippy"
                                                                                        data-tippy-placement="top"><i
                                                                                            class="mdi mdi-mail me-1"
                                                                                            onsubmit="confirmFunction()"></i>Continue
                                                                                        To Registration</button>
                                                                                </form>
                                                                            </div>
                                                                        </div> <!-- end col -->
                                                                    @else
                                                                        <div class="col-sm-6">
                                                                            <div class="text-sm-end mt-2 mt-sm-0">
                                                                                <form
                                                                                    action="{{ route('tracking-license-drafting.send-draft', Hashids::encode($data['tracking']->id)) }}"
                                                                                    method="POST"
                                                                                    onclick="confirmFunction()"
                                                                                    class="d-inline">
                                                                                    @csrf
                                                                                    <button type="submit"
                                                                                        class="btn btn-success d-inline waves-effect waves-light"
                                                                                        title="Send" tabindex="0"
                                                                                        data-plugin="tippy"
                                                                                        data-tippy-placement="top"><i
                                                                                            class="mdi mdi-mail me-1"
                                                                                            onsubmit="confirmFunction()"></i>Send
                                                                                        Draft To Requester</button>
                                                                                </form>
                                                                            </div>
                                                                        </div> <!-- end col -->
                                                                    @endif
                                                                @endif
                                                            </div> <!-- end row -->
                                                        </div>
                                                    </div>
                                                    <h4 class="header-title mt-4">History Drafting</h4>

                                                    <p class="text-muted mb-3">Berikut adalah data history perubahan dari
                                                        data document yang sudah draft.</p>

                                                    <div class="row">
                                                        <table id="" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Contract Number</th>
                                                                    <th>Description</th>
                                                                    <th>Revisi Ke</th>
                                                                    <th>Updated At</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $no = 1;
                                                                    $documentHistory = getDocumentHistory(
                                                                        $document->id,
                                                                    );
                                                                @endphp
                                                                @foreach ($documentHistory as $i)
                                                                    <tr>
                                                                        <td>{{ $no++ }}</td>
                                                                        <td>{{ $i->contract_number }}</td>
                                                                        <td>{{ $i->description }}</td>
                                                                        <td>{{ $i->revision }}</td>
                                                                        <td>{{ formatDate($i->updated_at) }}</td>
                                                                        <td>
                                                                            @php
                                                                                $num = 1;
                                                                            @endphp
                                                                            @foreach (getDocumentHistoryAttachment($i->id) as $dh)
                                                                                <div class="float-center">
                                                                                    <a
                                                                                        href="{{ route('tracking-drafting.attachment-download', $dh->id) }}"><i
                                                                                            class="mdi mdi-file-download-outline text-muted font-20"
                                                                                            title="Download"
                                                                                            tabindex="0"
                                                                                            data-plugin="tippy"
                                                                                            data-tippy-placement="top"></i>
                                                                                        Download Dokumen Pendukung
                                                                                        {{ $num++ }}</a>

                                                                                </div>
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end row-->
                                                @endif
                                                <!-- end row-->
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 2) active show @endif"
                                            id="send-draft" role="tabpanel" aria-labelledby="custom-v-pills-payment-tab">
                                            <div>
                                                <h4 class="header-title">Registration</h4>

                                                <p class="sub-header">Berikut adalah data permintaan dokumen yang telah
                                                    dibuat.</p>

                                                @if ($data['tracking']->status >= 2)
                                                    @php
                                                        $document = getDocumentDrafting($data['tracking']->id);
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="border p-3 rounded mb-3 mb-md-0">
                                                                <div class="float-end">
                                                                    @if ($data['tracking']->status == 2)
                                                                        <a
                                                                            href="{{ route('tracking-license-drafting.edit', Hashids::encode($document->id)) }}"><i
                                                                                class="mdi mdi-square-edit-outline text-muted"
                                                                                style="font-size: 30px" title="Edit"
                                                                                tabindex="0" data-plugin="tippy"
                                                                                data-tippy-placement="top"></i></a>
                                                                    @endif
                                                                </div>
                                                                <h5>{{ $document->contract_number }} -
                                                                    {{ $document->description }}</h5>

                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Effective Date </span>
                                                                    <b>{{ $document->contract_date }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Perusahaan
                                                                    </span><b>{{ $document->company }}</b></p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">PIC </span><br>
                                                                    @foreach (getPicDocument($document->id) as $pd)
                                                                        <span
                                                                            class="badge bg-primary">{{ $pd->name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Revisi Ke </span><b>
                                                                        {{ $document->revision }}</b></p>
                                                                @if ($document->is_unlimited_duration)
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration
                                                                        </span><b>Unlimited</b></p>
                                                                @else
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration </span><b>
                                                                            {{ $document->duration_days }} hari</b></p>
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Alert </span><b>
                                                                            {{ $document->alert_days }} hari
                                                                            sebelumnya</b></p>
                                                                @endif
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Created By
                                                                    </span><b>{{ getUserName($document->created_by)->name }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Note
                                                                    </span><b>{{ $document->note }}</b></p>
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Department In Charge
                                                                        <br></span>
                                                                    @foreach (getDocumentScope($data['tracking']->id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                @if ($document->is_extend_automatically)
                                                                    <p class="mb-0 ps-3 pt-1">
                                                                        <span class="badge bg-warning"><i
                                                                                class="fas fa-history"></i> Extend
                                                                            Automatically</span>
                                                                    </p>
                                                                @endif
                                                            </div>

                                                            <div class="row mt-4">
                                                                <div class="col-sm-6">
                                                                </div> <!-- end col -->
                                                                @if ($data['tracking']->status == 2 && $data['tracking']->is_cancel == 0)
                                                                    <div class="col-sm-6">
                                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                                            <form
                                                                                action="{{ route('tracking-license-drafting.complete', Hashids::encode($data['tracking']->id)) }}"
                                                                                method="POST" onclick="confirmFunction()"
                                                                                class="d-inline">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="btn btn-success d-inline waves-effect waves-light"
                                                                                    title="Send" tabindex="0"
                                                                                    data-plugin="tippy"
                                                                                    data-tippy-placement="top"><i
                                                                                        class="mdi mdi-mail me-1"
                                                                                        onsubmit="confirmFunction()"></i>Complete
                                                                                    Draft</button>
                                                                            </form>
                                                                        </div>
                                                                    </div> <!-- end col -->
                                                                @endif
                                                            </div> <!-- end row -->

                                                        </div>
                                                    </div>
                                                    <h4 class="header-title mt-4">History Drafting</h4>

                                                    <p class="text-muted mb-3">Berikut adalah data history perubahan dari
                                                        data document yang sudah draft.</p>

                                                    <div class="row">
                                                        <table id="" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Contract Number</th>
                                                                    <th>Description</th>
                                                                    <th>Revisi Ke</th>
                                                                    <th>Updated At</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $no = 1;
                                                                    $documentHistory = getDocumentHistory(
                                                                        $document->id,
                                                                    );
                                                                @endphp
                                                                @foreach ($documentHistory as $i)
                                                                    <tr>
                                                                        <td>{{ $no++ }}</td>
                                                                        <td>{{ $i->contract_number }}</td>
                                                                        <td>{{ $i->description }}</td>
                                                                        <td>{{ $i->revision }}</td>
                                                                        <td>{{ formatDate($i->updated_at) }}</td>
                                                                        <td>
                                                                            @php
                                                                                $num = 1;
                                                                            @endphp
                                                                            @foreach (getDocumentHistoryAttachment($i->id) as $dh)
                                                                                <div class="float-center">
                                                                                    <a
                                                                                        href="{{ route('tracking-drafting.attachment-download', $dh->id) }}"><i
                                                                                            class="mdi mdi-file-download-outline text-muted font-20"
                                                                                            title="Download"
                                                                                            tabindex="0"
                                                                                            data-plugin="tippy"
                                                                                            data-tippy-placement="top"></i>
                                                                                        Download Dokumen Pendukung
                                                                                        {{ $num++ }}</a>

                                                                                </div>
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end row-->
                                                @endif

                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 3) active show @endif"
                                            id="feedback" role="tabpanel" aria-labelledby="custom-v-pills-payment-tab">
                                            <div>
                                                <h4 class="header-title">Complete</h4>

                                                <p class="sub-header">Berikut merupakan dokumen yang sudah drafting.</p>

                                                <!-- Pay with Paypal box-->
                                                @if ($data['tracking']->status >= 3)
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="border p-3 rounded mb-3 mb-md-0">
                                                                <h5>{{ $document->contract_number }} -
                                                                    {{ $document->description }}</h5>

                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Effective Date </span>
                                                                    <b>{{ $document->contract_date }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Perusahaan
                                                                    </span><b>{{ $document->company }}</b></p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">PIC </span><br>
                                                                    @foreach (getPicDocument($document->id) as $pd)
                                                                        <span
                                                                            class="badge bg-primary">{{ $pd->name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Revisi Ke </span><b>
                                                                        {{ $document->revision }}</b></p>
                                                                @if ($document->is_unlimited_duration)
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration
                                                                        </span><b>Unlimited</b></p>
                                                                @else
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration </span><b>
                                                                            {{ $document->duration_days }} hari</b></p>
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Alert </span><b>
                                                                            {{ $document->alert_days }} hari
                                                                            sebelumnya</b></p>
                                                                @endif
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Created By
                                                                    </span><b>{{ getUserName($document->created_by)->name }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Note
                                                                    </span><b>{{ $document->note }}</b></p>
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Department In Charge
                                                                        <br></span>
                                                                    @foreach (getDocumentScope($data['tracking']->id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                @if ($document->is_extend_automatically)
                                                                    <p class="mb-0 ps-3 pt-1">
                                                                        <span class="badge bg-warning"><i
                                                                                class="fas fa-history"></i> Extend
                                                                            Automatically</span>
                                                                    </p>
                                                                @endif

                                                                @foreach (getDocumentFinal($document->id) as $df)
                                                                    <div class="border p-3 mb-3 rounded">
                                                                        @if ($df->file != '')
                                                                            <div class="float-end">
                                                                                <a
                                                                                    href="{{ route('tracking-drafting.final-download', $df->id) }}"><i
                                                                                        class="mdi mdi-file-download-outline text-muted font-20"
                                                                                        title="Download" tabindex="0"
                                                                                        data-plugin="tippy"
                                                                                        data-tippy-placement="top"></i></a>
                                                                            </div>
                                                                        @endif
                                                                        <div class="form-check">
                                                                            <label class="form-check-label font-16 fw-bold"
                                                                                for="BillingOptRadio2">Dokumen Final dari
                                                                                <b>{{ getUserName($df->created_by)->name }}</b>
                                                                                - {{ formatDate($df->created_at) }}</label>
                                                                        </div>
                                                                        <p class="mb-0 ps-3 pt-1"><a
                                                                                href="{{ route('tracking-drafting.final-download', $df->id) }}">{{ $df->file }}.</a>
                                                                        </p>

                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <div class="row mt-4">
                                                                <div class="col-sm-6">
                                                                </div> <!-- end col -->
                                                                @if ($data['tracking']->status == 3 && $data['tracking']->is_cancel == 0)
                                                                    <div class="col-sm-6">
                                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                                            <a href="{{ route('tracking-license-drafting.document-filing', Hashids::encode($document->id)) }}"
                                                                                class="btn btn-success d-inline waves-effect waves-light"
                                                                                title="Document Filing" tabindex="0"
                                                                                data-plugin="tippy"
                                                                                data-tippy-placement="top"><i
                                                                                    class="mdi mdi-mail me-1"></i> Document
                                                                                Filing</a>
                                                                        </div>
                                                                    </div> <!-- end col -->
                                                                @endif
                                                            </div> <!-- end row -->
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if ($data['tracking']->status == 4) active show @endif"
                                            id="document-filing" role="tabpanel"
                                            aria-labelledby="custom-v-pills-payment-tab">
                                            <div>
                                                <h4 class="header-title">Document Filing</h4>

                                                <p class="sub-header">Document Filing merupakan fase terakhir request
                                                    document.</p>

                                                @if ($data['tracking']->status >= 4)
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="border p-3 rounded mb-3 mb-md-0">
                                                                <div class="float-end">
                                                                    <a
                                                                        href="{{ route('license.detail', Hashids::encode($document->base_document_id)) }}"><i
                                                                            class="mdi mdi-eye text-muted"
                                                                            style="font-size: 30px" title="Detail"
                                                                            tabindex="0" data-plugin="tippy"
                                                                            data-tippy-placement="top"></i></a>
                                                                </div>
                                                                <h5>{{ $document->contract_number }} -
                                                                    {{ $document->description }}</h5>

                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Effective Date </span>
                                                                    <b>{{ $document->contract_date }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Perusahaan
                                                                    </span><b>{{ $document->company }}</b></p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">PIC </span><br>
                                                                    @foreach (getPicDocument($document->id) as $pd)
                                                                        <span
                                                                            class="badge bg-primary">{{ $pd->name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Revisi Ke </span><b>
                                                                        {{ $document->revision }}</b></p>
                                                                @if ($document->is_unlimited_duration)
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration
                                                                        </span><b>Unlimited</b></p>
                                                                @else
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Duration </span><b>
                                                                            {{ $document->duration_days }} hari</b></p>
                                                                    <p class="mb-1 ps-3 pt-1"><span
                                                                            class="fw-semibold me-2">Alert </span><b>
                                                                            {{ $document->alert_days }} hari
                                                                            sebelumnya</b></p>
                                                                @endif
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Created By
                                                                    </span><b>{{ getUserName($document->created_by)->name }}</b>
                                                                </p>
                                                                <p class="mb-1 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Note
                                                                    </span><b>{{ $document->note }}</b></p>
                                                                <p class="mb-2 ps-3 pt-1"><span
                                                                        class="fw-semibold me-2">Department In Charge
                                                                        <br></span>
                                                                    @foreach (getDocumentScope($data['tracking']->id) as $ds)
                                                                        <span
                                                                            class="badge bg-info">{{ $ds->department_name }}</span>
                                                                    @endforeach
                                                                </p>
                                                                @if ($document->is_extend_automatically)
                                                                    <p class="mb-0 ps-3 pt-1">
                                                                        <span class="badge bg-warning"><i
                                                                                class="fas fa-history"></i> Extend
                                                                            Automatically</span>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h4 class="header-title mt-4">Ringkasan</h4>

                                                    <p class="text-muted mb-3">Berikut ringkasan yang bisa dilihat oleh
                                                        semua user.</p>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="border p-3 rounded mb-3 mb-md-0">
                                                                <h5>{{ $document->title_ringkasan }}</h5>

                                                                <div class="mb-1 ps-3 pt-1">{!! $document->ringkasan !!}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

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
                    <h4 class="modal-title" id="standard-modalLabel">Add Feedback</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tracking-license-drafting.feedback-draft') }}" method="POST"
                    id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id_request_document">
                        <input type="hidden" name="id_document" id="id_document">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="simpleinput" class="form-label">Feedback <span
                                        class="text-danger">*</span></label>
                                <textarea name="feedback" id="" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mt-3">
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
                document.getElementById("id_document").value = $(this).attr('data-iddocument');
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
                            "{{ route('tracking-license.show', Hashids::encode($data['tracking']->id)) }}";
                    }
                });
            });
        });
    </script>
@endsection
