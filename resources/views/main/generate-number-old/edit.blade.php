@extends('layouts.master')

@section('title')
    Edit Document Number |
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
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Document Number</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Document Number</h4>
                            <form action="{{ route('generate-number.update', $documentNumber->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="user">User</label>
                                        <input type="text" class="form-control @error('user') is-invalid @enderror"
                                            name="user" value="{{ $documentNumber->user }}" required autofocus>
                                        @error('user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="create_by">Create By</label>
                                        <input type="text" class="form-control @error('create_by') is-invalid @enderror"
                                            name="create_by" value="{{ $documentNumber->create_by }}" required>
                                        @error('create_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="nrp">NRP</label>
                                        <input type="text" class="form-control @error('nrp') is-invalid @enderror"
                                            name="nrp" value="{{ $documentNumber->nrp }}" required>
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
                                                    <label for="document_type">Document Type</label>
                                                    <select name="document_type" id="document_type" class="form-control">
                                                        <option value="" selected disabled>-- select document type --
                                                        </option>
                                                        @foreach ($documentTypes as $item)
                                                            @if ($documentNumber->document_type == $item['value'])
                                                                <option selected value="{{ $item['value'] }}">
                                                                    {{ $item['name'] }}</option>
                                                            @else
                                                                <option value="{{ $item['value'] }}">{{ $item['name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
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
                                                        @if ($documentNumber->sign_by == 'GM')
                                                            <option value="" disabled>-- select sign by --</option>
                                                            <option value="GM" selected>GM</option>
                                                            <option value="BOD">BOD</option>
                                                        @elseif($documentNumber->sign_by == 'BOD')
                                                            <option value="" disabled>-- select sign by --</option>
                                                            <option value="GM">GM</option>
                                                            <option value="BOD" selected>BOD</option>
                                                        @else
                                                            <option value="" selected disabled>-- select sign by --
                                                            </option>
                                                            <option value="GM">GM</option>
                                                            <option value="BOD">BOD</option>
                                                        @endif
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
                                                    <label for="company">Company</label>
                                                    <select name="company" id="company" class="form-control">
                                                        <option selected disabled>-- select company --</option>
                                                        @foreach ($companies as $company)
                                                            @if ($documentNumber->company_id == $company->id)
                                                                <option selected value="{{ $company->short_name }}">
                                                                    {{ $company->name }} ({{ $company->short_name }})
                                                                </option>
                                                            @else
                                                                <option value="{{ $company->short_name }}">
                                                                    {{ $company->name }}
                                                                    ({{ $company->short_name }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('company')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <select name="department" id="department" class="form-control"
                                                        data-default="{{ $documentNumber->department_id }}">
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
                                        <label for="title">Document Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ $documentNumber->document_title }}" required>
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
                                                    <label for="file">File Document</label>
                                                    <input type="file"
                                                        class="form-control @error('file') is-invalid @enderror"
                                                        name="file">
                                                    @error('file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="">Last document</label>
                                                    <a class="text-sm text-info" href="{{ asset('storage' . $documentNumber->file) }}" target="_blank">Download here</a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col">
                                                <div class="form-group">
                                                    <label for="receipent">Receipent</label>
                                                    <input type="text"
                                                        class="form-control @error('receipent') is-invalid @enderror"
                                                        name="receipent" value="{{ $documentNumber->receipent }}" required>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var defaultCompanyCode = $('#company').val(); // Get the default selected company
            var defaultDepartmentId = $('#department').data('default'); // Get the default selected department

            function checkAndEnableSecondSelect() {
                var selectedValue = $('#document_type').val();
                if (selectedValue === 'Agg' || selectedValue === null) {
                    $('#sign_by').val(null);
                    $('#sign_by').addClass('grey-color');
                    $('#sign_by').prop('disabled', true); // Menghapus properti disabled
                } else {
                    $('#sign_by').removeClass('grey-color');
                    $('#sign_by').prop('disabled', false); // Menambahkan properti disabled
                }
            }

            // Memeriksa saat halaman pertama kali dimuat
            checkAndEnableSecondSelect();

            // Memeriksa saat nilai dari firstSelect berubah
            $('#document_type').change(function() {
                checkAndEnableSecondSelect();
            });

            if (defaultCompanyCode) {
                loadDepartments(defaultCompanyCode, defaultDepartmentId);
            }

            $('#company').on('change', function() {
                $('#department').prop('disabled', true);
                var companyCode = this.value;
                $('#department').html('');
                $('#department').html('<option selected disabled>-- select department --</option>');

                if (companyCode) {
                    loadDepartments(companyCode);
                }
            });

            function loadDepartments(companyCode, defaultDepartmentId = null) {
                $.ajax({
                    url: '/generate-number/departments/' + companyCode,
                    type: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            $('#department').removeClass('text-danger');
                            $('#department').prop('disabled', false);
                            $('#department').html(
                                '<option selected disabled>-- select department --</option>'
                            );
                            $.each(data, function(key, department) {
                                var selected = (defaultDepartmentId && defaultDepartmentId ==
                                    department.id) ? 'selected' : '';
                                $('#department').append('<option value="' + department.id +
                                    '" ' + selected + '>' + department.name + '</option>');
                            });
                        } else {
                            $('#department').prop('disabled', true);
                            $('#department').addClass('text-danger');
                            $('#department').html(
                                '<option selected disabled>Department not found</option>');
                        }
                    }
                });
            }
        });
    </script>
@endsection
