@extends('layouts.master')

@section('title')
    Contract Number |
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
                                <li class="breadcrumb-item active">Contract Number</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Contract Number</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Data Contract Number</h4>
                            <br><br>
                            <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contract Number</th>
					<th>Request Title</th>
                                        <th>Contract Description</th>
                                        <th>Company</th>
                                        <th>Category</th>
                                        <th>Deal Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data['contract'] as $item)
                                    
                                        <tr>
                                            <td class="align-middle">{{ $no++ }}</td>

                                            <td class="align-middle">
                                                @if ($item['contract_number'] == '0')
                                                    @if ($item['base_document_id'] != null)
                                                        <a
                                                            href="{{ route('contract.detail', Hashids::encode($item['base_document_id'])) }}">-</a>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if ($item['base_document_id'] != null)
                                                        <a
                                                            href="{{ route('contract.detail', Hashids::encode($item['base_document_id'])) }}">{{ $item['contract_number'] }}</a>
                                                    @else
                                                        {{ $item['contract_number'] }}
                                                    @endif
                                                @endif
                                            </td>
					    <td class="align-middle">
                                                {{ !empty($item['request_title']) ? $item['request_title'] : '-' }}
                                            </td>
                                            <td class="align-middle">{{ $item['description'] }}</td>
                                            <td class="align-middle">{{ $item['company'] }}</td>
                                            <td class="align-middle">
                                                @if ($item['document_type'] == 'Agg')
                                                    Perjanjian
                                                @else
                                                    {{ $item['document_type'] }}
                                                @endif
                                            </td>
                                            <td class="align-middle">{{ $item['contract_date'] }}</td>

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

    <div id="detail-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Viewer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>List Viewer Document.</p>
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

        $(document).ready(function() {
            $('.viewer').click(function() {
                var doc_id = $(this).data('id');

                $.ajax({
                    url: '{{ url('contract/get-data') }}/' + doc_id,
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
        });
    </script>
@endsection
