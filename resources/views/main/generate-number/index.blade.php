@extends('layouts.master')

@section('title')
    Generate Number Document |
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Generate Number</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Generate Number</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('generate-number.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-fw fa-plus"></i> Generate new number</a>
                            </div>
                            <h4 class="header-title">Data Document Number</h4>
                            <br><br>
                            @if (count($documentNumbers) > 0)
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
                                        <th>Document Number</th>
                                        <th>User</th>
                                        <th>File</th>
                                        <th>Created By</th>
                                        <th>Document Title</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentNumbers as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="{{ $item->deleted_at != null ? 'text-danger'  : 'text-success' }}">{{ $item->document_number }}</td>
                                            <td>{{ $item->user }}</td>
                                            <td><a href="{{ asset('storage') . $item->file }}" class="text-primary"
                                                    target="_blank"> Download</a></td>
                                            <td>{{ $item->create_by }}</td>
                                            <td>{{ $item->document_title }}</td>
                                            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($item->created_at) @endphp</td>
                                            <td class="align-middle">
                                                <form class="d-inline"
                                                    action="{{ $item->deleted_at == null ? route('generate-number.destroy', $item)  : route('generate-number.restore', $item)  }}" method="post"
                                                    id="deleteForm{{ $item->id }}">
                                                    @if($item->deleted_at == null)
                                                        @method('delete')
                                                    @else
                                                        @method('put')
                                                    @endif
                                                    @csrf
                                                </form>
                                                <a class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Flag Dokumen" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"
                                                    onclick="confirmDelete({{ $item->id }})"><i
                                                        class="fas fa-flag {{ $item->deleted_at != null ? 'text-danger' : '' }}"></i></a>
                                                <a href="{{ route('generate-number.show', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Ringkasan" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                                {{-- <form class="d-inline" action="{{ route('generate-number-force', $item) }}"
                                                    method="post" id="deleteForceForm{{ $item->id }}">
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                                <a class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="Hapus" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"
                                                    onclick="confirmDeleteForce({{ $item->id }})"><i
                                                        class="fas fa-trash"></i></a> --}}
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
        function confirmDeleteForce(itemId) {
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
                    $('#deleteForceForm' + itemId).submit();
                }
            });
        }


        function confirmDelete(itemId){
            $('#deleteForm' + itemId).submit();
        }

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
                    var createdAt = data[6]; // Index kolom `created_at`

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
