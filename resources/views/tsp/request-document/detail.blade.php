@extends('layouts.tsp_master')

@section('title')
    Request Contract / Letter |
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
                                <li class="breadcrumb-item"><a href="{{ route('tsp.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('tsp.request-document') }}">Request
                                        Document</a></li>
                                <li class="breadcrumb-item active">Show Request Contract / Letter</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Contract / Letter</h4>
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
                                        <div class="text-center p-3">
                                            Loading...
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="tab-content p-3">
                                        <div class="tab-pane fade @if ($requestDocument->stage_id == 1) active show @endif"
                                            id="request-document" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div>
                                                <h4 class="header-title">Request Document</h4>

                                                <p class="sub-header">Berikut adalah data permintaan dokumen yang telah
                                                    dibuat.</p>
                                                <div id="request-document-detail">

                                                    <div class="text-center">
                                                        Loading...
                                                    </div>

                                                </div>
                                                <br>
                                                {{-- @foreach ($data['document'] as $fb)
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
                                                @endforeach --}}
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            const stages = [{
                    id: 1,
                    name: 'Request Document',
                    target: 'request-document'
                },
                {
                    id: 2,
                    name: 'Legal Drafting (LD)',
                    target: 'legal-drafting'
                },
                {
                    id: 3,
                    name: 'Feedback LD',
                    target: 'send-draft'
                },
                {
                    id: 4,
                    name: 'Negotiation',
                    target: 'feedback'
                },
                {
                    id: 5,
                    name: 'Form Legal Review (FLR)',
                    target: 'revisi'
                },
                {
                    id: 6,
                    name: 'Feedback FLR',
                    target: 'send-rekanan'
                },
                {
                    id: 7,
                    name: 'Under Review BOD',
                    target: 'negosiasi'
                },
                {
                    id: 8,
                    name: 'Cleared for Delivery',
                    target: 'cleared-for-delivery'
                },
                {
                    id: 9,
                    name: 'Final Contract',
                    target: 'final-contract'
                },
                {
                    id: 10,
                    name: 'Document Filing',
                    target: 'document-filing'
                },
                {
                    id: 11,
                    name: 'Contract Active',
                    target: 'contract-active'
                }
            ];

            function renderStages(currentStage) {

                let html = '';

                stages.forEach(function(stage, index) {

                    const isActive = Number(currentStage) === stage.id;

                    const isDisabled = Number(currentStage) < stage.id;

                    html += `
                        <a
                            class="nav-link mt-2 py-2
                                ${isActive ? 'active show' : ''}
                                ${isDisabled ? 'disabled' : ''}
                            "
                            id="${stage.target}-tab"
                            data-bs-toggle="pill"
                            href="#${stage.target}"
                            role="tab"
                            aria-selected="${isActive ? 'true' : 'false'}"
                        >
                            ${stage.name}
                        </a>
                    `;

                    if (index < stages.length - 1) {

                        const isCompleted = Number(currentStage) >= stage.id + 1;

                        html += `
                            <i class="fas fa-arrow-circle-down
                                ${isCompleted ? 'text-primary' : ''}
                                mt-2">
                            </i>
                        `;
                    }
                });

                $('#v-pills-tab').html(html);
            }

            function renderRequestDocument(data) {
                let filesHtml = '';

                if (data.files && data.files.length > 0) {

                    data.files.forEach(function(file) {

                        filesHtml += `
                            <div class="border p-3 mb-3 rounded">

                                <div class="float-end">
                                    <a href="{{ url('/') }}/${file.file_path}"
                                        target="_blank">

                                        <i class="mdi mdi-file-download-outline text-muted font-20"
                                            title="Download">
                                        </i>

                                    </a>
                                </div>

                                <div class="form-check">

                                    <label class="form-check-label font-16 fw-bold">

                                        <a href="{{ url('/') }}/${file.file_path}"
                                            target="_blank">

                                            <i class="mdi mdi-file-document"></i>

                                            ${file.name ?? '-'}

                                        </a>

                                    </label>

                                    <div class="text-muted small mt-1">
                                        ${file.document_type ?? '-'}
                                    </div>

                                </div>

                            </div>
                        `;
                    });

                } else {

                    filesHtml = `
                        <div class="text-center text-muted border p-3 rounded">
                            Tidak ada attachment.
                        </div>
                    `;
                }

                let html = `
                <div class="row">
                    <div class="col-md-12">

                        <div class="border p-3 rounded mb-3">

                            <h5 class="mt-3 ps-3 pt-1">
                                ${data.document_number ?? '-'} ${data.title ?? '-'}
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Contract Type :
                                        </span>
                                        ${data.contract_type ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Potential Amount :
                                        </span>
                                        ${data.potential_amount ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Requester :
                                        </span>
                                        ${data.requester ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Sign Status :
                                        </span>
                                        ${data.sign_status ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Project Category :
                                        </span>
                                        ${data.is_project ? 'Project' : 'Non-Project'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            SOW :
                                        </span>
                                        ${data.sow ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Transaction Procedure :
                                        </span>
                                        ${data.transaction_procedure ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            KPI :
                                        </span>
                                        ${data.kpi ?? '-'}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <p class="mb-2 ps-3 pt-1 fw-semibold">PIC Data</p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            PIC Name :
                                        </span>
                                        ${data.pics?.name ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            PIC Position :
                                        </span>
                                        ${data.pics?.position ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            PIC Email :
                                        </span>
                                        ${data.pics?.email ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            PIC Phone :
                                        </span>
                                        ${data.pics?.phone ?? '-'}
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer :
                                        </span>

                                        ${data.customer
                                            ? data.customer.name
                                            : '-'
                                        }
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer NIB :
                                        </span>

                                        ${data.customer
                                            ? data.customer.nib
                                            : '-'
                                        }
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer NPWP :
                                        </span>

                                        ${data.customer
                                            ? data.customer.npwp
                                            : '-'
                                        }
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer Address :
                                        </span>

                                        ${data.customer?.address ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer Postal Code :
                                        </span>

                                        ${data.customer?.postal_code ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer Email :
                                        </span>

                                        ${data.customer?.email ?? '-'}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer PIC Name :
                                        </span>

                                        ${data.customer?.customer_pics?.[0]?.name ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer PIC Position :
                                        </span>

                                        ${data.customer?.customer_pics?.[0]?.position ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer PIC Email :
                                        </span>

                                        ${data.customer?.customer_pics?.[0]?.email ?? '-'}
                                    </p>

                                    <p class="mb-2 ps-3 pt-1">
                                        <span class="fw-semibold me-2">
                                            Customer PIC Phone :
                                        </span>

                                        ${data.customer?.customer_pics?.[0]?.phone ?? '-'}
                                    </p>
                                </div>
                            </div>

                            

                        </div>

                    </div>
                </div>

                <div class="mt-3">
                    ${filesHtml}
                </div>
            `;

                $('#request-document-detail').html(html);
            }

            const requestDocumentId = "{{ $requestDocument->id }}";

            let url = "{{ url('tsp/request-document/data') }}/" + requestDocumentId;

            url = url.replace(':id', requestDocumentId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {

                    if (!response.success) {
                        return;
                    }

                    const data = response.data;

                    renderStages(data.stage_id);
                    renderRequestDocument(data);

                },

                error: function(xhr) {

                    console.error(xhr);

                    $('#request-document-detail').html(`
                        <div class="alert alert-danger">
                            Gagal mengambil data Request Document.
                        </div>
                    `);
                }
            });

        });
    </script>
@endsection
