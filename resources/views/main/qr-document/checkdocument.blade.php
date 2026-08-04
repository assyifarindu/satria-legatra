@extends('layouts.master')

@section('title')
    Document Lookup |
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="bg-gradient-primary border-radius-lg h-100">
                                        <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                            <img class="w-80 position-relative z-index-2"
                                                src="{{ asset('storage/' . $document->qrcode_url) }}" alt="rocket">
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-lg">
                                    <div class="d-flex flex-column h-100">
                                        <p class="mb-1 pt-2 text-bold">Detail dokumen</p>
                                        <h5 class="font-weight-bolder">{{ $document->generateNumber->document_number }}</h5>
                                        <table class="table align-items-center mb-0">
                                            <tbody>
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
                                                        <p class="text-xs text-secondary mb-0 text-wrap">{{ $document->description }}</p>
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
                                                        <h6 class="mb-0 text-xs">No Materai</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->no_materai }}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">User Sign</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $document->user_sign }}</p>
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
                                                        <p class="text-xs text-secondary mb-0"><a
                                                                href="{{ asset('storage') . $document->id_attachment }}"> Download</a></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <h6 class="mb-0 text-xs">Final Document</h6>
                                                    </th>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0"><a
                                                                href="{{ asset('storage') . $document->id_document_final }}"> Download</a>
                                                        </p>
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
