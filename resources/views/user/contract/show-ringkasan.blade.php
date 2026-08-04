@extends('layouts.master')

@section('title')
 Ringkasan Document |
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
                            <li class="breadcrumb-item active">Ringkasan Document</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Ringkasan Document</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-muted">Judul Ringkasan</h5>
                        <h5 class="header-title">{{ $data['contract']->title_ringkasan }}</h5>

                        <h5 class="text-muted mt-4">Ringkasan</h5>
                        {!! $data['contract']->ringkasan !!}
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
