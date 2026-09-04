@extends('layouts.tsp_master')

@section('title')
    Request Contract / Letter |
@endsection

@section('css')
    <style>
        .stage-navigation.active {
            background-color: #6658dd !important;
            color: #ffffff !important;
            /* #6658dd */
        }

        .substage-navigation {
            background-color: #e9e8f7;
            color: #6c6f7a !important;
            border-radius: 5px;
            transition: all 0.2s ease;
        }

        .substage-navigation.active {
            background-color: #a49ce7 !important;
            color: #ffffff !important;
        }

        .substage-navigation.text-success {
            background-color: #e8f5ed;
        }

        .substage-navigation.disabled {
            background-color: #eef0f2;
            color: #9aa0a6 !important;
            opacity: 1;
        }
    </style>
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
                                        <div class="tab-pane fade active show" id="request-document" role="tabpanel"
                                            aria-labelledby="custom-v-pills-billing-tab">
                                            <div>
                                                <h4 class="header-title" id="request-document-title">Loading...</h4>

                                                <p class="sub-header" id="request-document-description">Loading...</p>

                                                <div id="feedback-list-container"></div>

                                                <div id="request-document-detail">

                                                    <div class="text-center">
                                                        Loading...
                                                    </div>

                                                </div>
                                                <br>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-sm-6">
                                                </div>
                                                {{-- button continue to drafting by admin --}}
                                                @if (
                                                    ($requestDocument->stage_id == 1 || $requestDocument->stage_id == 2) &&
                                                        getRoles(Auth::user()->id) === 'Admin Legal TSP')
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="{{ route('tsp.request-document.legal-drafting', $requestDocument->id) }}"
                                                                class="btn btn-success">
                                                                <i class="mdi mdi-file me-1"></i> Continue to
                                                                Drafting </a>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- button request to revision dan verify by user --}}
                                                @if (
                                                    $requestDocument->stage_id == 3 &&
                                                        $requestDocument->substage_id == 1 &&
                                                        $requestDocument->status_id == 6 &&
                                                        getRoles(Auth::user()->id) !== 'Admin Legal TSP' &&
                                                        Auth::user()->division !== 'Board Of Directors')
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-warning btn-request-revision"
                                                                data-id="{{ $requestDocument->id }}">

                                                                <i class="mdi mdi-file-edit-outline me-1"></i>

                                                                Request to Revision

                                                            </a>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-success btn-verify-by-user"
                                                                title="Verify Request Document" tabindex="0"
                                                                data-plugin="tippy" data-tippy-placement="top"
                                                                data-id="{{ $requestDocument->id }}">

                                                                <i class="fas fa-check"></i>
                                                                Verify
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- button revise legal drafting request document by admin --}}
                                                @if (
                                                    $requestDocument->stage_id == 3 &&
                                                        $requestDocument->substage_id == 3 &&
                                                        getRoles(Auth::user()->id) === 'Admin Legal TSP')
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="{{ route('tsp.request-document.legal-drafting.show-revision', $requestDocument->id) }}"
                                                                class="btn btn-warning">
                                                                <i class="mdi mdi-file-edit-outline me-1"></i> Revise</a>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- button request to revision dan verify by committee --}}
                                                @if (
                                                    $requestDocument->stage_id == 3 &&
                                                        $requestDocument->substage_id == 2 &&
                                                        ($requestDocument->status_id == 7 || $requestDocument->status_id == 8) &&
                                                        getRoles(Auth::user()->id) !== 'Admin Legal TSP' &&
                                                        Auth::user()->division === 'Board Of Directors' &&
                                                        getBODNotVerified($requestDocument->id))
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-warning btn-request-revision-committe"
                                                                data-id="{{ $requestDocument->id }}">

                                                                <i class="mdi mdi-file-edit-outline me-1"></i>

                                                                Request to Revision

                                                            </a>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-success btn-verify-by-committee"
                                                                title="Verify Request Document" tabindex="0"
                                                                data-plugin="tippy" data-tippy-placement="top"
                                                                data-id="{{ $requestDocument->id }}">

                                                                <i class="fas fa-check"></i>
                                                                Verify
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- button revise legal drafting request document by user --}}
                                                @if (
                                                    $requestDocument->stage_id == 3 &&
                                                        $requestDocument->substage_id == 3 &&
                                                        $requestDocument->status_id == 11 &&
                                                        getRoles(Auth::user()->id) !== 'Admin Legal TSP' &&
                                                        Auth::user()->division !== 'Board Of Directors')
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="{{ route('tsp.request-document.show-revision', $requestDocument->id) }}"
                                                                class="btn btn-warning">
                                                                <i class="mdi mdi-file-edit-outline me-1"></i> Revise</a>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- button revise legal drafting request document by user --}}
                                                @if (
                                                    $requestDocument->stage_id == 4 &&
                                                        $requestDocument->status_id == 9 &&
                                                        getRoles(Auth::user()->id) !== 'Admin Legal TSP' &&
                                                        Auth::user()->division !== 'Board Of Directors')
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end mt-2 mt-sm-0">
                                                            <a href="{{ route('tsp.request-document.show-revision', $requestDocument->id) }}"
                                                                class="btn btn-warning">
                                                                <i class="mdi mdi-file-edit-outline me-1"></i> Revise</a>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-success btn-upload-final-document"
                                                                data-id="{{ $requestDocument->id }}">

                                                                <i class="fas fa-upload me-1"></i>

                                                                Upload Final Document

                                                            </a>
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
    <div id="modal-container"></div>
@endsection

@section('js')
    <script>
        let requestDocumentData = null;

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
                    target: 'send-draft',
                    substages: [{
                            id: 1,
                            sequence: 1,
                            name: 'Under Review User'
                        },
                        {
                            id: 2,
                            sequence: 2,
                            name: 'Under Review Committee'
                        },
                        {
                            id: 3,
                            sequence: 3,
                            name: 'Revision'
                        }
                    ]
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
                    target: 'send-rekanan',
                    substages: [{
                            id: 4,
                            sequence: 1,
                            name: 'Under Review User'
                        },
                        {
                            id: 5,
                            sequence: 2,
                            name: 'Under Review Committee'
                        },
                        {
                            id: 6,
                            sequence: 3,
                            name: 'Revision'
                        }
                    ]
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

            function renderStages(currentStage, currentSubstage = null) {

                let html = '';

                currentStage = Number(currentStage);
                currentSubstage = currentSubstage ?
                    Number(currentSubstage) :
                    null;

                stages.forEach(function(stage, index) {

                    const isCurrentStage =
                        currentStage === stage.id;

                    const isDisabled =
                        currentStage < stage.id;

                    html += `
                        <a
                            href="javascript:void(0)"
                            class="nav-link mt-2 py-2 stage-navigation
                                ${isCurrentStage ? 'active show' : ''}
                                ${isDisabled ? 'disabled' : ''}
                            "
                            id="${stage.target}-tab"
                            data-stage-id="${stage.id}"
                            data-stage-name="${stage.name}"
                            role="tab"
                            aria-selected="${isCurrentStage ? 'true' : 'false'}"
                        >
                            ${stage.name}
                        </a>
                    `;


                    /* RENDER SUBSTAGE */

                    if (stage.substages) {

                        html += `
                            <div 
                                class="substage-container d-flex flex-column align-items-center"
                                style="
                                    padding: 6px 0;
                                    margin: 0;
                                    gap: 5px;
                                "
                            >
                        `;

                        stage.substages.forEach(function(substage) {

                            const isCompletedSubstage =
                                currentStage > stage.id ||
                                (
                                    currentStage === stage.id &&
                                    currentSubstage > substage.id
                                );

                            const isActiveSubstage =
                                currentStage === stage.id &&
                                currentSubstage === substage.id;

                            const isDisabledSubstage =
                                currentStage < stage.id ||
                                (
                                    currentStage === stage.id &&
                                    currentSubstage < substage.id
                                );

                            html += `
                                <a
                                    href="javascript:void(0)"
                                    class="nav-link substage-navigation
                                        ${isActiveSubstage ? 'active show' : ''}
                                        ${isDisabledSubstage ? 'disabled' : ''}
                                        ${isCompletedSubstage ? 'text-success' : ''}
                                    "
                                    id="${stage.target}-substage-${substage.id}-tab"

                                    data-stage-id="${stage.id}"
                                    data-stage-name="${stage.name}"
                                    data-substage-id="${substage.id}"
                                    data-substage-name="${substage.name}"

                                    role="tab"
                                    style="
                                        width: 95%;
                                        margin: 0;
                                        padding: 8px 10px;
                                    "

                                   
                                >
                                    ${substage.sequence}. ${substage.name}
                                </a>
                            `;
                        });

                        html += `
                            </div>
                        `;
                    }


                    /*ARROW ANTAR STAGE*/
                    if (index < stages.length - 1) {

                        const isCompleted =
                            currentStage > stage.id;

                        html += `
                            <div
                                class="d-flex justify-content-center align-items-center"
                                style="
                                    height: 28px;
                                    margin: 0;
                                    padding: 0;
                                "
                            >
                                <i
                                    class="fas fa-arrow-circle-down
                                    ${isCompleted ? 'text-primary' : ''}"
                                ></i>
                            </div>
                        `;
                    }

                });

                $('#v-pills-tab').html(html);
            }

            function renderFeedbacks(feedbacks) {
                let html = '';

                const currentStage = Number(requestDocumentData?.stage_id ?? 0);

                if (currentStage >= 3 && feedbacks && feedbacks.length > 0) {
                    feedbacks.forEach(function(feedback) {
                        const feedbackDate = feedback.created_at_formatted ??
                            (feedback.created_at ?
                                new Date(feedback.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric'
                                }) :
                                '-');

                        const fileUrl = feedback.file_path ?
                            `{{ url('/') }}/${String(feedback.file_path).replace(/^\/+/, '')}` :
                            null;

                        html += `
                            <div class="border p-3 mb-3 rounded">
                                ${fileUrl ? `
                                                                                                                                                            <div class="float-end">
                                                                                                                                                                <a href="${fileUrl}" target="_blank" rel="noopener noreferrer">
                                                                                                                                                                    <i class="mdi mdi-file-download-outline text-muted font-20"
                                                                                                                                                                        title="Download" tabindex="0"
                                                                                                                                                                        data-plugin="tippy"
                                                                                                                                                                        data-tippy-placement="top"></i>
                                                                                                                                                                </a>
                                                                                                                                                            </div>
                                                                                                                                                        ` : ''}

                                <div class="form-check">
                                    <label class="form-check-label font-16 fw-bold">
                                        Feedback dari <b>${feedback.action_by_name ?? '-'}</b> - ${feedbackDate}
                                    </label>
                                </div>
                                <p class="mb-0 ps-3 pt-1">${feedback.remark ?? '-'}.</p>
                            </div>
                        `;
                    });
                }

                $('#feedback-list-container').html(html);
            }

            function renderRequestDocument(data, selectedStageId = null, selectedSubstageId = null) {

                let filesHtml = '';
                const stageInformation = {
                    1: {
                        title: 'Request Document',
                        description: 'Berikut adalah data permintaan dokumen yang telah dibuat.'
                    },

                    2: {
                        title: 'Legal Drafting (LD)',
                        description: 'Pada tahap ini, tim Legal melakukan proses penyusunan dan pembuatan draft kontrak berdasarkan Request Document yang telah diajukan.'
                    },

                    3: {
                        title: 'Feedback LD',
                        description: 'Draft kontrak telah dikirimkan dan sedang menunggu proses review serta feedback dari pihak terkait.'
                    },

                    4: {
                        title: 'Negotiation',
                        description: 'Pada tahap ini dilakukan proses negosiasi dan pembahasan terhadap draft kontrak beserta masukan yang diberikan.'
                    },

                    5: {
                        title: 'Form Legal Review (FLR)',
                        description: 'Draft kontrak sedang melalui proses Form Legal Review untuk memastikan kesesuaian aspek legal dan ketentuan yang berlaku.'
                    },

                    6: {
                        title: 'Feedback FLR',
                        description: 'Hasil review telah disampaikan dan sedang menunggu feedback atau tindak lanjut dari pihak terkait.'
                    },

                    7: {
                        title: 'Under Review BOD',
                        description: 'Dokumen sedang dalam proses review dan persetujuan oleh Board of Directors.'
                    },

                    8: {
                        title: 'Cleared for Delivery',
                        description: 'Dokumen telah selesai melalui proses review dan dinyatakan siap untuk proses delivery.'
                    },

                    9: {
                        title: 'Final Contract',
                        description: 'Kontrak telah memasuki tahap finalisasi dokumen sebelum proses administrasi dan filing.'
                    },

                    10: {
                        title: 'Document Filing',
                        description: 'Dokumen kontrak sedang dalam proses pengarsipan dan penyimpanan dokumen.'
                    },

                    11: {
                        title: 'Contract Active',
                        description: 'Kontrak telah aktif dan proses Request Document telah selesai.'
                    }
                };

                const stageId = Number(
                    selectedStageId ?? data.stage_id
                );


                const stageInfo =
                    stageInformation[stageId] ?? {
                        title: 'Request Document',
                        description: 'Berikut adalah informasi dan proses Request Document.'
                    };

                let title = stageInfo.title;

                /*
                JIKA ADA SUBSTAGE YANG DIPILIH
                */

                if (selectedSubstageId) {

                    const selectedStage = stages.find(
                        stage => Number(stage.id) === Number(stageId)
                    );

                    const selectedSubstage = selectedStage
                        ?.substages
                        ?.find(
                            substage =>
                            Number(substage.id) === Number(selectedSubstageId)
                        );

                    if (selectedSubstage) {

                        title += ` - ${selectedSubstage.name}`;

                    }
                }


                $('#request-document-title').text(title);

                $('#request-document-description').text(
                    stageInfo.description
                );

                // RENDER FILES

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

                // RENDER DETAIL REQUEST DOCUMENT

                let html = `
                <div class="row">
                    <div class="col-md-12">

                        <div class="border p-3 rounded mb-3">

                            <h5 class="mt-3 ps-3 pt-1">
                                ${data.document_number ? data.document_number + ' - ' : ''}${data.title ?? '-'}
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

                    requestDocumentData = response.data;
                    feedbacks = response.feedbacks;

                    renderStages(requestDocumentData.stage_id, requestDocumentData.substage_id);
                    renderFeedbacks(feedbacks);
                    renderRequestDocument(requestDocumentData, requestDocumentData.stage_id,
                        requestDocumentData.substage_id);

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

            //DELAGATED EVENT LISTENER UNTUK STAGE NAVIGATION
            $(document).on(
                'click',
                '.stage-navigation',
                function() {

                    const stageId = $(this).data('stage-id');


                    /*JANGAN BISA KLIK STAGE YANG BELUM TERSEDIA*/

                    if ($(this).hasClass('disabled')) {
                        return;
                    }


                    /*UPDATE ACTIVE MENU*/

                    $('.stage-navigation').removeClass(
                        'active show'
                    );

                    $('.substage-navigation').removeClass(
                        'active show'
                    );

                    $(this).addClass(
                        'active show'
                    );


                    /*RENDER HEADER DAN CONTENT*/

                    renderRequestDocument(
                        requestDocumentData,
                        stageId,
                        null
                    );

                }
            );

            // DELEGATED EVENT LISTENER UNTUK SUBSTAGE NAVIGATION
            $(document).on(
                'click',
                '.substage-navigation',
                function() {

                    const stageId = $(this).data('stage-id');

                    const substageId = $(this).data('substage-id');


                    /* JANGAN BISA KLIK SUBSTAGE YANG BELUM TERSEDIA */

                    if ($(this).hasClass('disabled')) {
                        return;
                    }


                    /* RESET ACTIVE MENU*/

                    $('.stage-navigation').removeClass(
                        'active show'
                    );

                    $('.substage-navigation').removeClass(
                        'active show'
                    );


                    /*ACTIVE SUBSTAGE */

                    $(this).addClass(
                        'active show'
                    );


                    /*ACTIVE PARENT STAGE */

                    $(`.stage-navigation[data-stage-id="${stageId}"]`)
                        .addClass('active show');


                    /*RENDER DATA */

                    renderRequestDocument(
                        requestDocumentData,
                        stageId,
                        substageId
                    );

                }
            );

            // KLIK BUTTON REQUEST TO REVISION BY USER
            $(document).on(
                'click',
                '.btn-request-revision',
                function() {

                    const id = $(this).data('id');
                    const url =
                        "{{ url('tsp/request-document/legal-drafting/show-request-to-revision-by-user') }}/" +
                        id;

                    $.ajax({
                        url: url,
                        type: 'GET',
                        beforeSend: function() {

                            $('#modal-container').html(`
                                <div class="text-center p-3">
                                    Loading...
                                </div>
                            `);

                        },


                        success: function(response) {
                            $('#modal-container').html(response);
                            const modalElement =
                                document.getElementById(
                                    'request-to-revision-modal'
                                );
                            if (!modalElement) {

                                console.error(
                                    'Modal request-to-revision-modal tidak ditemukan.'
                                );

                                return;

                            }

                            const revisionModal = new bootstrap.Modal(modalElement);
                            revisionModal.show();

                        },


                        error: function(xhr) {
                            console.error(xhr);
                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text: xhr.responseJSON?.message ??
                                    'Gagal memuat form Request to Revision.'

                            });

                        }

                    });

                }
            );

            /*SUBMIT REQUEST TO REVISION BY USER */
            $(document).on(
                'submit',
                '#request-to-revision-form',
                function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const id = form.data('id');
                    const button = $('#confirm-request-revision');

                    /*FORM DATA*/
                    const formData = new FormData(this);

                    /*INPUT*/

                    const remarkInput = $('#revision-remark');

                    const attachmentInput = $('#revision-attachment');

                    /*ERROR ELEMENT*/

                    const remarkError = $('#revision-remark-error');

                    const attachmentError = $('#revision-attachment-error');

                    /*RESET VALIDATION*/

                    remarkInput.removeClass('is-invalid');

                    attachmentInput.removeClass('is-invalid');

                    remarkError.text('');

                    attachmentError.text('');


                    /* LOADING BUTTON */

                    button.prop('disabled', true);

                    button.html(`
                        <span
                            class="spinner-border spinner-border-sm me-1">
                        </span>

                        Processing...
                    `);


                    /* AJAX */
                    $.ajax({

                        url: "{{ url('tsp/request-document/legal-drafting/request-to-revision-by-user') }}/" +
                            id,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            if (response.success) {

                                /*HIDE MODAL */
                                const modalElement = document.getElementById(
                                    'request-to-revision-modal');

                                const modal = bootstrap.Modal.getInstance(modalElement);

                                if (modal) {
                                    modal.hide();
                                }

                                /*SUCCESS */

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    confirmButtonText: 'OK'
                                }).then(() => {

                                    /*REDIRECT*/

                                    window.location.href =
                                        "{{ route('tsp.request-document.tracking', $requestDocument->id) }}";
                                });

                            }

                        },
                        error: function(xhr) {

                            console.error(xhr);
                            /*VALIDATION ERROR*/

                            if (xhr.status === 422) {

                                const errors = xhr.responseJSON.errors;

                                /*REMARK ERROR*/

                                if (errors.remark) {
                                    remarkInput.addClass('is-invalid');
                                    remarkError.text(errors.remark[0]);

                                }

                                /*ATTACHMENT ERROR*/

                                if (errors.attachment) {
                                    attachmentInput.addClass('is-invalid');

                                    attachmentError.text(errors.attachment[0]

                                    );

                                }

                                return;

                            }

                            /* SYSTEM ERROR*/
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message ??
                                    'Request to Revision gagal disubmit.'
                            });

                        },

                        complete: function() {
                            button.prop('disabled', false);
                            button.html(`

                                <i
                                    class="mdi mdi-file-edit-outline me-1">
                                </i>

                                Request to Revision

                            `);

                        }

                    });

                }
            );
        });

        // KLIK BUTTON VERIFY BY USER
        $(document).on('click', '.btn-verify-by-user', function(e) {

            e.preventDefault();

            const id = $(this).data('id');

            const url =
                "{{ url('tsp/request-document/legal-drafting') }}/verify-confirmation/" + id;

            $.ajax({
                url: url,
                type: 'GET',

                beforeSend: function() {
                    console.log('AJAX STARTED');

                    $('#modal-container').html(`
                        <div class="text-center p-3">
                            Loading...
                        </div>
                    `);
                },

                success: function(response) {

                    $('#modal-container').html(response);

                    const modalElement =
                        document.getElementById('verify-by-user-modal');

                    if (!modalElement) {
                        console.error('Element #verify-by-user-modal tidak ditemukan!');
                        return;
                    }

                    const verifyByUserModal =
                        new bootstrap.Modal(modalElement);

                    verifyByUserModal.show();
                },

                error: function(xhr) {

                    console.error('AJAX ERROR:', xhr);

                    alert('Gagal memuat konfirmasi verifikasi.');
                }
            });

        });

        // KLIK BUTTON CONFIRM VERIFY BY USER
        $(document).on('click', '#confirm-verify', function() {

            const id = $(this).data('id');

            const button = $(this);

            button.prop('disabled', true);

            button.html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Processing...
        `);

            $.ajax({

                url: "{{ url('tsp/request-document/legal-drafting') }}/verify-by-user/" + id,

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function(response) {

                    if (response.success) {

                        const modalElement = document.getElementById('verify-by-user-modal');

                        const modal = bootstrap.Modal.getInstance(modalElement);

                        modal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {

                            /*REDIRECT*/

                            window.location.href =
                                "{{ route('tsp.request-document.tracking', $requestDocument->id) }}";
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });

                    }

                },

                error: function(xhr) {

                    console.error(xhr);

                    alert(
                        xhr.responseJSON?.message ??
                        'Terjadi kesalahan saat memverifikasi Request Document.'
                    );

                },

                complete: function() {

                    button.prop('disabled', false);

                    button.html(`
                    <i class="fas fa-check me-1"></i>
                    Yes, Cancel Request
                `);

                }

            });

        });


        // KLIK BUTTON REQUEST TO REVISION COMMITE
        $(document).on(
            'click',
            '.btn-request-revision-committe',
            function() {

                const id = $(this).data('id');
                const url =
                    "{{ url('tsp/request-document/legal-drafting/show-request-to-revision-by-committee') }}/" +
                    id;

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {

                        $('#modal-container').html(`
                                <div class="text-center p-3">
                                    Loading...
                                </div>
                            `);

                    },


                    success: function(response) {
                        $('#modal-container').html(response);
                        const modalElement =
                            document.getElementById(
                                'request-to-revision-committee-modal'
                            );
                        if (!modalElement) {

                            console.error(
                                'Modal request-to-revision-committee-modal tidak ditemukan.'
                            );

                            return;

                        }

                        const revisionModal = new bootstrap.Modal(modalElement);
                        revisionModal.show();

                    },


                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: xhr.responseJSON?.message ??
                                'Gagal memuat form Request to Revision.'

                        });

                    }

                });

            }
        );

        /*SUBMIT REQUEST TO REVISION COMMITTEE*/
        $(document).on(
            'submit',
            '#request-to-revision-committee-form',
            function(e) {
                e.preventDefault();
                const form = $(this);
                const id = form.data('id');
                const button = $('#confirm-request-revision-committee');

                /*FORM DATA*/
                const formData = new FormData(this);

                /*INPUT*/

                const remarkInput = $('#revision-remark');

                const attachmentInput = $('#revision-attachment');

                /*ERROR ELEMENT*/

                const remarkError = $('#revision-remark-error');

                const attachmentError = $('#revision-attachment-error');

                /*RESET VALIDATION*/

                remarkInput.removeClass('is-invalid');

                attachmentInput.removeClass('is-invalid');

                remarkError.text('');

                attachmentError.text('');


                /* LOADING BUTTON */

                button.prop('disabled', true);

                button.html(`
                        <span
                            class="spinner-border spinner-border-sm me-1">
                        </span>

                        Processing...
                    `);


                /* AJAX */
                $.ajax({

                    url: "{{ url('tsp/request-document/legal-drafting/request-to-revision-by-committee') }}/" +
                        id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.success) {

                            /*HIDE MODAL */
                            const modalElement = document.getElementById(
                                'request-to-revision-committee-modal');

                            const modal = bootstrap.Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }

                            /*SUCCESS */

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {

                                /*REDIRECT*/

                                window.location.href =
                                    "{{ route('tsp.request-document.tracking', $requestDocument->id) }}";
                            });

                        }

                    },
                    error: function(xhr) {

                        console.error(xhr);
                        /*VALIDATION ERROR*/

                        if (xhr.status === 422) {

                            const errors = xhr.responseJSON.errors;

                            /*REMARK ERROR*/

                            if (errors.remark) {
                                remarkInput.addClass('is-invalid');
                                remarkError.text(errors.remark[0]);

                            }

                            /*ATTACHMENT ERROR*/

                            if (errors.attachment) {
                                attachmentInput.addClass('is-invalid');

                                attachmentError.text(errors.attachment[0]

                                );

                            }

                            return;

                        }

                        /* SYSTEM ERROR*/
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ??
                                'Request to Revision gagal disubmit.'
                        });

                    },

                    complete: function() {
                        button.prop('disabled', false);
                        button.html(`

                                <i
                                    class="mdi mdi-file-edit-outline me-1">
                                </i>

                                Request to Revision

                            `);

                    }

                });

            }
        );

        // KLIK BUTTON VERIFY BY COMMITTEE
        $(document).on('click', '.btn-verify-by-committee', function(e) {

            e.preventDefault();

            const id = $(this).data('id');

            const url =
                "{{ url('tsp/request-document/legal-drafting') }}/verify-confirmation-committee/" + id;

            $.ajax({
                url: url,
                type: 'GET',

                beforeSend: function() {
                    console.log('AJAX STARTED');

                    $('#modal-container').html(`
                        <div class="text-center p-3">
                            Loading...
                        </div>
                    `);
                },

                success: function(response) {

                    $('#modal-container').html(response);

                    const modalElement =
                        document.getElementById('verify-by-committee-modal');

                    if (!modalElement) {
                        console.error('Element #verify-by-committee-modal tidak ditemukan!');
                        return;
                    }

                    const verifyByCommitteeModal =
                        new bootstrap.Modal(modalElement);

                    verifyByCommitteeModal.show();
                },

                error: function(xhr) {

                    console.error('AJAX ERROR:', xhr);

                    alert('Gagal memuat konfirmasi verifikasi.');
                }
            });

        });

        // KLIK BUTTON CONFIRM VERIFY BY COMMITTEE
        $(document).on('click', '#confirm-verify-committee', function() {

            const id = $(this).data('id');

            const button = $(this);

            button.prop('disabled', true);

            button.html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Processing...
        `);

            $.ajax({

                url: "{{ url('tsp/request-document/legal-drafting') }}/verify-by-committee/" + id,

                type: 'POST',

                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function(response) {

                    if (response.success) {

                        const modalElement = document.getElementById('verify-by-committee-modal');

                        const modal = bootstrap.Modal.getInstance(modalElement);

                        modal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {

                            /*REDIRECT*/

                            window.location.href =
                                "{{ route('tsp.request-document.tracking', $requestDocument->id) }}";
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });

                    }

                },

                error: function(xhr) {

                    console.error(xhr);

                    alert(
                        xhr.responseJSON?.message ??
                        'Terjadi kesalahan saat memverifikasi Request Document.'
                    );

                },

                complete: function() {

                    button.prop('disabled', false);

                    button.html(`
                    <i class="fas fa-check me-1"></i>
                    Yes, Cancel Request
                `);

                }

            });

        });

        // KLIK BUTTON UPLOAD FINAL DOCUMENT BY USER
        $(document).on(
            'click',
            '.btn-upload-final-document',
            function() {

                const id = $(this).data('id');
                const url =
                    "{{ url('tsp/request-document/upload-final-document') }}/" +
                    id;

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {

                        $('#modal-container').html(`
                                <div class="text-center p-3">
                                    Loading...
                                </div>
                            `);

                    },

                    success: function(response) {
                        $('#modal-container').html(response);
                        const modalElement =
                            document.getElementById(
                                'upload-final-document-modal'
                            );
                        if (!modalElement) {

                            console.error(
                                'Modal upload-final-document-modal tidak ditemukan.'
                            );

                            return;

                        }

                        const uploadFinalDocumentModal = new bootstrap.Modal(modalElement);
                        uploadFinalDocumentModal.show();

                    },


                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: xhr.responseJSON?.message ??
                                'Gagal memuat form Upload Final Document.'

                        });

                    }

                });

            }
        );

        /*SUBMIT UPLOAD FINAL DOCUMENT BY USER */
        $(document).on(
            'submit',
            '#upload-final-document-form',
            function(e) {
                e.preventDefault();
                const form = $(this);
                const id = form.data('id');
                const button = $('#confirm-upload-final-document');

                /*FORM DATA*/
                const formData = new FormData(this);

                /*INPUT*/

                const attachmentInput = $('#final_document');

                /*ERROR ELEMENT*/

                const attachmentError = $('#final-document-error');

                /*RESET VALIDATION*/

                attachmentInput.removeClass('is-invalid');

                attachmentError.text('');


                /* LOADING BUTTON */

                button.prop('disabled', true);

                button.html(`
                        <span
                            class="spinner-border spinner-border-sm me-1">
                        </span>

                        Processing...
                    `);


                /* AJAX */
                $.ajax({

                    url: "{{ url('tsp/request-document/upload-final-document') }}/" +
                        id,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.success) {

                            /*HIDE MODAL */
                            const modalElement = document.getElementById(
                                'upload-final-document-modal');

                            const modal = bootstrap.Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }

                            /*SUCCESS */

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {

                                /*REDIRECT*/

                                window.location.href =
                                    "{{ route('tsp.request-document.tracking', $requestDocument->id) }}";
                            });

                        }

                    },
                    error: function(xhr) {

                        console.error(xhr);
                        /*VALIDATION ERROR*/

                        if (xhr.status === 422) {

                            const errors = xhr.responseJSON.errors;

                            /*FINAL DOCUMENT ERROR*/

                            if (errors.final_document) {
                                attachmentInput.addClass('is-invalid');

                                attachmentError.text(errors.final_document[0]

                                );

                            }

                            return;

                        }

                        /* SYSTEM ERROR*/
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ??
                                'Upload Final Document gagal disubmit.'
                        });

                    },

                    complete: function() {
                        button.prop('disabled', false);
                        button.html(`

                                <i
                                    class="fas fa-upload me-1">
                                </i>

                                Upload Final Document

                            `);

                    }

                });

            }
        );
    </script>
@endsection
