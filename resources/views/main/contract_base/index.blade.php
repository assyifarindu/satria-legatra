@extends('layouts.master')

@section('title')
    Base Contract |
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
                                <li class="breadcrumb-item active">Base Contract</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Base Contract</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="float-end">
                                <a href="{{ route('contract.export-document-gas') }}" class="btn btn-sm btn-warning"><i
                                        class="fas fa-file-excel"></i> Export</a>
                                <a href="{{ route('contract.create') }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-plus"></i> Add Filing</a>
                            </div>
                            <h4 class="header-title">Data Base Contract ss</h4>
                            <br><br>

                            <div class="row">
                                <div class="col-md-4">
                                    <ul class="nav nav-pills navtab-bg nav-justified">
                                        <li class="nav-item">
                                            <a href="#home1" data-bs-toggle="tab" id="btn-active" aria-expanded="true"
                                                class="nav-link active">
                                                Active
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profile1" data-bs-toggle="tab" id="btn-inactive" aria-expanded="false"
                                                class="nav-link ">
                                                Inactive
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="home1">
                                    <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Due Date</th>
                                                <th>Priority</th>
                                                <th>Contract Number</th>
                                                <th>Contract Description</th>
                                                <th>Company</th>
                                                <th>Category</th>
                                                <th>Effective Date</th>
                                                <th>Renew Due Date</th>
                                                {{-- <th>Days Remaining</th> --}}
                                                {{-- <th>Start Alert</th> --}}
                                                <th>Views</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($data['contract'] as $item)
                                                <tr>
                                                    @php
                                                        $end_contract = $item->end_contract_date;
                                                        $now = date('Y-m-d');
                                                        $diff =
                                                            (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                                    @endphp

                                                    <td class="align-middle">{{ $no++ }}</td>
                                                    <td class="align-middle">
                                                        @if ($diff > 0 && $diff > $item->alert_days)
                                                            <i class="fas fa-flag text-success" title="Low"
                                                                tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @elseif ($diff > 0 && $diff <= $item->alert_days)
                                                            <i class="fas fa-flag text-warning" title="Medium"
                                                                tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]" data-tippy-arrow="true"
                                                                title="High" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @else
                                                            <i class="fas fa-flag text-danger" title="High" tabindex="0"
                                                                data-plugin="tippy" data-tippy-animation="scale"
                                                                data-tippy-inertia="true" data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($item->priority == 1)
                                                            <i class="fas fa-exclamation-triangle text-success"
                                                                title="Low" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @elseif ($item->priority == 2)
                                                            <i class="fas fa-exclamation-triangle text-warning"
                                                                title="Medium" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @else
                                                            <i class="fas fa-exclamation-triangle text-danger"
                                                                title="High" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($item->contract_number == '0')
                                                            <a
                                                                href="{{ route('license.detail', Hashids::encode($item->id)) }}">-</a>
                                                        @else
                                                            <a
                                                                href="{{ route('contract.detail', Hashids::encode($item->id)) }}">{{ $item->contract_number }}</a>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle"><a
                                                            href="{{ route('contract.detail', Hashids::encode($item->id)) }}">{{ Str::limit($item->description, 50, '...') }}</a>
                                                    </td>
                                                    <td class="align-middle">{{ $item->company }}</td>
                                                    <td class="align-middle">
                                                        @if ($item->document_type == 'Agg')
                                                            Agreement
                                                        @else
                                                            {{ $item->document_type }}
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">{{ $item->contract_date }}</td>
                                                    <td class="align-middle">
                                                        @if ($item->is_unlimited_duration)
                                                            <span class="badge bg-success">Unlimited</span>
                                                        @else
                                                            {{ date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days')) }}
                                                        @endif
                                                    </td>
                                                    {{-- <td class="align-middle">
                                                    
                                                    {{ $diff }} days <br>
                                                    @if ($diff > 0)
                                                        @php $remain = getTimeLater(strtotime($end_contract)); @endphp
                                                    @else
                                                        @php $remain = getTimeAgo(strtotime($end_contract)); @endphp
                                                    @endif
                                                    {{ $remain }}
                                                </td> --}}
                                                    {{-- <td class="align-middle">
                                                    @if (empty($item->alert_id))
                                                        @php $alert = "-"; @endphp
                                                    @else
                                                        @php $alert = $item->Alert->start_alert." Days Before"; @endphp
                                                    @endif
                                                    {{ $alert }}
                                                </td> --}}
                                                    <td class="align-middle"><a href="#"
                                                            class="d-inline waves-effect waves-light viewer"
                                                            title="Views" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top" data-id="{{ $item->id }}"><i
                                                                class="fas fa-eye"></i></a>
                                                        <span class="d-inline"> {{ countViewBaseDocument($item->id) }}
                                                            views</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('contract.edit', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Extend Document" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i
                                                                class="fas fa-arrows-alt"></i></a>
                                                        <a href="{{ route('contract.detail', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Detail" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                        {{-- <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_extend" title="Extend" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#extend_modal"
                                                    data-company="{{ $item->company }}" data-docid={{ $item->id }} data-number="{{ $item->contract_number }}" data-dealdate="{{ $item->contract_date }}"  data-note="{{ $item->description }}" data-remain="{{ $remain }} | {{ $alert }}" data-renewdate="{{ date('Y-m-d', strtotime($item->contract_date. ' + '.$item->Alert->duration.' days')) }}"><i class="fas fa-exchange-alt"></i></a>
                                                    <a href="#" class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view" title="View Detail" tabindex="0" data-plugin="tippy" data-tippy-placement="top" data-bs-toggle="modal" data-bs-target="#view_modal" 
                                                    data-company="{{ $item->company }}" data-docid={{ $item->id }} data-number="{{ $item->contract_number }}" data-dealdate="{{ $item->contract_date }}" data-picname="{{ $item->pic_name }} | {{ $item->pic_email }}" data-note="{{ $item->description }}" data-file="{{ $item->file }}" data-duration="{{ date('Y-m-d', strtotime($item->contract_date. ' + '.$item->Alert->duration.' days')) }}"><i class="fas fa-eye"></i></a> --}}
                                                        <a href="{{ route('contract.show', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light btn_view"
                                                            title="Ringkasan" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i class="fas fa-book"></i></a>
                                                        <a href="{{ route('contract.edit-document', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Edit Document" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                        <form
                                                            action="{{ route('contract.destroy', Hashids::encode($item->id)) }}"
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
                                </div>
                                <div class="tab-pane" id="profile1">

                                    <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Due Date ss</th>
                                                <th>Priority</th>
                                                <th>Contract Number</th>
                                                <th>Contract Description</th>
                                                <th>Company</th>
                                                <th>Category</th>
                                                <th>Effective Date</th>
                                                <th>Renew Due Date</th>
                                                {{-- <th>Days Remaining</th> --}}
                                                {{-- <th>Start Alert</th> --}}
                                                <th>Deleted By</th>
                                                <th>Deleted At</th>
                                                <th>Views</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($data['inactive_contract'] as $item)
                                                <tr>
                                                    @php
                                                        $end_contract = $item->end_contract_date;
                                                        $now = date('Y-m-d');
                                                        $diff =
                                                            (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                                    @endphp

                                                    <td class="align-middle">{{ $no++ }}</td>
                                                    <td class="align-middle">
                                                        @if ($diff > 0 && $diff > $item->alert_days)
                                                            <i class="fas fa-flag text-success" title="Low"
                                                                tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @elseif ($diff > 0 && $diff <= $item->alert_days)
                                                            <i class="fas fa-flag text-warning" title="Low"
                                                                tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @else
                                                            <i class="fas fa-flag text-danger" title="High"
                                                                tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($item->priority == 1)
                                                            <i class="fas fa-exclamation-triangle text-success"
                                                                title="Low" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @elseif ($item->priority == 2)
                                                            <i class="fas fa-exclamation-triangle text-warning"
                                                                title="Medium" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @else
                                                            <i class="fas fa-exclamation-triangle text-danger"
                                                                title="High" tabindex="0" data-plugin="tippy"
                                                                data-tippy-animation="scale" data-tippy-inertia="true"
                                                                data-tippy-duration="[600, 300]"
                                                                data-tippy-arrow="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($item->contract_number == '0')
                                                            <a
                                                                href="{{ route('license.detail', Hashids::encode($item->id)) }}">-</a>
                                                        @else
                                                            <a
                                                                href="{{ route('contract.detail', Hashids::encode($item->id)) }}">{{ $item->contract_number }}</a>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle"><a
                                                            href="{{ route('contract.detail', Hashids::encode($item->id)) }}">{{ Str::limit($item->description, 50, '...') }}</a>
                                                    </td>
                                                    <td class="align-middle">{{ $item->company }}</td>
                                                    <td class="align-middle">
                                                        @if ($item->document_type == 'Agg')
                                                            Agreement
                                                        @else
                                                            {{ $item->document_type }}
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">{{ $item->contract_date }}</td>
                                                    <td class="align-middle">
                                                        @if ($item->is_unlimited_duration)
                                                            <span class="badge bg-success">Unlimited</span>
                                                        @else
                                                            {{ date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days')) }}
                                                        @endif
                                                    </td>
                                                    {{-- <td class="align-middle">
                                                    
                                                    {{ $diff }} days <br>
                                                    @if ($diff > 0)
                                                        @php $remain = getTimeLater(strtotime($end_contract)); @endphp
                                                    @else
                                                        @php $remain = getTimeAgo(strtotime($end_contract)); @endphp
                                                    @endif
                                                    {{ $remain }}
                                                </td> --}}
                                                    {{-- <td class="align-middle">
                                                    @if (empty($item->alert_id))
                                                        @php $alert = "-"; @endphp
                                                    @else
                                                        @php $alert = $item->Alert->start_alert." Days Before"; @endphp
                                                    @endif
                                                    {{ $alert }}
                                                </td> --}}
                                                    <td>{{ $item->deleted_by_name }}</td>
                                                    <td>{{ $item->deleted_at != null ? formatDate($item->deleted_at) : '-' }}
                                                    </td>
                                                    <td class="align-middle"><a href="#"
                                                            class="d-inline waves-effect waves-light viewer"
                                                            title="Views" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top" data-id="{{ $item->id }}"><i
                                                                class="fas fa-eye"></i></a>
                                                        <span class="d-inline"> {{ countViewBaseDocument($item->id) }}
                                                            views</span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('contract.restore', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Restore" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i
                                                                class="fas fa-reply-all"></i></a>
                                                        <a href="{{ route('contract.detail', Hashids::encode($item->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Detail" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i class="fas fa-eye"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


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

            $('#btn-inactive').on('click', function(e) {
                $('.scroll-horizontal-datatable').DataTable().draw();
            });
            $('#btn-active').on('click', function(e) {
                $('.scroll-horizontal-datatable').DataTable().draw();
            });
        });
    </script>
@endsection
