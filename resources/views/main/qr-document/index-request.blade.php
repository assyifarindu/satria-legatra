@extends('layouts.master')

@section('title')
    Request QR Document
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
                                <li class="breadcrumb-item active">Request QR Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request QR Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Request QR Document</h4>
                            <br><br>
                            <table class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Status Verification</th>
                                        <th>Date Verification</th>
                                        <th>Verified By</th>
                                        <th>Date Created</th>
                                        <th>File Request</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->status_action }}</td>
                                            <td>{!! $item->status_verification == 'Verified' ? '<span class="text-success">Verified</span>' : '<span class="text-danger">Unverified</span>' !!}</td>

                                            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($item->date_verification) @endphp</td>
                                            <td>{!! $item->user_verified_id == null ? '<span class="text-danger">Not yet verified</span>' : $item->user_verified->name !!}</td>
                                            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($item->created_at) @endphp</td>
                                            <td><a href="{{ asset('storage').'/'. $item->file }}" class="text-primary" target="_blank"> Download</a></td>

                                            <td class="align-middle">
                                                <a href="{{ route('request-qr.edit', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                    title="Edit Document" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-pencil-alt"></i></a>
                                                <a href="{{ route('request-qr.show', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Ringkasan" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
