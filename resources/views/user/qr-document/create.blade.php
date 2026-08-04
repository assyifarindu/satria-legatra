{{-- @extends('layouts.master')

@section('title')
    Make Request Document QR | 
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
                                <li class="breadcrumb-item"><a href="{{ route('qr-document.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Make Request</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Make Request QR Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('qr-document.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-10">
                                        <label for="email">Email</label>
                                        <input type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            name="email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="document">Choose document</label>
                                        <input type="file" class="form-control" id="document" name="document"
                                            value="{{ old('document') }}" required>
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
@endsection --}}


<!-- Standard modal content -->
<div id="makeRequestModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="makeRequest-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="makeRequest-label">Form Request Document QR</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('request-qr-user-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="file_upload">Choose File</label>
                        <input type="file" class="form-control" id="file_upload" name="file_upload"
                            value="{{ old('file_upload') }}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create Request</button>
            </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
