@extends('layouts.master')

@section('title')
    Edit Request QR Document |
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
                                <li class="breadcrumb-item"><a href="{{ route('request-qr.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Request QR Document</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Request QR Document</h4>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="bg-white border-radius-lg h-100">
                                        <a class="text-center" href="{{ asset('storage/') . '/'. $request->file }}" target="_blank">
                                            <div
                                                class="position-relative d-flex flex-column align-items-center justify-content-center h-100">
                                                <i class="fas fa-fw fa-download fa-3x text-primary"></i>
                                                <p class="mb-0 mt-1">Download File Here</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <form action="{{ route('request-qr.update', $request->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-lg-10 mt-3">
                                                <label for="email">Email</label>
                                                <input type="text" disabled
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ $request->email }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-10 mt-3">
                                                <label for="status_verification">Status Verification</label>
                                                <select
                                                    class="form-control @error('status_verification') is-invalid @enderror"
                                                    id="status_verification" name="status_verification">
                                                    @if ($request->status_verification == 'Verified')
                                                        <option value="Verified" selected>Verified</option>
                                                        <option value="Unverified">Unverifed</option>
                                                    @else
                                                        <option value="Verified">Verified</option>
                                                        <option value="Unverified" selected>Unverifed</option>
                                                    @endif
                                                </select>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="text-end mt-2">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                            <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
