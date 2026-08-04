@extends('layouts.master')

@section('title')
    PML Portal | Update QR Document
@endsection

@section('css')
    <style>
        .form-control:read-only {
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
                                <li class="breadcrumb-item"><a href="{{ route('qr-document.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Document {{ $document->generateNumber->document_numberx }}</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('qr-document.update', $document->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-lg-10">
                                        <label for="no_ducument">No Document</label>
                                        {{-- <input type="text"
                                            class="form-control @error('no_document') is-invalid @enderror" id="no_ducument"
                                            name="no_document" value="{{ $document->no_document }}" required
                                            autofocus> --}}
                                        <select class="form-select" name="no_document" id="no_document">
                                            <option selected disabled>-- Select Document Number --</option>
                                            @foreach ($documentNumbers as $item)
                                                @if ($item->id == $document->no_document)
                                                    <option selected value="{{ $item->id }}">
                                                        {{ $item->document_number }}</option>
                                                @else
                                                    <option value="{{ $item->id }}">{{ $item->document_number }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
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
                                            id="date_document" name="date_document" value="{{ $document->date_document }}"
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
                                            cols="15" rows="5" required>{{ $document->description }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="receipent">Receipent</label>
                                        <input type="text" class="form-control @error('receipent') is-invalid @enderror"
                                            id="receipent" name="receipent" value="{{ $document->receipent }}" required>
                                        @error('receipent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="no_materai">No Materai</label>
                                        <input type="text" class="form-control @error('no_materai') is-invalid @enderror"
                                            id="no_materai" name="no_materai" value="{{ $document->no_materai }}" required>
                                        @error('no_materai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="user_sign">Document Sign By</label>
                                        <input type="text" class="form-control @error('user_sign') is-invalid @enderror"
                                            id="user_sign" name="user_sign" value="{{ $document->user_sign }}" required>
                                        @error('user_sign')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="">Last document</label>
                                        <a class="text-sm text-info"
                                            href="{{ asset('storage/' . $document->id_document_final) }}">Download here</a>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        {{-- <label for="document">Choose document</label>
                                        <input style="background-color: white" type="file" class="form-control" id="document" name="document"> --}}
                                        <input hidden type="text" id="file" name="file" value="{{ $document->id_attachment }}">
                                        
                                        <embed style="width: 100%;height: 900px " type="application/pdf" id="pdfView" src="{{ asset('storage/' . $document->id_document_final) }}"></embed>
                                    
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#no_document').select2();
            $("#receipent, #user_sign, #date_document").prop('readonly', true);
            $('#no_document').on('change', function() {
                var idGenerateNumber = this.value;
                console.log(idGenerateNumber);
                if (idGenerateNumber) {
                    $.ajax({
                        url: '/qr-document/document/' + idGenerateNumber,
                        type: 'GET',
                        success: function(data) {
                            console.log(data)
                            $("#receipent").val(data.receipent);
                            $("#user_sign").val(data.sign_by);
                            // Set nilai input type="date"
                            let date = new Date(data.created_at);
                            let formattedDate = date.toISOString().substring(0, 10);
                            $("#date_document").val(formattedDate);
                            $("#receipent, #user_sign, #date_document").prop('readonly', true);
                        }
                    });
                }
            });
        })
    </script>
@endsection
