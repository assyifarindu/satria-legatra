@extends('layouts.master')

@section('title')
    Show Request QR Document |
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
                                <li class="breadcrumb-item"><a href="{{ route('request-qr.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Edit Request QR Document</h4>
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="bg-gradient-primary border-radius-lg h-100">
                                <a class="text-center" href="{{ asset('storage/') . $request->file }}">
                                    <div
                                        class="position-relative d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="fas fa-fw fa-download fa-3x text-primary"></i>
                                        <p class="mb-0 mt-1">Download File Request Here</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex flex-column h-100">
                                <p class="mb-1 pt-2 text-bold">Detail Request Validation</p>
                                <h5 class="font-weight-bolder">Verified By : {!! $request->user_verified_id == null
                                    ? '<span class="text-danger">Not yet verified</span>'
                                    : $request->user_verified->name !!}</h5>
                                <table class="table align-items-center mb-0">
                                    <tbody>
                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">User Request Name</h6>
                                            </th>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">{{ $request->user_request->name }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Email</h6>
                                            </th>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">{{ $request->email }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Status Verification</h6>
                                            </th>
                                            <td>
                                                @if ($request->status_verification == 'Verified')
                                                    <span class="text-primary">{{ $request->status_verification }}</span>
                                                @else
                                                    <span class="text-danger">{{ $request->status_verification }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Date Verification</h6>
                                            </th>
                                            @if ($request->date_verification != null)
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($request->date_verification) @endphp</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">Not yet</p>
                                                </td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Status Action</h6>
                                            </th>
                                            <td>
                                                @if ($request->status_action == 'Read')
                                                    <p class="text-xs text-success mb-0">{{ $request->status_action }}</p>
                                                @else
                                                    <p class="text-xs text-danger mb-0">{{ $request->status_action }}</p>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Created At</h6>
                                            </th>
                                            <td>
                                                <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($request->created_at) @endphp</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <h6 class="mb-0 text-xs">Updated At</h6>
                                            </th>
                                            @if ($request->updated_at != null)
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($request->updated_at) @endphp</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">Not yet</p>
                                                </td>
                                            @endif
                                        </tr>
                                        @if ($request->status_verification != 'Verified')
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0 text-xs">Action</h6>
                                                </th>
                                                <td>
                                                    <a class="btn btn-primary btn-sm" href="{{ route('request-qr.edit', Hashids::encode($request->id)) }}">Vericate Now <i
                                                            class="fas fa-fw fa-arrow-right"></i></a>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>

                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
