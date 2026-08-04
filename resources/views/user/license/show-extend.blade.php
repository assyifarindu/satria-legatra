@extends('layouts.master')

@section('title')
    Detail Extend Contract |
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
                            <li class="breadcrumb-item"><a href="{{ route('extend-license-user.index') }}">Extend License</a></li>
                            <li class="breadcrumb-item active">Detail Extend License</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Detail Extend License</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Data Extend License</h4>
                        <br>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <h5 class="mt-0">Company</h5>
                                    <p>{{ $data['contract']->Document->company }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <h5 class="mt-0">Category </h5>
                                    <p>License</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <h5 class="mt-0">PIC </h5>
                                    <p>{{ $data['contract']->Document->pic_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <h5 class="mt-0">Email</h5>
                                    <p>{{ $data['contract']->Document->pic_email }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-4">
                                    <h5 class="mt-0">Contract Number </h5>
                                    <p>{{ $data['contract']->extend_contract_number }}</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->

        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Timeline Extend Contract</h4>
                        <br>
                        <div class="track-order-list">
                            <ul class="list-unstyled">
                                @foreach ($data['contract_history'] as $ch)
                                    <li class="completed">
                                        <h5 class="mt-0 mb-1">Deal Date : {{ formatDate($ch->extended_date) }} with duration {{ $ch->Alert->duration }} days</h5>
                                        <p class="text-muted">Note : {{ $ch->note }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->

            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">History Contract</h4>
                        <br><br>
                        <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deal Date </th>
                                    <th>Duration</th>
                                    <th>Contract Note</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                                @php
                                    $no = 1;
                                @endphp
                            <tbody>
                                @foreach ($data['contract_history'] as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ formatDate($item->extended_date) }}</td>
                                        <td>{{ $item->Alert->duration }} Days</td>
                                        <td>{{ $item->note }}</td>
                                        <td><a href="">{{ $item->file }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('js')
@endsection
