@extends('layouts.master')

@section('title')
    Detail Document Number |
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
                                <li class="breadcrumb-item"><a href="{{ route('generate-number.index') }}">Generate Number</a></li>
                                <li class="breadcrumb-item active">Show Detail</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Detail Document Number <span class="{{ $document->deleted_at != null ? 'text-danger' : '' }}">{{ $document->document_number }}</span> </h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="bg-white border-radius-lg h-100">
                                        <a class="text-center" href="{{ asset('storage/'.$document->file) }}" target="_blank">
                                            <div
                                                class="position-relative d-flex flex-column align-items-center justify-content-center h-100">
                                                <i class="fas fa-fw fa-download fa-3x text-primary"></i>
                                                <p class="mb-0 mt-1">Download File Here</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-column h-100">
                                        <table class="table align-items-center mb-0">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Document Number</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0 {{ $document->deleted_at != null ? 'text-danger' : '' }}">{{ $document->document_number }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Title</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->document_title }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">User</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->user }} - {{ $document->nrp_user }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Create By</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->create_by }} - {{ $document->nrp }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Document Type</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0 text-wrap">{{ $document->document_type }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Company</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->company->name }} ({{ $document->company->short_name }})</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Department</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->department->name }} ({{ $document->department->short_name }})</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Receipent</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->receipent }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Sign By</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{!! $document->sign_by == null ? '<span class="text-danger">Not Sign</span>' : $document->sign_by !!}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Created At</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($document->created_at); @endphp</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Updated At</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($document->updated_at); @endphp</p>
                                                    </td>
                                                </tr>
                                                @if($document->deleted_at != null)
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Tagged on</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0 {{ $document->deleted_at != null ? 'text-danger' : '' }}">@php echo App\Helpers\MyHelper::ubahFormatTimestamp($document->deleted_at); @endphp</p>
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
        </div>
    </div>
@endsection