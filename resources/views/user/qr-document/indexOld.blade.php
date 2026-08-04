@extends('layouts.master')

@section('title')
    Base Request Document QR |
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
                                <li class="breadcrumb-item active">QR Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">QR Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                {{-- <a href="{{ route('request-qr-user-create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-fw fa-plus"></i> Make Request</a> --}}
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#makeRequestModal"><i class="fas fa-fw fa-plus"></i>Make
                                    Request</button>
                            </div>
                            <h4 class="header-title">History Request QR Document</h4>
                            <br><br>
                            <table class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Status Verification</th>
                                        <th>Date Verification</th>
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
                                            <td>{!! $item->status_verification == 'Verified'
                                                ? '<span class="text-success">Verified</span>'
                                                : '<span class="text-danger">Unverified</span>' !!}</td>
                                            <td>{!! $item->date_verification == null
                                                ? "<span class='text-danger'>Not yet verified</span>"
                                                : App\Helpers\MyHelper::ubahFormatTimestamp($item->date_verification) !!}</td>
                                            <td>@php echo App\Helpers\MyHelper::ubahFormatTimestamp($item->created_at)@endphp</td>
                                            <td><a href="{{ asset('storage') . '/' . $item->file }}" class="text-primary"
                                                    target="_blank"> Download</a></td>

                                            <td>
                                                @if ($item->date_verication == null && $item->status_verification == 'Unverified')
                                                    <form action="{{ route('request-qr-user-destroy', $item) }}"
                                                        method="post" id="deleteForm{{ $item->id }}">
                                                        @method('delete')
                                                        @csrf
                                                    </form>

                                                    <button onclick="confirmDelete({{ $item->id }})"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                        title="Delete" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-trash-alt text-danger"></i></button>
                                                @else
                                                    <span></span>
                                                @endif
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
    @include('user.qr-document.create')
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
                }
            });
        }
    </script>
@endsection
