@extends('layouts.master')

@section('title')
    Log Error |
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
                                <li class="breadcrumb-item active">Log Error</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Log Error</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Log Error</h4>
                            <br><br>

                            <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>NRP / Email</th>
                                        <th>Action</th>
                                        <th>Message</th>
                                        <th>Ex String</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['error'] as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td>{{ $item['created_by'] }}</td>
                                            <td>{{ $item['action'] }}</td>
                                            <td>{{ $item['message'] }}</td>
                                            <td>{{ $item['ex_string'] }}</td>
                                            <td>{{ $item['created_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->
@endsection
