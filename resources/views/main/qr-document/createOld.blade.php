@extends('layouts.master')

@section('title')
    Create QR Document
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
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create QR Document</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Create QR Document</h4>
                            <form action="{{ route('qr-document.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="no_ducument">No Document</label>
                                        <input type="text"
                                            class="form-control @error('no_document') is-invalid @enderror" id="no_ducument"
                                            name="no_document" value="{{ old('no_document') }}" required autofocus>
                                        @error('no_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="date_document">Date Document</label>
                                        <input type="date"
                                            class="form-control @error('date_document') is-invalid @enderror"
                                            id="date_document" name="date_document" value="{{ old('date_document') }}"
                                            required>
                                        @error('date_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                            cols="15" rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="receipent">Receipent</label>
                                        <input type="text" class="form-control @error('receipent') is-invalid @enderror"
                                            id="receipent" name="receipent" value="{{ old('receipent') }}" required>
                                        @error('receipent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="no_materai">No Materai</label>
                                        <input type="text" class="form-control @error('no_materai') is-invalid @enderror"
                                            id="no_materai" name="no_materai" value="{{ old('no_materai') }}" required>
                                        @error('no_materai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="user_sign">Document Sign By</label>
                                        <input type="text" class="form-control @error('user_sign') is-invalid @enderror"
                                            id="user_sign" name="user_sign" value="{{ old('user_sign') }}" required>
                                        @error('user_sign')
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
@endsection
