@extends('layouts.master')

@section('title')
    Alert HAKI |
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
                                <li class="breadcrumb-item active">Alert HAKI</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Alert HAKI</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Alert HAKI</h4>
                            <br><br>
                            <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contract Number</th>
                                        <th>Contract Description</th>
                                        <th>Company</th>
                                        <th>Tipe Haki</th>
                                        <th>Duties</th>
                                        <th>Launch By</th>
                                        <th>Deal Date</th>
                                        <th>Renew Due Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['contract'] as $item)
                                        @php
                                            $end_contract = date(
                                                'Y-m-d',
                                                strtotime(
                                                    $item->contract_date . ' + ' . $item->duration_days . ' days',
                                                ),
                                            );
                                            $now = date('Y-m-d');
                                            $diff = (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                        @endphp
                                        @if ($diff <= $item->alert_days)
                                            <tr>
                                                @php
                                                    $end_contract = date(
                                                        'Y-m-d',
                                                        strtotime(
                                                            $item->contract_date .
                                                                ' + ' .
                                                                $item->duration_days .
                                                                ' days',
                                                        ),
                                                    );
                                                    $now = date('Y-m-d');
                                                    $diff = (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                                @endphp

                                                <td class="align-middle">{{ $no++ }}</td>
                                                <td class="align-middle">
                                                    @if ($item->contract_number == '0')
                                                        <a
                                                            href="{{ route('haki.detail', Hashids::encode($item->id)) }}">-</a>
                                                    @else
                                                        <a
                                                            href="{{ route('haki.detail', Hashids::encode($item->id)) }}">{{ $item->contract_number }}</a>
                                                    @endif
                                                </td>
                                                <td class="align-middle"><a
                                                        href="{{ route('contract.detail', Hashids::encode($item->id)) }}">{{ $item->description }}</a>
                                                </td>
                                                <td class="align-middle">{{ $item->company }}</td>
                                                <td class="align-middle">{{ $item->HakiType->name }}</td>
                                                <td class="align-middle">{{ $item->Duty->name }}</td>
                                                <td class="align-middle">{{ $item->launch_by }}</td>
                                                <td class="align-middle">{{ $item->contract_date }}</td>
                                                <td class="align-middle">
                                                    {{ date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days')) }}
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('haki.detail', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                        title="Detail" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('haki.show', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                        title="Ringkasan" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                                    <a href="{{ route('license-alert.show', Hashids::encode($item->id)) }}"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                        title="Send email alert" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top"><i class="fas fa-paper-plane"></i></a>
                                                    <a href="#"
                                                        class="btn btn-light btn-xs d-inline waves-effect waves-light history_email"
                                                        title="History Process" tabindex="0" data-plugin="tippy"
                                                        data-tippy-placement="top" data-id="{{ $item->id }}"><i
                                                            class="mdi mdi-book-clock-outline"></i></a>
                                                </td>

                                            </tr>
                                        @endif
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

    <div id="view_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Detail Contract</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Company</h5>
                                <p id="md_company"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Contract/SN Num</h5>
                                <p id="md_number"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Category</h5>
                                <p>Contract</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>PIC</h5>
                                <p id="md_picname"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Deal Date</h5>
                                <p id="md_dealdate"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Duration</h5>
                                <p id="md_duration"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Contract Note</h5>
                                <p id="md_note"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <h5>Contract File</h5>
                                <p><a href="" id="md_file"></a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div id="extend_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Extend Contract</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <h5>Company</h5>
                                <p id="em_company"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <h5>Contract Number</h5>
                                <p id="em_number"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <h5>Deal Date</h5>
                                <p id="em_dealdate"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <h5>Renew Date</h5>
                                <p id="em_renewdate"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <h5>Contract Note</h5>
                                <p id="em_note"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <h5>Days Remaining</h5>
                                <p id="em_remain"></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <form action="{{ route('contract.store') }}" id="fileUploadForm" enctype="multipart/form-data"
                        method="post">
                        @csrf
                        <input type="hidden" name="doc_id" id="doc_id">

                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <label class="form-label">Extend Contract Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="contract_number"
                                    placeholder="Contract number" required>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <label class="form-label">Note <span class="text-danger">*</span></label>
                                <textarea name="note" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <label class="form-label">Duration <span class="text-danger">*</span></label>
                                <select name="duration" class="form-control" id="">
                                    <option>-- Select Duration --</option>
                                    @foreach ($data['duration'] as $i)
                                        <option value="{{ $i->id }}">{{ $i->duration }} Days</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-12 mt-1">
                                <label class="form-label">Deal Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control basic-datepicker" name="deal_date"
                                    placeholder="Choose Closing Meeting date.." required>
                            </div>
                        </div>

                        <label class="form-label">Dokumen Pendukung <span class="text-danger">*</span></label>
                        <input type="file" name="file" required data-plugins="dropify" data-height="75"
                            accept="application/pdf" />

                        <div class="form-group mt-2">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                    style="width: 0%"></div>
                            </div>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div id="email-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">History Email</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>History Email</h6>
                    <p>History Email Alert.</p>
                    <div id="modal-table">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.btn_view').click(function() {
                $("#md_company").text($(this).attr('data-company'));
                $("#md_number").text($(this).attr('data-number'));
                $("#md_picname").text($(this).attr('data-picname'));
                $("#md_dealdate").text($(this).attr('data-dealdate'));
                $("#md_duration").text($(this).attr('data-duration'));
                $("#md_note").text($(this).attr('data-note'));
                $("#md_file").text($(this).attr('data-file'));
                var newURL = "{{ url('contract/download') }}/" + $(this).attr('data-docid');
                document.getElementById("md_file").href = newURL;
            });
        });

        $(document).ready(function() {
            $('.btn_extend').click(function() {
                $("#em_company").text($(this).attr('data-company'));
                $("#em_number").text($(this).attr('data-number'));
                $("#em_dealdate").text($(this).attr('data-dealdate'));
                $("#em_renewdate").text($(this).attr('data-renewdate'));
                $("#em_note").text($(this).attr('data-note'));
                $("#em_remain").text($(this).attr('data-remain'));
                document.getElementById("doc_id").value = $(this).attr('data-docid');

            });
        });

        $(function() {
            $(document).ready(function() {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function() {
                        var percentage = '0';
                    },
                    uploadProgress: function(event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage + '%', function() {
                            return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function(xhr) {
                        location.reload();
                    }
                });
            });
        });

        function confirmation() {
            event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: "Are you sure send warning to user?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, Send Warning!",
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

        $(document).ready(function() {
            $('.history_email').click(function() {
                var doc_id = $(this).data('id');

                $.ajax({
                    url: '{{ url('contract-alert/get-data') }}/' + doc_id,
                    type: 'get',
                    data: {
                        doc_id: doc_id
                    },
                    success: function(response) {
                        // Add response in Modal body
                        $('#modal-table').html(response);

                        // Display Modal
                        $('#email-modal').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
