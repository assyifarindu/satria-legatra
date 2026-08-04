@extends('layouts.master')

@section('title')
    Request Existing Document |
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
                                <li class="breadcrumb-item active">Request Existing Document</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Request Existing Document</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Request Existing Document</h4>
                            <br><br>
                            <div class="text-right">
                                <form class="row g-3 mb-3" action="{{ route('tracking-existing.index') }}" method="GET">
                                    {{-- <div class="col-xl-2 col-md-3">
                                        <label for="inputState">Overall Status</label>
                                        <select id="inputState" class="form-select" name="filter">
                                            <option value="all" @if ($data['filter'] == 'all') selected @endif>All
                                                Status</option>
                                            <option value="progress" @if ($data['filter'] == 'progress') selected @endif>On
                                                Progress</option>
                                            <option value="filing" @if ($data['filter'] == 'filing') selected @endif>Filing
                                            </option>
                                            <option value="cancel" @if ($data['filter'] == 'cancel') selected @endif>Cancel
                                            </option>
                                        </select>
                                    </div> --}}
                                    <div class="col-xl-2 col-md-3">
                                        <label for="inputState">Status</label>
                                        <select id="inputState" class="form-select" name="filter">
                                            <option value="all" @if ($data['filter'] == 'all') selected @endif>All
                                                Status</option>
                                            <option value="1" @if ($data['filter'] == '1') selected @endif>
                                                Submitted</option>
                                            <option value="2" @if ($data['filter'] == '2') selected @endif>Need
                                                Upload Document</option>
                                            <option value="3" @if ($data['filter'] == '3') selected @endif>
                                                Uploaded, Need Confirmation</option>
                                            <option value="4" @if ($data['filter'] == '4') selected @endif>Reject,
                                                Need Reupload</option>
                                            <option value="5" @if ($data['filter'] == '5') selected @endif>Done
                                            </option>
                                            <option value="cancel" @if ($data['filter'] == 'cancel') selected @endif>Cancel
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-md-3">
                                        <label for="inputState">Document Position</label>
                                        <select id="inputState" class="form-select" name="filter_position">
                                            <option value="all" @if ($data['filter_position'] == 'all') selected @endif>All
                                                Position</option>
                                            <option value="legal" @if ($data['filter_position'] == 'legal') selected @endif>On
                                                Legal</option>
                                            <option value="user" @if ($data['filter_position'] == 'user') selected @endif>On
                                                User</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-2 col-md-3">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            style="margin-top: 20px"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>
                            </div>
                            <table class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipe Dokumen</th>
                                        <th>Status</th>
                                        <th>Judul Dokumen Request</th>
                                        <th>Tujuan</th>
                                        <th>PIC</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['request'] as $item)
                                        <tr>
                                            <td class="align-middle">{{ $no++ }}</td>
                                            <td class="align-middle">{{ $item->type }}</td>
                                            <td class="align-middle">
                                                @if ($item->is_cancel)
                                                    <span class="badge bg-danger">Cancel</span>
                                                @else
                                                    @if ($item->status == 1)
                                                        <span class="badge bg-primary">Submitted</span>
                                                    @elseif ($item->status == 2)
                                                        <span class="badge bg-primary">Need Upload
                                                            Document</span>
                                                    @elseif ($item->status == 3)
                                                        <span class="badge bg-info">Uploaded, Need
                                                            Confirmation</span>
                                                    @elseif ($item->status == 4)
                                                        <span class="badge bg-danger">Reject, Need
                                                            Reupload</span>
                                                    @elseif ($item->status == 5)
                                                        <span class="badge bg-success">Done</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($item->title != null)
                                                    <li><a
                                                            href="{{ route('tracking-existing.show', Hashids::encode($item->id)) }}">{{ Str::limit($item->title, 50, '...') }}</a>
                                                    </li>
                                                @else
                                                    @foreach ($item->RequestExistingDocument as $rd)
                                                        <li><a
                                                                href="{{ route('tracking-existing.show', Hashids::encode($item->id)) }}">{{ Str::limit($rd->title, 50, '...') }}</a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="align-middle">{{ Str::limit($item->purpose, 50, '...') }}</td>
                                            <td class="align-middle">{{ $item->Pic->name }}</td>
                                            <td class="align-middle">{{ getUserName($item->created_by)->name }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('tracking-existing.show', Hashids::encode($item->id)) }}"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                    title="View Detail" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                <a href="#"
                                                    class="btn btn-light btn-xs d-inline waves-effect waves-light history_process"
                                                    title="History Process" tabindex="0" data-plugin="tippy"
                                                    data-tippy-placement="top" data-id="{{ $item->id }}"><i
                                                        class="mdi mdi-book-clock-outline"></i></a>
                                                @if ($item->status != 5 && $item->is_cancel == 0)
                                                    <a href="#"
                                                        class="btn btn-danger btn-xs d-inline waves-effect waves-light btn_cancel"
                                                        title="Cancel Request" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top" data-id="{{ $item->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#cancel-modal"><i
                                                            class="fas fa-times"></i></a>
                                                @endif
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
                <form action="{{ route('tracking-existing.cancel') }}" method="post">
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
                    url: '{{ url('tracking-existing/get-data') }}/' + doc_id,
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
