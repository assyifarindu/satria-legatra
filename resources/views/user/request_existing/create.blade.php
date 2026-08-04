@extends('layouts.master')

@section('title')
    Request Existing Document |
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
                                <li class="breadcrumb-item"><a href="{{ route('request-existing.index') }}">Request Existing
                                        Document</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Existing Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Add Request Existing Document</h4>

                            <form action="{{ route('request-existing.store') }}" method="post">
                                @csrf

                                {{-- <div class="row">
                                <div class="col-lg-11 mt-3">
                                    <label for="example-select" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                                    <select class="form-select js-example-basic-single" required name="document">
                                        <option></option>
                                        @foreach ($data['document'] as $item)
                                            @if ($item->category == 1)
                                                <option value="{{ $item->id }}">[Contract] - {{ $item->contract_number }} - {{ $item->description }}</option>
                                            @elseif ($item->category == 2)
                                                <option value="{{ $item->id }}">[License] - {{ $item->contract_number }} - {{ $item->description }}</option>
                                            @else
                                                <option value="{{ $item->id }}">[HAKI] - {{ $item->contract_number }} - {{ $item->description }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                                <div class="row">
                                    <div class="col-lg-11 mt-3">
                                        <label for="example-select" class="form-label">Tipe Dokumen <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select js-example-basic-single" required name="type">
                                            <option></option>
                                            <option value="Contract/Letter">Contract/Letter</option>
                                            <option value="License">License</option>
                                            <option value="HAKI">HAKI</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-11 mt-3">
                                        <label for="simpleinput" class="form-label">Pic <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select js-example-basic-single" required name="pic">
                                            <option></option>
                                            @foreach ($data['pic'] as $i)
                                                <option value="{{ $i->id }}">{{ $i->Company->name }} -
                                                    {{ $i->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-11 mt-3">
                                        <label for="simpleinput" class="form-label">Tujuan Permintaan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="purpose" class="form-control" required cols="30" rows="6"
                                            placeholder="Tujuan permintaan dokumen"></textarea>
                                    </div>
                                </div>


                                <div class="row uploadWrapper mt-3">
                                    <div class="col-lg-11">
                                        <label for="simpleinput" class="form-label">Judul Dokumen</label>
                                        <input type="text" class="form-control" name="document[]"
                                            placeholder="Judul Dokumen">
                                    </div>
                                    <div class="col-lg-1">
                                        <label for="simpleinput" class="form-label">Action <span
                                                class="text-danger">*</span></label>
                                        <button class="btn btn-primary form-control" id="add_upload"><i
                                                class="fas fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="text-end mt-5">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div><!-- end col -->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Document
        let upload_wrapper = $('.uploadWrapper');
        $(document).on("click", "#add_upload", function(e) {
            e.preventDefault();
            $(upload_wrapper).append('<div class="row numberUpload">' +
                '<div class="col-lg-11 mt-1">' +
                '<input type="text" class="form-control" required name="document[]" placeholder="Judul Dokumen">' +
                '</div>' +
                '<div class="col-lg-1 mt-1">' +
                '<button class="btn btn-danger form-control remove_upload"><i class="fas fa-trash"></i></button>' +
                '</div>' +
                '</div>');
        });

        $(document).on("click", ".remove_upload", function() {
            $(this).parents("div .numberUpload").remove();
        });
    </script>
@endsection
