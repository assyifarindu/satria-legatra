@extends('layouts.tsp_master')

@section('title')
    Legal Drafting |
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
                            Legal Drafting
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

                                    {{ $requestDocument->document_number ?? '-' }}

                                    -

                                    {{ $requestDocument->title }}

                                </h4>

                                <p class="text-muted mb-0">

                                    Legal Drafting Request Document

                                </p>

                            </div>


                            <form id="legal-drafting-form"
                                action="{{ url('tsp/request-document/legal-drafting/' . $requestDocument->id) }}"
                                method="POST" enctype="multipart/form-data">

                                @csrf

                                {{-- DRAFT CONTRACT --}}

                                <div class="mb-4">

                                    <label for="draft_contract" class="form-label fw-bold">

                                        Draft Contract

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input type="file" class="form-control @error('draft_contract') is-invalid @enderror"
                                        id="draft_contract" name="draft_contract" accept=".pdf">


                                    <div class="form-text">

                                        Format PDF, maksimal 10 MB.

                                    </div>
                                    @error('draft_contract')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>



                                <hr>

                                {{-- COMMITTEE --}}

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Committee Approval Sequence
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div id="committee-container">

                                        <div class="committee-row mb-2">

                                            <div class="row align-items-center">

                                                <div class="col-md-10">

                                                    <select name="committee[]"
                                                        class="form-select committee-select @error('committee') is-invalid @enderror">

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

                                    </div>

                                    @error('committee')
                                        <div class="invalid-feedback">
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

        });

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
    </script>
@endsection
