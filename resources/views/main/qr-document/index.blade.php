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
                            @if(count($qrDocuments) > 0)
                                <div class="d-flex align-items-center mb-3">
                                    <label for="filterYear" class="mr-2">Filter by Year:</label>
                                    <select id="filterYear" class="form-control">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            @endif
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
        </div>
    </div>
@endsection

@section('js')
    <script>
        function confirmDelete(itemId) {
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
                    });
                } else {
                    $('#searchResults').empty();
                    $('#data-view').show();
                }
            });

        });

        $(document).ready(function() {
            var currentYear = new Date().getFullYear();
            var startYear = currentYear - 3; // 5 tahun kebelakang
            var endYear = currentYear + 1; // 1 tahun ke depan

            for (var year = endYear; year >= startYear; year--) {
                $('#filterYear').append(new Option(year, year)).select2();
            }

            var table = $('.scroll-horizontal-datatable').DataTable();

            // Custom filtering function
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var filterYear = $('#filterYear').val();
                    var createdAt = data[3]; // Index kolom `created_at`

                    if (filterYear === "" || createdAt.includes(filterYear)) {
                        return true;
                    }
                    return false;
                }
            );

            // Apply the filter
            $('#filterYear').on('change', function() {
                table.draw();
            });
        });
    </script>
@endsection
