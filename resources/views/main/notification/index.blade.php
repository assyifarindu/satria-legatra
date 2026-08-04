@extends('layouts.master')

@section('title')
    Notification |
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
                            <li class="breadcrumb-item active">Notification</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Notification</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <!-- Portlet card -->
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="{{ route('notification.read-all') }}" class="btn btn-sm btn-primary"><i class="fas fa-envelope-open"></i> Mark As Read All</a>
                        </div>
                        <h4 class="header-title mb-0">Notification</h4>

                        <div id="cardCollpase4" class="collapse pt-3 show">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-borderless mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Notification</th>
                                            <th>Created At</th>
                                            <th>From</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data['notification'] as $item)
                                        
                                            <tr>
                                                
                                                <td>{{ $no++ }}</td>
                                                <td><a href="{{ route($item->url, Hashids::encode($item->id_feature)) }}">{{ $item->feature }}</a></td>
                                                <td>{{ formatDate($item->created_at) }}</td>
                                                <td>
                                                    @if ($item->created_by == NULL)
                                                        -
                                                    @else
                                                        {{ getUserName($item->created_by)->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->is_clicked == 0)
                                                        <span class="badge bg-soft-warning text-info p-1">No Action</span>
                                                    @else
                                                        <span class="badge bg-soft-info text-info p-1">Clicked</span>                                                        
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- .table-responsive -->
                        </div> <!-- end collapse-->
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
        <!-- end row-->

    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('js')
@endsection
