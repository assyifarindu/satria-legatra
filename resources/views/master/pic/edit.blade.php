@extends('layouts.master')

@section('title')
    PIC Company |
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
                                <li class="breadcrumb-item"><a href="{{ route('master-pic.index') }}">PIC</a></li>
                                <li class="breadcrumb-item active">Edit PIC Company</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Master PIC Company</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit PIC Company</h4>

                            <form action="{{ route('master-pic.update', $data['pic']->id) }}" method="post">
                                @csrf
                                <input name="_method" type="hidden" value="PUT">
                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">PIC Name</label>
                                        <select class="form-select js-example-basic-single" name="pic">
                                            <option></option>
                                            @foreach ($data['user'] as $i)
                                                <option value="{{ $i->id }}"
                                                    @if ($i->id == $data['pic']->user_id) selected @endif>{{ $i->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mt-3">
                                        <label for="example-select" class="form-label">Company</label>
                                        {{-- <select class="form-select js-example-basic-single" name="company">
                                        <option></option>
                                        @foreach ($data['company'] as $j)
                                            <option value="{{ $j->id }}" @if ($j->id == $data['pic']->company_id) selected @endif>{{ $j->name }}</option>
                                        @endforeach
                                    </select> --}}
                                        <input type="hidden" name="company" value="{{ $data['company']->id }}">
                                        <input type="text" class="form-control" value="{{ $data['company']->name }}"
                                            required readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 mt-3">
                                        <label for="simpleinput" class="form-label">Email PIC<span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="simpleinput" class="form-control" name="email"
                                            value="{{ $data['pic']->email }}" required placeholder="ex. 5">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                    <a href="{{ route('master-pic.index') }}"
                                        class="btn btn-secondary waves-effect">Cancel</a>
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
