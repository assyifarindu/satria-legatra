@extends('layouts.master')

@section('title')
    Create new Document Number |
@endsection

@section('css')
    <style>
        .form-control:disabled {
            background-color: rgba(234, 234, 234, 0.603);
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('generate-number.index') }}">Generate
                                        Number</a></li>
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create new Document Number</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Create Document Number</h4>
                            <form action="{{ route('generate-number.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-5 mt-3">
                                        <label for="user">User <span class="text-danger">*</span></label>
                                        <select id="user" class="form-control" name="user" required>
                                            <option></option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->name }}"
                                                    data-personal-number="{{ $user->personal_number }}">{{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-5 mt-3">
                                        <label for="nrp_user">NRP User</label>
                                        <input type="text" class="form-control @error('nrp_user') is-invalid @enderror"
                                            name="nrp_user" id="nrp_user" value="{{ old('nrp_user') }}" required autofocus>
                                        @error('nrp_user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 mt-3">
                                        <label for="create_by">Create By <span class="text-danger">*</span></label>
                                        <select id="create_by" class="form-control" name="create_by" required>
                                            <option></option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->name }}"
                                                    data-personal-number="{{ $user->personal_number }}">
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('create_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-5 mt-3">
                                        <label for="nrp">NRP</label>
                                        <input type="text" class="form-control @error('nrp') is-invalid @enderror"
                                            name="nrp" id="nrp" value="{{ old('nrp') }}" required>
                                        @error('nrp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="document_type">Document Type <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="document_type" id="document_type">
                                                        <option value="" selected disabled>-- select document type --
                                                        </option>
                                                        <option value="Agg">Agreement (Agg)</option>
                                                        <option value="Let">Surat (Let)</option>
                                                        <option value="SK">Surat Kuasa (SK)</option>
                                                        <option value="Memo">Memo (Memo)</option>
                                                        <option value="SOP">Standard Operational Procedure (SOP)</option>
                                                    </select>
                                                    @error('document_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="sign_by">Sign By</label>
                                                    <select name="sign_by" id="sign_by" class="form-control">
                                                        <option value="" selected disabled>-- select sign by --
                                                        </option>
                                                        <option value="GM">GM</option>
                                                        <option value="BOD">BOD</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="company">Company <span class="text-danger">*</span></label>
                                                    {{-- <select name="company" id="company" class="form-control">
                                                        <option selected disabled>-- select company --</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->short_name }}">
                                                                {{ $company->name }} ({{ $company->short_name }})
                                                            </option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="hidden" name="company" id="company"
                                                        value="{{ $companies->short_name }}">
                                                    <input type="text" class="form-control"
                                                        value="{{ $companies->name }}" required readonly>

                                                    @error('company')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="department">Department <span
                                                            class="text-danger">*</span></label>
                                                    <select name="department" id="department" class="form-control">
                                                        <option selected disabled>-- select department --</option>
                                                    </select>
                                                    @error('department')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="title">Document Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="file">File Document <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file"
                                                        class="form-control @error('file') is-invalid @enderror"
                                                        name="file" value="{{ old('file') }}"
                                                        accept="application/pdf" required>
                                                    @error('file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="receipent">Receipent <span
                                                            class="text-danger">*</span></label>
                                                    <select id="receipent" class="form-control" name="receipent"
                                                        required>
                                                        <option></option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->name }}"
                                                                data-personal-number="{{ $user->personal_number }}">
                                                                {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('receipent')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-2">
                                    <button class="btn btn-primary waves-effect waves-light"
                                        type="submit">Submit</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                </div>

                            </form>
                            <span class="text-danger">* Required field</span>
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
            // Initialize Selectize
            $("#user, #create_by, #receipent").select2({
                tags: true
            });

            // Initialize Select2
            $("#document_type, #sign_by, #department").select2();

            // Disable the department select on page load
            $('#department').prop('disabled', true);

            // Function to check and enable/disable sign_by select
            function checkAndEnableSecondSelect() {
                var selectedValue = $('#document_type').val();
                if (selectedValue === 'Agg' || selectedValue === null) {
                    $('#sign_by').val(null).trigger('change');
                    $('#sign_by').addClass('grey-color');
                    $('#sign_by').prop('disabled', true);
                } else {
                    $('#sign_by').removeClass('grey-color');
                    $('#sign_by').prop('disabled', false);
                }
            }

            // Check and enable/disable sign_by select on page load
            checkAndEnableSecondSelect();

            // Check and enable/disable sign_by select when document_type changes
            $('#document_type').change(function() {
                checkAndEnableSecondSelect();
            });

            $('#user, #create_by').change(function() {
                var selectedOption = $(this).find('option:selected');
                var personalNumber = selectedOption.data('personal-number');
                console.log(selectedOption);
                var targetInput = $(this).attr('id') === 'user' ? '#nrp_user' : '#nrp';

                if (personalNumber !== undefined) {
                    $(targetInput).val(personalNumber);
                } else {
                    $(targetInput).val('');
                }
            });

            // Enable department select and load data when company changes

            // $('#company').on('input', function() {
            //     var companyCode = $(this).val().trim();
            //     console.log('Company code:', companyCode);

            //     $('#department').html('');
            //     $('#department').html('<option selected disabled>-- select department --</option>');
            //     var url = "{{ url('generate-number/departments/') }}/";
            //     if (companyCode) {
            //         $.ajax({
            //             url: url + companyCode,
            //             type: 'GET',
            //             success: function(data) {
            //                 console.log(data);
            //                 if (data.length > 0) {
            //                     $('#department').removeClass('text-danger');
            //                     $('#department').prop('disabled', false);

            //                     $.each(data, function(key, department) {
            //                         if (department.short_name != null) {
            //                             $('#department').append('<option value="' +
            //                                 department.id + '">' + department.name +
            //                                 ' (' + department.short_name + ')' +
            //                                 '</option>');
            //                         } else {
            //                             $('#department').append('<option value="' +
            //                                 department.id + '">' + department.name +
            //                                 '</option>');
            //                         }
            //                     });
            //                     // Initialize Select2 on the department dropdown
            //                     $('#department').select2({
            //                         placeholder: "-- select department --",
            //                         allowClear: true
            //                     });
            //                 } else {
            //                     $('#department').prop('disabled', true);
            //                     $('#department').addClass('text-danger');
            //                     $('#department').html(
            //                         '<option selected disabled>Department not found</option>'
            //                     );
            //                 }
            //             }
            //         });
            //     }
            // });


        });

        $(document).ready(function() {
            var companyCode = $('#company').val().trim();
            console.log('Company code:', companyCode);
            // $('#company').on('input', function() {
            //     var companyCode = $(this).val().trim();
            // console.log('Company code:', companyCode);
            // Reset dropdown department
            $('#department').html('<option selected disabled>-- select department --</option>');

            if (companyCode !== "") {
                var url = "{{ url('generate-number/departments/') }}/" + companyCode;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            $('#department').removeClass('text-danger');
                            $('#department').prop('disabled', false);

                            $.each(data, function(index, department) {
                                var displayText = department.name;
                                if (department.short_name) {
                                    displayText += ' (' + department.short_name +
                                        ')';
                                }
                                $('#department').append('<option value="' +
                                    department.id + '">' + displayText +
                                    '</option>');
                            });

                            // Inisialisasi Select2 pada dropdown department
                            $('#department').select2({
                                placeholder: "-- select department --",
                                allowClear: true
                            });
                        } else {
                            $('#department').prop('disabled', true);
                            $('#department').addClass('text-danger');
                            $('#department').html(
                                '<option selected disabled>Department not found</option>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: ', error);
                    }
                });
            } else {
                // Bila input kosong, nonaktifkan dropdown department
                $('#department').prop('disabled', true);
            }
            // });
        });
    </script>
@endsection
