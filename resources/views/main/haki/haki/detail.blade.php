@extends('layouts.master')

@section('title')
    Detail HAKI |
@endsection

@section('css')
    <link href="{{ asset('assets/dearflip/css/min.css') }}" rel="stylesheet" type="text/css">
    <!-- Icons Stylesheet -->
    <link href="{{ asset('assets/dearflip/css/themify-icons.min.css') }}" rel="stylesheet" type="text/css">
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
                                <li class="breadcrumb-item"><a href="{{ route('haki.index') }}">HAKI</a></li>
                                <li class="breadcrumb-item active">HAKI</li>
                            </ol>
                        </div>
                        <h4 class="page-title">HAKI</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Current HAKI Document</h4>
                            <br>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Judul HAKI</h5>
                                        <p>{{ $data['contract']->description }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Category </h5>
                                        <p>HAKI</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Contract Number </h5>
                                        <p>{{ $data['contract']->contract_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Company</h5>
                                        <p>{{ $data['contract']->company }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Effective Date | Renew Due Date</h5>
                                        <p>{{ formatOnlyDate($data['contract']->contract_date) }} |
                                            @if ($data['contract']->is_unlimited_duration)
                                                Unlimited
                                            @else
                                                {{ formatOnlyDate(date('Y-m-d', strtotime($data['contract']->contract_date . ' + ' . $data['contract']->duration_days . ' days'))) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Days Remaining </h5>
                                        @if ($data['contract']->is_unlimited_duration)
                                            Unlimited
                                        @else
                                            @php
                                                $end_contract = date(
                                                    'Y-m-d',
                                                    strtotime(
                                                        $data['contract']->contract_date .
                                                            ' + ' .
                                                            $data['contract']->duration_days .
                                                            ' days',
                                                    ),
                                                );
                                                $now = date('Y-m-d');
                                                $diff = (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                            @endphp

                                            <p>
                                                {{ $diff }} days <br>
                                                @if ($diff > 0)
                                                    @php $remain = getTimeLater(strtotime($end_contract)); @endphp
                                                @else
                                                    @php $remain = getTimeAgo(strtotime($end_contract)); @endphp
                                                @endif
                                                {{ $remain }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Launch By</h5>
                                        <p>{{ $data['contract']->launch_by }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Tipe HAKI</h5>
                                        <p>{{ $data['contract']->HakiType->name }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Duty </h5>
                                        <p>{{ $data['contract']->Duty->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @if ($data['contract']->is_extend_automatically)
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <h5 class="mt-0">Extend</h5>
                                            <span class="badge bg-warning"><i class="fas fa-history"></i> Extend
                                                Automatically</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($data['contract']->is_unlimited_duration)
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <h5 class="mt-0">Duration Type</h5>
                                            <span class="badge bg-primary">Unlimited Duration</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <h5 class="mt-0">Department In Charge</h5>
                                        <p>
                                            @foreach (getDocumentScopeByBase($data['contract']->id) as $ds)
                                                <span class="badge bg-info">{{ $ds->department_name }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <h5 class="mt-0">PIC</h5>
                                        <p>
                                            @foreach (getPicDocument($data['current_contract']->id) as $pd)
                                                <span class="badge bg-primary">{{ $pd->name }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Timeline HAKI</h4>
                            <br>
                            <div class="track-order-list">
                                <ul class="list-unstyled">
                                    @foreach ($data['extend-contract'] as $ch)
                                        <li class="completed">
                                            <h5 class="mt-0 mb-1">Deal Date : {{ formatOnlyDate($ch->deal_date) }} with
                                                duration {{ $ch->duration_days }} days</h5>
                                            <p class="text-muted">Judul : {{ $ch->description }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>


                        </div>
                    </div> <!-- end card -->
                </div><!-- end col-->

                <div class="col-9">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">History Contract</h4>
                            <br><br>
                            <table id="" class="table nowrap w-100 scroll-horizontal-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nomor Kontrak </th>
                                        <th>Judul </th>
                                        <th>Deal Date - End Date </th>
                                        <th>Duration</th>
                                        <th>Download (times)</th>
                                        <th>File</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @php
                                    $no = 1;
                                @endphp
                                <tbody>
                                    @foreach ($data['extend-contract'] as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                @foreach ($item->DocumentFinalAttachment as $dfa)
                                                    <a href="#" id="my_flipbook" class="_df_custom"
                                                        backgroundcolor="gray"
                                                        source="{{ asset('upload/document/contract') }}/{{ $dfa->file }}"><i
                                                            class="fas fa-eye"></i>
                                                        {{ Str::limit($dfa->file, 20) }}</a><br>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ formatOnlyDate($item->deal_date) }} |
                                                @if ($item->is_unlimited_duration)
                                                    Unlimited
                                                @else
                                                    {{ formatOnlyDate(date('Y-m-d', strtotime($item->contract_date . ' + ' . $item->duration_days . ' days'))) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->is_unlimited_duration)
                                                    Unlimited
                                                @else
                                                    {{ $item->duration_days }} days
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->download_count }}</td>
                                            <td>
                                                @foreach ($item->DocumentFinalAttachment as $dfad)
                                                    <a href="{{ route('haki.download', Hashids::encode($dfad->id)) }}"><i
                                                            class="fas fa-download"></i>
                                                        {{ Str::limit($dfad->file, 20) }}</a><br>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($item->is_extend == 1)
                                                    <span class="badge bg-success">Current Document</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->
@endsection

@section('js')
    <!-- jQuery  -->
    <script src="{{ asset('assets/dearflip/js/libs/jquery.min.js') }}" type="text/javascript"></script>
    <!-- Flipbook main Js file -->
    <script src="{{ asset('assets/dearflip/js/dflip.min.js') }}" type="text/javascript"></script>
    <!-- Flipbook main Js file -->

    <script>
        // var my_flipbook = {enableDownload:false};
    </script>
@endsection
