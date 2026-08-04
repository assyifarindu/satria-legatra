@extends('layouts.master')

@section('title')
    Show QR Document
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
                                <li class="breadcrumb-item"><a href="{{ route('qr-document.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Show Detail</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Detail Document {{ $document->no_document }}</h4>
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
                                        <div
                                            class="position-relative d-flex align-items-center justify-content-center h-100">
                                            <img class="w-80 position-relative z-index-2"
                                                src="{{ asset('storage/' . $document->qrcode_url) }}" alt="rocket">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-column h-100">
                                        <table class="table align-items-center mb-0">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">No Document</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->no_document }}
                                                        </p>
                                                    </td>

                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Date Uploaded</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTanggal($document->date_uploaded); @endphp</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Date Document</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">@php echo App\Helpers\MyHelper::ubahFormatTanggal($document->date_document); @endphp</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Description</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0 text-wrap">
                                                            {!! $document->description !!}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Receipent</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->receipent }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">No Materai</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->no_materai }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">User Sign</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->user_sign }}
                                                        </p>
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
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Raw Document</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0"><a target="_blank"
                                                                href="{{ asset('storage/' . $document->id_attachment) }}">
                                                                Download</a></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Final Document</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0"><a target="_blank"
                                                                href="{{ asset('storage/' . $document->id_document_final) }}">
                                                                Download</a></p>
                                                    </td>
                                                </tr>
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
