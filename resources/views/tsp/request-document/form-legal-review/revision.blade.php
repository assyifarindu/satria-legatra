@extends('layouts.tsp_master')

@section('title')
    Request Document |
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
                        </div>
                        <h4 class="page-title">Form Legal Review</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="form-legal-review-revision" class="d-flex flex-column gap-2"
                                action="{{ route('tsp.request-document.form-legal-review.store-revision-form-legal-review', $requestDocument->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                            
                                <h4 class="header-title mb-3">Form Revision Legal Review</h4>

                                <div class="row">
                                    <div class="col-4">
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

                                    <div class="col-4">
                                        <label for="title" class="fw-bold d-block">
                                            Title
                                        </label>

                                        <input type="text" name="title" id="title"
                                            value="{{ $requestDocument->title ?? old('title') }}"
                                            class="form-control @error('title') is-invalid @enderror" placeholder="Title"
                                            disabled>

                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4">
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
                                </div>

                                <div class="row">
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
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <label for="pic" class="fw-bold d-block">
                                            PIC Document
                                        </label>

                                        <input type="text" name="pic" id="pic"
                                            value="{{ $pic->name }}"
                                            class="form-control @error('pic') is-invalid @enderror" placeholder="PIC Document" readonly>

                                        @error('pic')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4">
                                        <label for="department" class="fw-bold d-block">
                                            Department
                                        </label>

                                        <select name="department" class="form-select @error('department') is-invalid @enderror">
                                            <option value="">Select Department</option>
                                            @foreach ($department as $item)
                                                <option 
                                                    value="{{ $item['name'] }}"
                                                    {{ ($flr->department ?? old('department')) == $item['name'] ? 'selected' : '' }}
                                                >
                                                    {{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('department')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4">
                                        <label for="party_name" class="fw-bold d-block">
                                            Party Name
                                        </label>

                                        <input type="text" name="party_name" id="party_name"
                                            value="{{ $requestDocument->customer->name ?? old('party_name') }}"
                                            class="form-control @error('party_name') is-invalid @enderror"
                                            placeholder="Party Name" readonly>

                                        @error('party_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <label for="period_time" class="fw-bold d-block">
                                            Period Time
                                        </label>

                                        <input type="text" name="period_time" id="period_time"
                                            value="{{ $flr->period_time ?? old('period_time') }}"
                                            class="form-control @error('period_time') is-invalid @enderror"
                                            placeholder="Period Time">

                                        @error('period_time')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-4">
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

                                    <div class="col-4">
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

                                <div class="row">
                                    <div class="col-6">
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

                                    <div class="col-6">
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

                                <div class="row">
                                    <div class="col-6">
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

                                    <div class="col-6">
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
                                </div>

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
            $('#form-legal-review-revision').on('submit', function(event) {
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
        })
    </script>
@endsection
