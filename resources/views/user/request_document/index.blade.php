@extends('layouts.master')

@section('title')
    Request Feedback/Drafting Document |
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
                                <li class="breadcrumb-item active">Request Feedback/Drafting Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Feedback/Drafting Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('request-document.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Create Request</a>
                            </div>
                            <h4 class="header-title">Data Request Feedback/Drafting Document</h4>
                            <br><br>
                            <table class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Judul</th>
                                        <th>Para Pihak</th>
                                        <th>Ruang Lingkup </th>
                                        <th>Send Email</th>
                                        <th>Created By</th>
                                        <th>SLA</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['tracking'] as $item)
                                        <tr>
                                            <td class="align-middle">{{ $no++ }}</td>
                                            <td class="align-middle">
                                                @if ($item->type === 'Contract')
                                                    Contract/Letter
                                                @else
                                                    {{ $item->type }}
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($item->type != 'Contract')
                                                    @if ($item->is_cancel)
                                                        <span class="badge bg-danger">Cancel</span>
                                                    @else
                                                        @if ($item->status == 0)
                                                            <span class="badge bg-primary">Submitted</span>
                                                        @elseif ($item->status == 1)
                                                            <span class="badge bg-primary">Preparation</span>
                                                        @elseif ($item->status == 2)
                                                            <span class="badge bg-primary">Registration</span>
                                                        @elseif ($item->status == 3)
                                                            <span class="badge bg-primary">Complete</span>
                                                        @elseif ($item->status == 4)
                                                            <span class="badge bg-success">Filing</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($item->is_cancel)
                                                        <span class="badge bg-danger">Cancel</span>
                                                    @else
                                                        @if ($item->status == 0)
                                                            <span class="badge bg-primary">Submitted</span>
                                                        @elseif ($item->status == 1)
                                                            <span class="badge bg-primary">Legal Drafting</span>
                                                        @elseif ($item->status == 2)
                                                            <span class="badge bg-primary">Send Draft</span>
                                                        @elseif ($item->status == 3)
                                                            <span class="badge bg-primary">Feedback</span>
                                                        @elseif ($item->status == 4)
                                                            <span class="badge bg-primary">Revisi</span>
                                                        @elseif ($item->status == 5)
                                                            <span class="badge bg-primary">Send Rekanan</span>
                                                        @elseif ($item->status == 6)
                                                            <span class="badge bg-primary">Negosiasi</span>
                                                        @elseif ($item->status == 7)
                                                            <span class="badge bg-success">Filing</span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($item->type == 'License')
                                                    <a
                                                        href="{{ route('request-document.show-license', Hashids::encode($item->id)) }}">{{ Str::limit($item->title, 50, '...') }}</a>
                                                @elseif ($item->type == 'HAKI')
                                                    <a
                                                        href="{{ route('request-document.show-haki', Hashids::encode($item->id)) }}">{{ Str::limit($item->title, 50, '...') }}</a>
                                                @else
                                                    <a
                                                        href="{{ route('request-document.show', Hashids::encode($item->id)) }}">{{ Str::limit($item->title, 50, '...') }}</a>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @foreach (getPihak($item->id) as $i)
                                                    <span class="badge bg-success">{{ $i->name }}</span><br>
                                                @endforeach
                                            </td>
                                            <td class="align-middle">{{ $item->scope }}</td>
                                            <td class="align-middle">{{ $item->email }}</td>
                                            <td class="align-middle">{{ getUserName($item->created_by)->name }}</td>
                                            <td class="align-middle">
                                                @if ($item->type != 'Contract')
                                                    @if ($item->status == 4)
                                                        {{ getSlaRequestDocumentFour($item->id) }} Hari
                                                    @endif
                                                @else
                                                    @if ($item->status == 7)
                                                        {{ getSlaRequestDocument($item->id) }} Hari
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($item->status == 0 && $item->is_cancel == 0)
                                                    <a href="{{ route('request-document.edit', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="Edit" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-pen"></i></a>
                                                @endif
                                                @if ($item->type == 'License')
                                                    <a href="{{ route('request-document.show-license', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="View Detail" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                @elseif ($item->type == 'HAKI')
                                                    <a href="{{ route('request-document.show-haki', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="View Detail" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                @else
                                                    <a href="{{ route('request-document.show', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="View Detail" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                @endif
                                                <a href="#"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light history_process"
                                                    title="History Process" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top" data-id="{{ $item->id }}"><i
                                                        class="mdi mdi-book-clock-outline"></i></a>
                                                @if ($item->status == 0 && $item->is_cancel == 0)
                                                    <a href="#"
                                                        class="btn btn-danger btn-xs d-inline waves-effect waves-light btn_cancel"
                                                        title="Cancel Request" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top" data-id="{{ $item->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#cancel-modal"><i
                                                            class="fas fa-times"></i></a>
                                                @endif
                                                {{-- <form action="{{ route('license.destroy', Hashids::encode($item->id )) }}" method="POST" onclick="deleteFunction()" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-light btn-xs d-inline waves-effect waves-light" title="Delete" tabindex="0" data-plugin="tippy" data-tippy-placement="top"><i class="fas fa-trash-alt" onsubmit="deleteFunction()"></i></button>
                                            </form> --}}
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
    <div id="detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">History Process</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>History Process</h6>
                    <p>History Process Request Document.</p>
                    <div id="modal-table">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="cancel-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Cancel Request</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('request-document.cancel') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <h3>Apakah anda yakin ?</h3>
                        <p>Tekan confirm untuk membatalkan request ini.</p>
                        <input type="hidden" name="id_cancel" id="id_cancel">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Confirm, cancel</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.history_process').click(function() {
                var doc_id = $(this).data('id');

                $.ajax({
                    url: '{{ url('request-document/get-data') }}/' + doc_id,
                    type: 'get',
                    data: {
                        doc_id: doc_id
                    },
                    success: function(response) {
                        // Add response in Modal body
                        $('#modal-table').html(response);

                        // Display Modal
                        $('#detail-modal').modal('show');
                    }
                });
            });

            $('.btn_cancel').click(function() {
                document.getElementById("id_cancel").value = $(this).attr('data-id');
            });
        });
    </script>
@endsection
