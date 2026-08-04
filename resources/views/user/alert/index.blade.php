@extends('layouts.master')

@section('title')
    Alert |
@endsection

@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page Duties -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Alert</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Alert</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Alert</h4>
                            <br><br>
                            <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Contract Number</th>
                                        <th>Document Title</th>
                                        <th>Renew Due Date</th>
                                        <th>Email At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['alert'] as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                @if ($item->BaseDocument->category == 1)
                                                    Contract/Letter
                                                @elseif ($item->BaseDocument->category == 2)
                                                    License
                                                @else
                                                    HAKI
                                                @endif
                                            </td>
                                            <td>{{ $item->BaseDocument->contract_number }}</td>
                                            <td>{{ $item->BaseDocument->description }}</td>
                                            <td>{{ date('Y-m-d', strtotime($item->BaseDocument->contract_date . ' + ' . $item->BaseDocument->duration_days . ' days')) }}
                                            </td>
                                            <td>{{ formatDate($item->created_at) }}</td>
                                            <td>
                                                <a href="{{ route('alert-user.show', Hashids::encode($item->id)) }}"
                                                    class="btn btn-primary btn-xs d-inline waves-effect waves-light"><i
                                                        class="fe-file-plus"></i> Request Extend</a>
                                            </td>
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
@section('js')
@endsection
