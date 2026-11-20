@extends('layouts.tsp_master')

@section('title')
    Revisi Legal Drafting |
@endsection

@section('content')
    <div class="content">

        <div class="container-fluid">

            {{-- PAGE TITLE --}}

            <div class="row">

                <div class="col-12">

                    <div class="page-title-box">

                        <div class="page-title-right">

                            <ol class="breadcrumb m-0">

                                <li class="breadcrumb-item">
                                    <a href="{{ route('tsp.request-document') }}">
                                        Request Document
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    Legal Drafting
                                </li>

                            </ol>

                        </div>

                        <h4 class="page-title">
                            Revisi Legal Drafting
                        </h4>

                    </div>

                </div>

            </div>


            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            {{-- REQUEST DOCUMENT INFO --}}

                            <div class="mb-4">

                                <h4 class="header-title">

                                    {{ $requestDocument->document_number ? $requestDocument->document_number . ' - ' . $requestDocument->title : $requestDocument->title }}

                                </h4>

                                <p class="text-muted mb-0">

                                    Revisi Legal Drafting Request Document

                                </p>

                            </div>


                            <form id="legal-drafting-form"
                                action="{{ url('tsp/request-document/legal-drafting/revision/' . $requestDocument->id) }}"
                                method="POST" enctype="multipart/form-data">

                                @csrf

                                {{-- DRAFT CONTRACT --}}

                                <div class="mb-4">

                                    <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                                        Untuk attachment Draft Contract, file harus diawali dengan prefix
                                        (draft contract).
                                    </div>

                                    <label for="draft_contract" class="form-label fw-bold">

                                        Draft Contract

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input type="file" class="form-control @error('draft_contract') is-invalid @enderror"
                                        id="draft_contract" name="draft_contract" accept=".pdf"
                                        data-label="Draft Contract">


                                    <div class="form-text">
                                        @if ($draftContract)
                                            Upload file Draft Contract baru untuk menggantikan dokumen sebelumnya.
                                            Format PDF, maksimal 10 MB.
                                        @else
                                            Format PDF, maksimal 10 MB.
                                        @endif
                                    </div>
                                    @error('draft_contract')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- EXISTING DRAFT CONTRACT --}}

                                @if ($draftContract)
                                    <div class="border rounded p-3 mb-3">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>

                                                <i class="mdi mdi-file-pdf-box text-danger font-22"></i>

                                                <span class="ms-2">

                                                    {{ $draftContract->name }}

                                                </span>

                                                <small class="d-block text-muted ms-4">

                                                    Draft Contract sebelumnya

                                                </small>

                                            </div>


                                            <a href="{{ url('/tsp/download-request-document-file') }}/{{ $draftContract->id }}"
                                                target="_blank" class="btn btn-sm btn-light">

                                                <i class="mdi mdi-download"></i>

                                                Download

                                            </a>

                                        </div>

                                    </div>
                                @endif

                                <hr>

                                {{-- COMMITTEE --}}

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Committee Approval Sequence
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div id="committee-container">

                                        {{-- JIKA SUDAH ADA COMMITTEE SEBELUMNYA --}}

                                        @forelse ($requestDocument->committees as $index => $selectedCommittee)
                                            <div class="committee-row mb-2">
                                                <div class="row align-items-center">

                                                    {{-- SELECT COMMITTEE --}}

                                                    <div class="col-md-10">
                                                        <select name="committee[]" class="form-select committee-select">
                                                            <option value="">
                                                                Pilih Committee
                                                            </option>
                                                            @foreach ($committee as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ (string) $selectedCommittee->committee_id === (string) $item->id ? 'selected' : '' }}>

                                                                    {{ $item->name }}

                                                                    @if ($item->title)
                                                                        - {{ $item->title }}
                                                                    @endif

                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                    {{-- ACTION --}}
                                                    <div class="col-md-2">
                                                        <div class="d-flex gap-1">
                                                            {{-- ADD --}}
                                                            @if ($index === 0)
                                                                <button type="button"
                                                                    class="btn btn-primary btn-add-committee">

                                                                    <i class="fas fa-plus"></i>

                                                                </button>
                                                            @endif

                                                            {{-- DELETE --}}
                                                            @if ($index !== 0)
                                                                <button type="button"
                                                                    class="btn btn-danger btn-remove-committee">

                                                                    <i class="fas fa-trash"></i>

                                                                </button>
                                                            @endif

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        @empty

                                            {{-- JIKA BELUM ADA COMMITTEE --}}
                                            <div class="committee-row mb-2">
                                                <div class="row align-items-center">
                                                    <div class="col-md-1 text-center">
                                                        <span class="badge bg-primary sequence-number">
                                                            1
                                                        </span>

                                                    </div>

                                                    <div class="col-md-9">

                                                        <select name="committee[]" class="form-select committee-select">

                                                            <option value="">

                                                                Pilih Committee

                                                            </option>


                                                            @foreach ($committee as $item)
                                                                <option value="{{ $item->id }}">

                                                                    {{ $item->name }}

                                                                    @if ($item->title)
                                                                        - {{ $item->title }}
                                                                    @endif

                                                                </option>
                                                            @endforeach

                                                        </select>

                                                    </div>


                                                    <div class="col-md-2">

                                                        <button type="button" class="btn btn-primary btn-add-committee">

                                                            <i class="fas fa-plus"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>
                                        @endforelse


                                    </div>


                                    @error('committee')
                                        <div class="text-danger small mt-1">

                                            {{ $message }}

                                        </div>
                                    @enderror

                                </div>

                                {{-- ACTION --}}

                                <div class="mt-4 text-end">

                                    <a href="{{ route('tsp.request-document') }}" class="btn btn-secondary">

                                        Cancel

                                    </a>


                                    <button type="submit" class="btn btn-primary" id="btn-submit-legal-drafting">

                                        <i class="fas fa-save me-1"></i>

                                        Submit Draft Contract

                                    </button>

                                </div>


                            </form>

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

            /*INITIALIZE SELECT2*/

            function initializeSelect2(element) {

                $(element).select2({

                    placeholder: 'Pilih Committee',

                    allowClear: true,

                    width: '100%'

                });

            }


            /*INITIAL SELECT2*/

            $('.committee-select').each(function() {

                initializeSelect2(this);

            });


            /*ADD COMMITTEE*/

            $(document).on('click', '.btn-add-committee', function() {

                const newRow = `

                <div class="committee-row mb-2">

                    <div class="row align-items-center">

                        <div class="col-md-10">

                            <select
                                name="committee[]"
                                class="form-select committee-select">

                                <option value="">
                                    Pilih Committee
                                </option>

                                @foreach ($committee as $item)

                                    <option value="{{ $item->id }}">

                                        {{ $item->name }}

                                        @if ($item->title)
                                            - {{ $item->title }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-2">

                            <div class="d-flex gap-1">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-remove-committee">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            `;


                $('#committee-container').append(newRow);
                updateCommitteeSequence();


                /*INITIALIZE SELECT2 BARU*/

                const newSelect = $('#committee-container')
                    .find('.committee-select')
                    .last();

                initializeSelect2(newSelect);

                updateCommitteeOptions();

            });


            /*DELETE COMMITTEE*/

            $(document).on(
                'click',
                '.btn-remove-committee',
                function() {

                    const row = $(this)
                        .closest('.committee-row');


                    /* DESTROY SELECT2*/

                    const select = row.find(
                        '.committee-select'
                    );

                    if (select.hasClass(
                            'select2-hidden-accessible'
                        )) {

                        select.select2('destroy');

                    }

                    /*REMOVE ROW*/

                    row.remove();
                    updateCommitteeSequence();
                    updateCommitteeOptions();

                }
            );

            function updateCommitteeSequence() {

                $('#committee-container .committee-row').each(
                    function(index) {

                        $(this)
                            .find('.sequence-number')
                            .text(index + 1);

                    }
                );

            }

            function updateCommitteeOptions() {

                /*AMBIL SEMUA COMMITTEE YANG SUDAH DIPILIH */

                const selectedValues = [];

                $('.committee-select').each(function() {

                    const value = $(this).val();

                    if (value) {

                        selectedValues.push(value);

                    }

                });


                /*UPDATE SETIAP SELECT*/

                $('.committee-select').each(function() {

                    const currentSelect = $(this);

                    const currentValue = currentSelect.val();


                    currentSelect.find('option').each(function() {

                        const option = $(this);

                        const value = option.val();


                        /*JANGAN DISABLE OPTION DEFAULT*/

                        if (!value) {

                            return;

                        }


                        /*JIKA SUDAH DIPILIH DI SELECT LAIN*/

                        if (
                            selectedValues.includes(value) &&
                            value !== currentValue
                        ) {

                            option.prop('disabled', true);

                        } else {

                            option.prop('disabled', false);

                        }

                    });


                    /*REFRESH SELECT2*/

                    currentSelect.trigger('change.select2');

                });

            }

            /* UPDATE COMMITTEE OPTIONS KETIKA ADA PERUBAHAN PADA SELECT */
            $(document).on(
                'change',
                '.committee-select',
                function() {

                    updateCommitteeOptions();

                }
            );

            // Prevent accidental form submission when pressing Enter
            $("#legal-drafting-form").on("keydown", function(e) {
                if (e.key === "Enter" && e.target.tagName !== "TEXTAREA") {
                    e.preventDefault();
                }
            });

            // Disable submit button after form submission to prevent multiple submissions
            /*ERROR HANDLING*/

            function showError(input, message) {
                const $input = $(input);

                $input.addClass('is-invalid');

                let $error;

                if ($input.hasClass('select2-hidden-accessible')) {

                    const $select2 = $input.next('.select2-container');

                    $select2.find('.select2-selection').addClass('is-invalid');

                    $error = $select2.next('.client-error');

                    if (!$error.length) {
                        $error = $('<div class="invalid-feedback d-block client-error"></div>');
                        $select2.after($error);
                    }

                } else {

                    $error = $input.next('.client-error');

                    if (!$error.length) {
                        $error = $('<div class="invalid-feedback d-block client-error"></div>');
                        $input.after($error);
                    }
                }

                $error.text(message).show();
            }


            function clearError(input) {
                const $input = $(input);

                $input.removeClass('is-invalid');

                if ($input.hasClass('select2-hidden-accessible')) {

                    const $select2 = $input.next('.select2-container');

                    $select2.find('.select2-selection').removeClass('is-invalid');
                    $select2.next('.client-error').remove();

                } else {

                    $input.next('.client-error').remove();
                }
            }


            /*ATTACHMENT CONFIGURATION*/

            const attachmentConfig = {
                id: '#draft_contract',
                label: 'Draft Contract',
                prefix: 'draft contract'
            };


            /*ATTACHMENT VALIDATION*/

            function validateAttachment(input, config) {

                const file = input.files?.[0];

                // Required
                if (!file) {
                    showError(input, `${config.label} is required.`);
                    return false;
                }

                const fileName = file.name.trim().toLowerCase();
                const prefix = config.prefix.toLowerCase();

                // Prefix validation
                if (!fileName.startsWith(prefix)) {
                    showError(
                        input,
                        `File name ${config.label} must start with "${config.prefix}".`
                    );
                    return false;
                }

                // PDF validation
                if (
                    !fileName.endsWith('.pdf') ||
                    (file.type && file.type !== 'application/pdf')
                ) {
                    showError(input, `${config.label} must be a PDF file.`);
                    return false;
                }

                // Maximum 10 MB
                if (file.size > 10 * 1024 * 1024) {
                    showError(input, `${config.label} must be at most 10 MB.`);
                    return false;
                }

                clearError(input);

                return true;
            }


            /*VALIDATE WHEN FILE SELECTED*/

            $(attachmentConfig.id).on('change', function() {
                validateAttachment(this, attachmentConfig);
            });


            /*COMMITTEE VALIDATION*/

            function validateCommittee() {

                let valid = true;

                const selectedValues = [];

                const $committee = $('.committee-select');

                // Minimal satu committee
                if ($committee.length === 0) {
                    valid = false;
                    return valid;
                }

                // Clear previous errors
                $committee.each(function() {
                    clearError(this);
                });

                // Required validation
                $committee.each(function() {

                    const value = $(this).val();

                    if (!value) {

                        showError(this, 'Committee is required.');

                        valid = false;

                    } else {

                        selectedValues.push({
                            input: this,
                            value: value.toString()
                        });

                    }
                });

                // Duplicate validation
                const valueCounts = {};

                selectedValues.forEach(item => {
                    valueCounts[item.value] = (valueCounts[item.value] || 0) + 1;
                });

                selectedValues.forEach(item => {

                    if (valueCounts[item.value] > 1) {

                        showError(
                            item.input,
                            'This committee has already been selected.'
                        );

                        valid = false;
                    }
                });

                return valid;
            }


            /*CLEAR COMMITTEE ERROR WHEN CHANGED*/

            $(document).on('change', '.committee-select', function() {

                clearError(this);

            });


            /*FORM SUBMIT VALIDATION*/

            $('#legal-drafting-form').on('submit', function(event) {

                const form = this;
                const $form = $(this);

                const submitter = event.originalEvent?.submitter;

                let valid = true;

                // 1. Validate Draft Contract
                if (!validateAttachment(
                        $(attachmentConfig.id)[0],
                        attachmentConfig
                    )) {
                    valid = false;
                }

                // 2. Validate Committee
                if (!validateCommittee()) {
                    valid = false;
                }

                // 3. Stop submission if invalid
                if (!valid) {

                    event.preventDefault();
                    event.stopPropagation();

                    const firstError = $form.find('.is-invalid').first();

                    if (firstError.length) {

                        const input = firstError[0];

                        input.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        if (!firstError.hasClass('select2-hidden-accessible')) {
                            input.focus({
                                preventScroll: true
                            });
                        } else {
                            firstError.select2('open');
                        }
                    }

                    return false;
                }

                // 4. All valid - disable submit button
                $form.find('button[type="submit"]').prop('disabled', true);

                if (submitter) {

                    $(submitter).html(`
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Processing...
                    `);

                }

                return true;

            });
        });
    </script>
@endsection
