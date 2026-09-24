@extends('layouts.tsp_master')

@section('title')
    Request Document |
@endsection

@section('css')
    <style>
        .step-line {
            position: absolute;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 2px;
            background-color: #6c757d;
            z-index: 0 !important;
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
                        </div>
                        <h4 class="page-title">Form Legal Review</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="form-legal-review-create" class="d-flex flex-column gap-2"
                                action="{{ route('tsp.request-document.store-create-form-legal-review', $requestDocument->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <h4 class="header-title mb-3">Form Create Legal Review</h4>

                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <div class="position-relative pe-2 d-flex flex-column align-items-center">
                                        <button type="button"
                                            class="btn rounded-circle d-flex align-items-center justify-content-center btn-primary step-button active"
                                            style="z-index: 1;" data-step="1">1</button>
                                        <p>Step 1</p>
                                        <div class="step-line"></div>
                                    </div>
                                    <div class="position-relative px-2 d-flex flex-column align-items-center">
                                        <button type="button"
                                            class="btn rounded-circle d-flex align-items-center justify-content-center btn-secondary step-button"
                                            style="z-index: 1;" data-step="2">2</button>
                                        <p>Step 2</p>
                                        <div class="step-line"></div>
                                    </div>
                                    <div class="position-relative px-2 d-flex flex-column align-items-center">
                                        <button type="button"
                                            class="btn rounded-circle d-flex align-items-center justify-content-center btn-secondary step-button"
                                            style="z-index: 1;" data-step="3">3</button>
                                        <p>Step 3</p>
                                        <div class="step-line"></div>
                                    </div>
                                    <div class="position-relative ps-2 d-flex flex-column align-items-center">
                                        <button type="button"
                                            class="btn rounded-circle d-flex align-items-center justify-content-center btn-secondary step-button"
                                            style="z-index: 1;" data-step="4">4</button>
                                        <p>Step 4</p>
                                        <div class="step-line"></div>
                                    </div>
                                </div>

                                <fieldset class="border p-2 rounded-3">
                                    <legend class="fs-5">Document Information</legend>

                                    <div class="d-flex flex-column gap-2">
                                        <div>
                                            <label for="document_number" class="fw-bold d-block">
                                                Document Number
                                            </label>

                                            <input type="text" name="document_number" id="document_number"
                                                value="{{ $requestDocument->document_number }}"
                                                class="form-control @error('document_number') is-invalid @enderror"
                                                placeholder="Document Number" readonly>

                                            @error('document_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="title" class="fw-bold d-block">
                                                Title
                                            </label>

                                            <input type="text" name="title" id="title"
                                                value="{{ $requestDocument->title ?? old('title') }}"
                                                class="form-control @error('title') is-invalid @enderror"
                                                placeholder="Title" disabled>

                                            @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="date" class="fw-bold d-block">
                                                Date
                                            </label>

                                            <input type="date" name="date" id="date"
                                                value="{{ $flr->date ?? old('date') }}"
                                                class="form-control @error('date') is-invalid @enderror" placeholder="Date">
                                            @error('date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="document_objective" class="fw-bold d-block">
                                                Document Objective
                                            </label>

                                            <textarea name="document_objective" id="document_objective"
                                                value="{{ $flr->document_objective ?? old('document_objective') }}"
                                                class="form-control @error('document_objective') is-invalid @enderror" placeholder="Document Objective">{{ $flr->document_objective ?? old('document_objective') }}</textarea>

                                            @error('document_objective')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="pic" class="fw-bold d-block">
                                                PIC Document
                                            </label>

                                            <input type="text" name="pic" id="pic" value="{{ $pic->name }}"
                                                class="form-control @error('pic') is-invalid @enderror"
                                                placeholder="PIC Document" readonly>

                                            @error('pic')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="department" class="fw-bold d-block">
                                                Department
                                            </label>

                                            <select name="department"
                                                class="form-select @error('department') is-invalid @enderror">
                                                <option value="">Select Department</option>
                                                @foreach ($department as $item)
                                                    <option value="{{ $item['name'] }}"
                                                        {{ ($flr->department ?? old('department')) == $item['name'] ? 'selected' : '' }}>
                                                        {{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="text" name="department" id="department" value="{{ old('department') }}"
                                                class="form-control d-none @error('department') is-invalid @enderror"
                                                placeholder="Department" readonly> --}}

                                            @error('department')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="party_name" class="fw-bold d-block">
                                                Party Name
                                            </label>

                                            <input type="text" name="party_name" id="party_name"
                                                value="{{ $requestDocument->customer->name }}"
                                                class="form-control @error('party_name') is-invalid @enderror"
                                                placeholder="Party Name" readonly>

                                            @error('party_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-2">
                                        <button type="button" class="btn btn-primary next-button">Next</button>
                                    </div>
                                </fieldset>

                                <fieldset class="border p-2 rounded-3">
                                    <legend class="fs-5">Period & Location</legend>

                                    <div class="d-flex flex-column gap-2">
                                        <div class="row">
                                            <label for="" class="fw-bold d-block mb-1">
                                                Period Time
                                            </label>
                                            <div class="col-md-6">
                                                <label for="start_date">
                                                    Start Date
                                                </label>

                                                <input type="date" name="start_date" id="start_date"
                                                    value="{{ $flr->start_date ?? old('start_date') }}"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    placeholder="Start Date">

                                                @error('start_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="end_date">
                                                    End Date
                                                </label>

                                                <input type="date" name="end_date" id="end_date"
                                                    value="{{ $flr->end_date ?? old('end_date') }}"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    placeholder="End Date"
                                                    min="{{ $flr->start_date ?? old('start_date') }}">

                                                @error('end_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="incoterm" class="fw-bold d-block">
                                                    Incoterms
                                                </label>

                                                <input type="text" name="incoterm" id="incoterm"
                                                    value="{{ $flr->incoterm ?? old('incoterm') }}"
                                                    class="form-control @error('incoterm') is-invalid @enderror"
                                                    placeholder="Incoterms">

                                                @error('incoterm')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="term_of_payment" class="fw-bold d-block">
                                                    TOP
                                                </label>

                                                <input type="text" name="term_of_payment" id="term_of_payment"
                                                    value="{{ $flr->term_of_payment ?? old('term_of_payment') }}"
                                                    class="form-control @error('term_of_payment') is-invalid @enderror"
                                                    placeholder="TOP">

                                                @error('term_of_payment')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <label for="work_location" class="fw-bold d-block">
                                                Work Location
                                            </label>

                                            <input type="text" name="work_location" id="work_location"
                                                value="{{ $flr->work_location ?? old('work_location') }}"
                                                class="form-control @error('work_location') is-invalid @enderror"
                                                placeholder="Work Location">

                                            @error('work_location')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="delivery_location" class="fw-bold d-block">
                                                Delivery Location
                                            </label>

                                            <input type="text" name="delivery_location" id="delivery_location"
                                                value="{{ $flr->delivery_location ?? old('delivery_location') }}"
                                                class="form-control @error('delivery_location') is-invalid @enderror"
                                                placeholder="Delivery Location">

                                            @error('delivery_location')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-2">
                                        <button type="button" class="btn btn-secondary prev-button">Prev</button>
                                        <button type="button" class="btn btn-primary next-button">Next</button>
                                    </div>

                                </fieldset>

                                <fieldset class="border p-2 rounded-3">
                                    <legend class="fs-5">Contract Overview</legend>

                                    <div class="d-flex flex-column gap-2">
                                        <div>
                                            <label for="resume" class="fw-bold d-block">
                                                Resume
                                            </label>

                                            <textarea name="resume" id="resume" value="{{ $flr->resume ?? old('resume') }}"
                                                class="form-control @error('resume') is-invalid @enderror" placeholder="Resume">{{ $flr->resume ?? old('resume') }}</textarea>

                                            @error('resume')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="legal_note" class="fw-bold d-block">
                                                Legal Note
                                            </label>

                                            <textarea name="legal_note" id="legal_note" value="{{ $flr->legal_note ?? old('legal_note') }}"
                                                class="form-control @error('legal_note') is-invalid @enderror" placeholder="Legal Note">{{ $flr->legal_note ?? old('legal_note') }}</textarea>

                                            @error('legal_note')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="validation_required_by" class="fw-bold d-block">
                                                Validation Required By
                                            </label>

                                            <input type="text" name="validation_required_by"
                                                id="validation_required_by"
                                                value="{{ $flr->validation_required_by ?? old('validation_required_by') }}"
                                                class="form-control @error('validation_required_by') is-invalid @enderror"
                                                placeholder="Validation Required By">

                                            @error('validation_required_by')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="investment" class="fw-bold d-block">
                                                Investment
                                            </label>

                                            <textarea name="investment" id="investment" value="{{ $flr->investment ?? old('investment') }}"
                                                class="form-control @error('investment') is-invalid @enderror" placeholder="Investment">{{ $flr->investment ?? old('investment') }}</textarea>
                                            @error('investment')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="manpower_provision" class="fw-bold d-block">
                                                Manpower Provision
                                            </label>

                                            <textarea name="manpower_provision" id="manpower_provision"
                                                value="{{ $flr->manpower_provision ?? old('manpower_provision') }}"
                                                class="form-control @error('manpower_provision') is-invalid @enderror" placeholder="Manpower Provision">{{ $flr->manpower_provision ?? old('manpower_provision') }}</textarea>

                                            @error('manpower_provision')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-2">
                                        <button type="button" class="btn btn-secondary prev-button">Prev</button>
                                        <button type="button" class="btn btn-primary next-button">Next</button>
                                    </div>

                                </fieldset>

                                <fieldset class="border p-2 rounded-3">
                                    <legend class="fs-5">Legal & Service Terms</legend>

                                    <div class="d-flex flex-column gap-2">
                                        <div>
                                            <label for="sanction" class="fw-bold d-block">
                                                Sanction
                                            </label>

                                            <textarea name="sanction" id="sanction" value="{{ $flr->sanction ?? old('sanction') }}"
                                                class="form-control @error('sanction') is-invalid @enderror" placeholder="Sanction">{{ $flr->sanction ?? old('sanction') }}</textarea>
                                            @error('sanction')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="penalty" class="fw-bold d-block">
                                                Penalty
                                            </label>

                                            <textarea name="penalty" id="penalty" value="{{ $flr->penalty ?? old('penalty') }}"
                                                class="form-control @error('penalty') is-invalid @enderror" placeholder="Penalty">{{ old('penalty') }}</textarea>

                                            @error('penalty')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="insurance" class="fw-bold d-block">
                                                Insurance
                                            </label>

                                            <textarea name="insurance" id="insurance" value="{{ $flr->insurance ?? old('insurance') }}"
                                                class="form-control @error('insurance') is-invalid @enderror" placeholder="Insurance">{{ old('insurance') }}</textarea>
                                            @error('insurance')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="sla" class="fw-bold d-block">
                                                SLA
                                            </label>

                                            <textarea name="sla" id="sla" value="{{ $flr->sla ?? old('sla') }}"
                                                class="form-control @error('sla') is-invalid @enderror" placeholder="SLA">{{ old('sla') }}</textarea>

                                            @error('sla')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end mt-2">
                                        <button type="button" class="btn btn-secondary prev-button">Prev</button>
                                    </div>

                                </fieldset>


                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" value="submit" class="btn btn-primary">
                                        Submit
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

            $("select[name='department']").select2({
                placeholder: "Select Department",
                width: '100%',
                allowClear: true
            });

            // Disable submit button after form submission to prevent double submission
            $('#form-legal-review-create').on('submit', function(event) {
                const form = $(this);

                // Disable semua tombol submit
                form.find('button[type="submit"]').prop('disabled', true);

                // Button yang diklik
                const submitter = event.originalEvent.submitter;

                if (submitter) {
                    $(submitter).html(`
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Processing...
                    `);
                }
            });

            const fieldsets = document.querySelectorAll('fieldset');
            let currentStep = 0;
            fieldsets.forEach((fieldset, index) => {
                if (index !== currentStep) {
                    fieldset.style.display = 'none';
                }
            });
            $('.next-button').on('click', function() {
                if (currentStep < fieldsets.length - 1) {
                    fieldsets[currentStep].style.display = 'none';
                    currentStep++;
                    fieldsets[currentStep].style.display = 'block';
                    $('.step-button').removeClass('active').addClass('btn-secondary').removeClass(
                        'btn-primary');
                    $(`.step-button[data-step="${currentStep + 1}"]`).addClass('active').removeClass(
                        'btn-secondary').addClass('btn-primary');
                }
            });
            $('.prev-button').on('click', function() {
                if (currentStep > 0) {
                    fieldsets[currentStep].style.display = 'none';
                    currentStep--;
                    fieldsets[currentStep].style.display = 'block';
                    $('.step-button').removeClass('active').addClass('btn-secondary').removeClass(
                        'btn-primary');
                    $(`.step-button[data-step="${currentStep + 1}"]`).addClass('active').removeClass(
                        'btn-secondary').addClass('btn-primary');
                }
            });
            $('.step-button').on('click', function() {
                const step = parseInt($(this).data('step')) - 1;
                if (step >= 0 && step < fieldsets.length) {
                    fieldsets[currentStep].style.display = 'none';
                    currentStep = step;
                    fieldsets[currentStep].style.display = 'block';
                    $('.step-button').removeClass('active').addClass('btn-secondary').removeClass(
                        'btn-primary');
                    $(this).addClass('active').removeClass('btn-secondary').addClass('btn-primary');
                }
            });
        })
    </script>
@endsection
