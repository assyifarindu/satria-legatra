@extends('layouts.master')

@section('title')
    QR Document
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
                                <li class="breadcrumb-item active">QR Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">QR Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('qr-document.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-fw fa-plus"></i> QR Document</a>
                            </div>
                            <h4 class="header-title">Data QR Document</h4>
                            <br><br>
                            <table class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Document</th>
                                        <th>Description</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($qrDocuments as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-success">{{ $item->generateNumber->document_number }}
                                            </td>
                                            <td>@php echo App\Helpers\MyHelper::potongString($item->description)@endphp</td>
                                            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($item->created_at) @endphp</td>
                                            <td class="align-middle">
                                                <a href="{{ route('qr-document.show', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Ringkasan" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                                <form class="d-inline" action="{{ route('qr-document.destroy', $item) }}"
                                                    method="post" id="deleteForm{{ $item->id }}">
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                                <a class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Hapus" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"
                                                    onclick="confirmDelete({{ $item->id }})"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('qr-document.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-fw fa-plus"></i> QR Document</a>
                            </div>
                            <h4 class="header-title">Data QR Documents</h4>
                            <br><br>

                            <form class="form-inline mb-3" action="{{ route('qr-document.index') }}" method="get">
                                <div class="input-group w-100">
                                    <input type="text" class="form-control" id="keyword" name="keyword" placeholder="e.x. document pml..." aria-label="Cari sesuatu" aria-describedby="button-search">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" id="button-search"><i class="fas fa-fw fa-search"></i> Cari</button>
                                    </div>
                                </div>
                            </form>

                            @if (count($qrDocuments) < 1)
                                <div class="text-center">
                                    <iframe
                                        src="https://lottie.host/embed/c73aca21-eeaa-40b5-977c-a87182030fd8/dP9JfgQc5i.json"></iframe>
                                    <h5>Oops, no documents yet</h5>
                                    <small>It looks like your document data is still empty, add a document
                                        immediately!</small>
                                </div>
                            @endif

                            <div class="row" id="data-view">
                                @foreach ($qrDocuments as $item)
                                    <div class="col-md-4 col-sm-6 col-lg-4 col-6 col-xl-3">
                                        <div class="card shadow">
                                            <div
                                                class="card-header p-0 mx-3 mt-3 text-center position-relative z-index-1 bg-white">
                                                <img src="{{ asset('storage/' . $item->qrcode_url) }}"
                                                    class="img-fluid border-radius-lg">
                                            </div>

                                            <div class="card-body pt-2">
                                                <span class="text-muted text-xs font-weight-bold my-2">Date Uploaded :
                                                    @php echo App\Helpers\MyHelper::ubahFormatTanggal($item->date_uploaded); @endphp</span>
                                                <a href="{{ route('qr-document.show', Hashids::encode($item->id)) }}"
                                                    class="card-title h5 d-block text-darker text-primary">
                                                    {{ $item->no_document }}
                                                </a>
                                                <p class="card-description mb-4">
                                                    @php echo App\Helpers\MyHelper::potongString($item->description)@endphp
                                                </p>
                                                <div class="author align-items-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-primary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('qr-document.edit', Hashids::encode($item->id)) }}">Update</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('qr-document.show', Hashids::encode($item->id)) }}">View</a>
                                                            </li>
                                                            <form action="{{ route('qr-document.destroy', $item) }}"
                                                                method="post" id="deleteForm{{ $item->id }}">
                                                                @method('delete')
                                                                @csrf
                                                            </form>
                                                            <li>
                                                                <button class="dropdown-item" onclick="confirmDelete({{ $item->id }})">Delete</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-5 d-flex justify-content-center">
                                {{ $qrDocuments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        function confirmDelete(itemId) {
            console.log(itemId);
            return Swal.fire({
                title: 'Are you sure?',
                text: "Data will be deleted permanently",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm' + itemId).submit();
                    $('#staticBackdrop').modal('show');
                }
            });
        }

        $(document).ready(function() {
            $('#searchInput').on('input', function() {
                var keyword = $(this).val().trim();

                if (keyword.length > 0) {
                    $.ajax({
                        url: '{{ route('qr-document.search') }}',
                        method: 'GET',
                        data: {
                            keyword: keyword
                        },
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    $('#searchResults').empty();
                    $('#data-view').show();
                }
            });

        });
    </script>
@endsection
