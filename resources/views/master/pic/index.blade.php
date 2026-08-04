@extends('layouts.master')

@section('title')
    PIC Company |
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
                                <li class="breadcrumb-item active">PIC Company</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Master PIC Company</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('master-pic.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Add PIC Company</a>
                            </div>
                            <h4 class="header-title">Master PIC Company</h4>
                            <br><br>
                            <table id="" class="table activate-select nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Email Notification</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['pic'] as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->Company->name }}</td>
                                            <td>
                                                <input type="checkbox" name="" class="form-check-input"
                                                    onchange="updateEmailNotification({{ $item->id }})"
                                                    id="check{{ $item->id }}"
                                                    @if ($item->is_email_notification == 1) checked @endif>
                                            </td>
                                            <td>
                                                <a href="{{ route('master-pic.edit', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_edit"
                                                    title="Edit" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-pen"></i></a>
                                                <form action="{{ route('master-pic.destroy', Hashids::encode($item->id)) }}"
                                                    method="POST" onclick="deleteFunction()" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                        title="Delete" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-trash-alt"
                                                            onsubmit="deleteFunction()"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->
@endsection
@section('js')
    <script>
        function deleteFunction() {
            event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1,
            }).then(function(e) {
                e.value ?
                    form.submit() :
                    e.dismiss === Swal.DismissReason.cancel &&
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your data is safe :)",
                        icon: "error",
                        confirmButtonColor: "#4a4fea",
                    });
            });
        }

        function updateEmailNotification(id) {
            if ($('#check' + id).is(':checked')) {
                $.ajax({
                    url: "{{ route('master-pic-email') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        console.log('checked');
                    }
                })
            } else {
                $.ajax({
                    url: "{{ route('master-pic-email-false') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        console.log('unchecked');
                    }
                })
            }

        }
    </script>
@endsection
