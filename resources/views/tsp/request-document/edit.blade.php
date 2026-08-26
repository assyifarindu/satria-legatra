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
                        <h4 class="page-title">Request Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Form Update Request Document</h4>
                            <br><br>
                            <form id="form-request-document" class="d-flex flex-column gap-2"
                                action="{{ route('tsp.request-document.edit', $requestDocument->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="title" class="fw-bold d-block">
                                        Title
                                    </label>

                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $requestDocument->title) }}"
                                        class="form-control @error('title') is-invalid @enderror" placeholder="Title">

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="customer_name" class="fw-bold d-block">
                                        Customer Name
                                    </label>

                                    <select name="customer_id"
                                        class="form-select @error('customer_id') is-invalid @enderror">
                                        <option value="">Select Customer</option>
                                        @if ($requestDocument->customer)
                                            <option value="{{ $requestDocument->customer->id }}" selected>
                                                {{ $requestDocument->customer->name }}
                                            </option>
                                        @endif
                                    </select>
                                    <input type="text" name="customer_name" id="customer_name"
                                        value="{{ old('customer_name', $requestDocument->customer->name ?? '') }}"
                                        class="form-control d-none @error('customer_name') is-invalid @enderror"
                                        placeholder="Customer Name" readonly>

                                    @error('customer_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="customer_nib" class="fw-bold d-block">
                                            Customer NIB
                                        </label>

                                        <input type="text" name="customer_nib" id="customer_nib"
                                            value="{{ old('customer_nib', $requestDocument->customer->nib ?? '') }}"
                                            class="form-control @error('customer_nib') is-invalid @enderror"
                                            placeholder="Customer NIB" readonly>

                                        @error('customer_nib')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="customer_npwp" class="fw-bold d-block">
                                            Customer NPWP
                                        </label>

                                        <input type="text" name="customer_npwp" id="customer_npwp"
                                            value="{{ old('customer_npwp', $requestDocument->customer->npwp ?? '') }}"
                                            class="form-control @error('customer_npwp') is-invalid @enderror"
                                            placeholder="Customer NPWP" readonly>

                                        @error('customer_npwp')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="customer_address" class="fw-bold d-block">
                                        Customer Address
                                    </label>

                                    <input type="text" name="customer_address" id="customer_address"
                                        value="{{ old('customer_address', $requestDocument->customer->address ?? '') }}"
                                        class="form-control @error('customer_address') is-invalid @enderror"
                                        placeholder="Customer Address" readonly>

                                    @error('customer_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="customer_postal_code" class="fw-bold d-block">
                                            Customer Postal Code
                                        </label>

                                        <input type="text" name="customer_postal_code" id="customer_postal_code"
                                            value="{{ old('customer_postal_code', $requestDocument->customer->postal_code ?? '') }}"
                                            class="form-control @error('customer_postal_code') is-invalid @enderror"
                                            placeholder="Customer Postal Code" readonly>

                                        @error('customer_postal_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="customer_email" class="fw-bold d-block">
                                            Customer Email
                                        </label>

                                        <input type="text" name="customer_email" id="customer_email"
                                            value="{{ old('customer_email', $requestDocument->customer->email ?? '') }}"
                                            class="form-control @error('customer_email') is-invalid @enderror"
                                            placeholder="Customer Email" readonly>

                                        @error('customer_email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    @php
                                        $customer = $requestDocument->customer ?? null;

                                        $customerPic = null;

                                        if ($customer && $customer->customerPics->isNotEmpty()) {
                                            $customerPic = $customer->customerPics->first();
                                        }
                                    @endphp
                                    <label for="customer_pic_name" class="fw-bold d-block">
                                        Customer PIC Name
                                    </label>

                                    <input type="text" name="customer_pic_name" id="customer_pic_name"
                                        value="{{ old('customer_pic_name', $customerPic->name ?? '') }}"
                                        class="form-control @error('customer_pic_name') is-invalid @enderror"
                                        placeholder="Customer PIC Name">

                                    @error('customer_pic_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_pic_position" class="fw-bold d-block">
                                        Customer PIC Position
                                    </label>

                                    <input type="text" name="customer_pic_position" id="customer_pic_position"
                                        value="{{ old('customer_pic_position', $customerPic->position ?? '') }}"
                                        class="form-control @error('customer_pic_position') is-invalid @enderror"
                                        placeholder="Customer PIC Position">
                                    @error('customer_pic_position')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="customer_pic_email" class="fw-bold d-block">
                                            Customer PIC Email
                                        </label>

                                        <input type="text" name="customer_pic_email" id="customer_pic_email"
                                            value="{{ old('customer_pic_email', $customerPic->email ?? '') }}"
                                            class="form-control @error('customer_pic_email') is-invalid @enderror"
                                            placeholder="Customer PIC Email">
                                        @error('customer_pic_email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="customer_pic_phone" class="fw-bold d-block">
                                            Customer PIC Phone
                                        </label>

                                        <input type="text" name="customer_pic_phone" id="customer_pic_phone"
                                            value="{{ old('customer_pic_phone', $customerPic->phone ?? '') }}"
                                            class="form-control @error('customer_pic_phone') is-invalid @enderror"
                                            placeholder="Customer PIC Phone">

                                        @error('customer_pic_phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="contract_type" class="fw-bold d-block">
                                        Contract Type
                                    </label>

                                    <select name="contract_type"
                                        class="form-select @error('contract_type') is-invalid @enderror">
                                        <option value="">Select Contract Type</option>
                                        <option value="Part"
                                            {{ old('contract_type', $requestDocument->contract_type) == 'Part' ? 'selected' : '' }}>
                                            Part
                                        </option>
                                        <option value="Service"
                                            {{ old('contract_type', $requestDocument->contract_type) == 'Service' ? 'selected' : '' }}>
                                            Service</option>
                                        <option value="Reman"
                                            {{ old('contract_type', $requestDocument->contract_type) == 'Reman' ? 'selected' : '' }}>
                                            Reman</option>
                                        <option value="Unit"
                                            {{ old('contract_type', $requestDocument->contract_type) == 'Unit' ? 'selected' : '' }}>
                                            Unit
                                        </option>
                                    </select>

                                    @error('contract_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="potential_amount_display" class="fw-bold d-block">
                                        Potential Amount
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>

                                        <input type="text" id="potential_amount_display"
                                            value="{{ old('potential_amount', $requestDocument->potential_amount) ? number_format((int) old('potential_amount', $requestDocument->potential_amount), 0, ',', '.') : '' }}"
                                            class="form-control @error('potential_amount') is-invalid @enderror"
                                            placeholder="Potential Amount" inputmode="numeric"
                                            oninput="
                                                this.value = this.value.replace(/\D/g,'').replace(/\B(?=(\d{3})+(?!\d))/g,'.');
                                                document.getElementById('potential_amount').value = this.value.replace(/\./g,'');
                                            ">

                                        <input type="hidden" name="potential_amount" id="potential_amount"
                                            value="{{ old('potential_amount', $requestDocument->potential_amount) }}">
                                    </div>

                                    @error('potential_amount')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="sign_status" class="fw-bold d-block">
                                        Sign Status
                                    </label>

                                    <select name="sign_status"
                                        class="form-select @error('sign_status') is-invalid @enderror">
                                        <option value="">Select Sign Status</option>
                                        <option value="Not Signed"
                                            {{ old('sign_status', $requestDocument->sign_status) == 'Not Signed' ? 'selected' : '' }}>
                                            Not Signed
                                        </option>
                                        <option value="Partial Signed"
                                            {{ old('sign_status', $requestDocument->sign_status) == 'Partial Signed' ? 'selected' : '' }}>
                                            Partial Signed
                                        </option>
                                        <option value="Fully Signed"
                                            {{ old('sign_status', $requestDocument->sign_status) == 'Fully Signed' ? 'selected' : '' }}>
                                            Fully Signed
                                        </option>
                                    </select>

                                    @error('sign_status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="is_project" class="fw-bold d-block">
                                        Project Category
                                    </label>

                                    <select name="is_project"
                                        class="form-select @error('is_project') is-invalid @enderror">
                                        <option value="">Select Project Category</option>
                                        <option value="1"
                                            {{ old('is_project', $requestDocument->is_project) == '1' ? 'selected' : '' }}>
                                            Project</option>
                                        <option value="0"
                                            {{ old('is_project', $requestDocument->is_project) == '0' ? 'selected' : '' }}>
                                            Non-Project
                                        </option>
                                    </select>

                                    @error('is_project')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sow" class="fw-bold d-block">
                                        SOW
                                    </label>

                                    <textarea name="sow" id="sow" value="{{ old('sow', $requestDocument->sow) }}"
                                        class="form-control @error('sow') is-invalid @enderror" placeholder="SOW">{{ old('sow', $requestDocument->sow) }}</textarea>

                                    @error('sow')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="transaction_procedure" class="fw-bold d-block">
                                        Transaction Procedure
                                    </label>

                                    <textarea name="transaction_procedure" id="transaction_procedure"
                                        value="{{ old('transaction_procedure', $requestDocument->transaction_procedure) }}"
                                        class="form-control @error('transaction_procedure') is-invalid @enderror" placeholder="Transaction Procedure">{{ old('transaction_procedure', $requestDocument->transaction_procedure) }}</textarea>

                                    @error('transaction_procedure')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="kpi" class="fw-bold d-block">
                                        KPI
                                    </label>

                                    <textarea name="kpi" id="kpi" value="{{ old('kpi', $requestDocument->kpi) }}"
                                        class="form-control @error('kpi') is-invalid @enderror" placeholder="KPI">{{ old('kpi', $requestDocument->kpi) }}</textarea>

                                    @error('kpi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- @php
                                    $pic = $requestDocument->pics ?? null;

                                    $pics = null;

                                    if ($pic && $pic->isNotEmpty()) {
                                        $pics = $pic->first();
                                    }
                                @endphp --}}

                                <div>
                                    <label for="pic_name" class="fw-bold d-block">
                                        PIC Name
                                    </label>

                                    <input type="text" name="pic_name" id="pic_name"
                                        value="{{ old('pic_name', $requestDocument->pics->name ?? '') }}"
                                        class="form-control @error('pic_name') is-invalid @enderror"
                                        placeholder="PIC Name">

                                    @error('pic_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pic_position" class="fw-bold d-block">
                                        PIC Position
                                    </label>

                                    <input type="text" name="pic_position" id="pic_position"
                                        value="{{ old('pic_position', $requestDocument->pics->position ?? '') }}"
                                        class="form-control @error('pic_position') is-invalid @enderror"
                                        placeholder="PIC Position">
                                    @error('pic_position')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="pic_email" class="fw-bold d-block">
                                            PIC Email
                                        </label>

                                        <input type="text" name="pic_email" id="pic_email"
                                            value="{{ old('pic_email', $requestDocument->pics->email ?? '') }}"
                                            class="form-control @error('pic_email') is-invalid @enderror"
                                            placeholder="PIC Email">
                                        @error('pic_email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pic_phone" class="fw-bold d-block">
                                            PIC Phone
                                        </label>

                                        <input type="text" name="pic_phone" id="pic_phone"
                                            value="{{ old('pic_phone', $requestDocument->pics->phone ?? '') }}"
                                            class="form-control @error('pic_phone') is-invalid @enderror"
                                            placeholder="PIC Phone">

                                        @error('pic_phone')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-warning alert-dismissible fade show mb-0 py-2" role="alert">
                                    Untuk attachment Draft Contract, file harus diawali dengan prefix (Draf_Contract_).
                                </div>

                                <div>
                                    <label for="draft_contract" class="form-label">Draf Contract</label>
                                    <input type="file"
                                        class="form-control @error('draft_contract') is-invalid @enderror"
                                        name="draft_contract" id="draft_contract" accept=".pdf"
                                        placeholder="Draft Contract">

                                    @if ($draftContract)
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Existing Document:</small>
                                            <a href="{{ url('/') }}/{{ $draftContract->file_path }}"
                                                target="_blank" rel="noopener">
                                                {{ $draftContract->name ?? basename($draftContract->file_path) }}
                                            </a>
                                        </div>
                                    @endif

                                    @error('draft_contract')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="alert alert-warning alert-dismissible fade show mb-0 py-2" role="alert">
                                    Untuk attachment Quotation, file harus diawali dengan prefix (Quotation_).
                                </div>

                                <div>
                                    <label for="quotation" class="form-label">Quotation</label>
                                    <input type="file" class="form-control @error('quotation') is-invalid @enderror "
                                        name="quotation" id="quotation" accept=".pdf"
                                        value="{{ old('quotation', $requestDocument->quotation) }}"
                                        placeholder="Quotation">

                                    @if ($quotation)
                                        <div class="mt-2">
                                            <small class="text-muted d-block">Existing Document:</small>
                                            <a href="{{ url('/') }}/{{ $quotation->file_path }}" target="_blank"
                                                rel="noopener">
                                                {{ $quotation->name ?? basename($quotation->file_path) }}
                                            </a>
                                        </div>
                                    @endif
                                    @error('quotation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>



                                <div class="d-flex gap-2 justify-content-end">
                                    @if ($requestDocument->status_id == 1)
                                        <button type="submit" name="action_type" value="draft"
                                            class="btn btn-secondary">
                                            Save as Draft
                                        </button>
                                    @endif

                                    <button type="submit" name="action_type" value="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
        </div> <!-- container -->
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // $('#form-request-document').on('submit', function(e) {
            //     e.preventDefault();

            //     let formData = new FormData(this);

            //     // Deteksi tombol mana yang diklik
            //     let clickedButton = e.originalEvent.submitter;
            //     let actionType = $(clickedButton).val(); // Berisi 'draft' atau 'submit'

            //     // Tambahkan status ke dalam FormData yang dikirim ke controller
            //     formData.append('action_type', actionType);

            //     $.ajax({
            //         url: "{{ url('tsp/request-document') }}",
            //         type: "POST",
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         beforeSend: function() {
            //             // Disable tombol agar tidak tertekan dua kali
            //             $('button[type="submit"]').prop('disabled', true);
            //         },
            //         success: function(response) {
            //             alert(response.message);

            //             // Reset form & reload DataTable
            //             $('#form-request-document')[0].reset();
            //             $('#request-document-table').DataTable().ajax.reload();
            //         },
            //         error: function(xhr) {
            //             if (xhr.status === 422) {
            //                 console.log(xhr.responseJSON.errors);
            //             } else {
            //                 alert('Terjadi kesalahan.');
            //             }
            //         },
            //         complete: function() {
            //             // Aktifkan kembali tombol
            //             $('button[type="submit"]').prop('disabled', false);
            //         }
            //     });
            // });

            $("select[name='customer_id']").select2({
                placeholder: "Select a customer",
                width: '100%',
                ajax: {
                    url: "{{ url('tsp/customers') }}",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response.data.map(function(customer) {
                                return {
                                    id: customer.id,
                                    text: customer.name
                                };
                            })
                        };
                    }
                }
            });

            $("select[name='customer_id']").on('select2:select', function(e) {
                var selectedCustomerId = e.params.data.id;
                $.ajax({
                    url: `{{ url('tsp/customers/${selectedCustomerId}') }}`,
                    type: 'GET',
                    success: function(response) {
                        console.log("Selected Customer Details:", response);
                        if (response.success) {
                            const customer = response.data;
                            $("#customer_name").val(customer.name ?? "");
                            $("#customer_nib").val(customer.nib ?? "");
                            $("#customer_npwp").val(customer.npwp ?? "");
                            $("#customer_address").val(customer.address ?? "");
                            $("#customer_postal_code").val(customer.postal_code ?? "");
                            $("#customer_email").val(customer.email ?? "");
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengambil data customer.');
                        // Kosongkan form jika terjadi error
                        $("#customer_nib").val("");
                        $("#customer_npwp").val("");
                        $("#customer_address").val("");
                        $("#customer_postal_code").val("");
                        $("#customer_email").val("");
                    }
                })
            });

            $("select[name='contract_type']").select2({
                placeholder: "Select Contract Type",
                width: '100%',
                allowClear: true
            });

            $("select[name='sign_status']").select2({
                placeholder: "Select Sign Status",
                width: '100%',
                allowClear: true
            });

            $("select[name='is_project']").select2({
                placeholder: "Select Project Category",
                width: '100%',
                allowClear: true
            });
        })
    </script>
@endsection
