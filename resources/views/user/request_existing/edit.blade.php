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
                            <li class="breadcrumb-item"><a href="{{ route('request-existing.index') }}">Request Existing Document</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                        <h4 class="header-title">Edit Request Existing Document</h4>

                        <form action="{{ route('request-existing.update-data', Hashids::encode($data['tracking']->id)) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-lg-11 mt-3">
                                    <label for="example-select" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="Judul dokumen" value="{{ $data['tracking']->title }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-11 mt-3">
                                    <label for="example-select" class="form-label">Tipe Dokumen <span class="text-danger">*</span></label>
                                    <select class="form-select js-example-basic-single" required name="type">
                                        <option value="Contract/Letter" @if ($data['tracking']->type == "Contract/Letter") selected @endif>Contract/Letter</option>
                                        <option value="License" @if ($data['tracking']->type == "License") selected @endif>License</option>
                                        <option value="HAKI" @if ($data['tracking']->type == "HAKI") selected @endif>HAKI</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-11 mt-3">
                                    <label for="simpleinput" class="form-label">Pic <span class="text-danger">*</span></label>
                                    <select class="form-select js-example-basic-single" required name="pic">
                                        <option></option>
                                        @foreach ($data['pic'] as $i)
                                            <option value="{{ $i->id }}" @if ($data['tracking']->pic_id == $i->id) selected @endif>{{ $i->Company->name }} - {{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-11 mt-3">
                                    <label for="simpleinput" class="form-label">Tujuan Permintaan <span class="text-danger">*</span></label>
                                    <textarea name="purpose" class="form-control" required cols="30" rows="6" placeholder="Tujuan permintaan dokumen">{{ $data['tracking']->purpose }}</textarea>
                                </div>
                            </div>

                            <div class="text-end mt-2">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
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
<script>

</script>
@endsection
