@extends('layouts.master')

@section('title')
    Send Email Alert |
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
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('license-alert.index') }}">Alert License</a>
                                </li>
                                <li class="breadcrumb-item active">Send Email Alert</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Send Email Alert</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Send Email Alert</h4>

                            <form action="{{ route('license-alert.email') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="base_document_id" value="{{ $data['base']->id }}">
                                <input type="hidden" name="document_id" value="{{ $data['document']->id }}">
                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="simpleinput" class="form-label">Judul Document <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="document_title" readonly
                                            placeholder="Contract document"
                                            value="{{ $data['base']->contract_number }} - {{ $data['base']->description }}"
                                            required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mt-3">
                                        <label for="example-select" class="form-label">Kirim email kepada</label>
                                        <select class="form-control selectize" required name="to[]" multiple>
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}">{{ $i->name }} -
                                                    {{ $i->email_sf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="text-end mt-2">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Kirim
                                        Email</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                </div>
                            </form>
                            <!-- end row-->

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div><!-- end col -->
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
