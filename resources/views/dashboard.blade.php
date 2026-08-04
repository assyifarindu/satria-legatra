@extends('layouts.master')

@section('title')
    Dashboard |
@endsection

@section('css')
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
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card" style="height: 480px">
                        <div class="card-body">
                            <h4 class="header-title mb-0">Jumlah Dokumen</h4>

                            <div id="cardCollpase5" class="collapse pt-3 show" dir="ltr">
                                <div id="bar-chart" class="apex-charts" data-colors="#6658dd,#1abc9c,#CED4DC"></div>
                            </div> <!-- collapsed end -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-xl-6">
                    <div class="card" style="height: 480px">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Request Documents</h4>

                            <div class="table-responsive" style="max-height: 400px; max: 400px;">
                                <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Request</th>
                                            <th>Jenis</th>
                                            <th>Oleh</th>
                                            <th>Tipe Request</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['tracking'] as $rd)
                                            @if ($rd->type == 'Contract' && $rd->status != 7)
                                                <tr>
                                                    <td>
                                                        <h5 class="m-0 fw-normal">
                                                            @if ($rd->type == 'Contract')
                                                                <a
                                                                    href="{{ route('tracking.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                            @elseif ($rd->type == 'License')
                                                                <a
                                                                    href="{{ route('tracking-license.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                            @else
                                                                <a
                                                                    href="{{ route('tracking-haki.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                            @endif
                                                        </h5>
                                                    </td>

                                                    <td>
                                                        {{ $rd->type }}
                                                    </td>

                                                    <td>
                                                        {{ getUserName($rd->created_by)->name }}
                                                    </td>

                                                    <td>
                                                        @if ($rd->is_extend == 1)
                                                            <span class="badge bg-primary">Extend Document</span>
                                                        @else
                                                            <span class="badge bg-warning">New Document</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($rd->type != 'Contract')
                                                            @if ($rd->status == 0)
                                                                <span class="badge bg-primary">Submitted</span>
                                                            @elseif ($rd->status == 1)
                                                                <span class="badge bg-primary">Preparation</span>
                                                            @elseif ($rd->status == 2)
                                                                <span class="badge bg-primary">Registration</span>
                                                            @elseif ($rd->status == 3)
                                                                <span class="badge bg-primary">Complete</span>
                                                            @elseif ($rd->status == 4)
                                                                <span class="badge bg-success">Filing</span>
                                                            @endif
                                                        @else
                                                            @if ($rd->status == 0)
                                                                <span class="badge bg-primary">Submitted</span>
                                                            @elseif ($rd->status == 1)
                                                                <span class="badge bg-primary">Legal Drafting</span>
                                                            @elseif ($rd->status == 2)
                                                                <span class="badge bg-primary">Send Draft</span>
                                                            @elseif ($rd->status == 3)
                                                                <span class="badge bg-primary">Feedback</span>
                                                            @elseif ($rd->status == 4)
                                                                <span class="badge bg-primary">Revisi</span>
                                                            @elseif ($rd->status == 5)
                                                                <span class="badge bg-primary">Send Rekanan</span>
                                                            @elseif ($rd->status == 6)
                                                                <span class="badge bg-primary">Negosiasi</span>
                                                            @elseif ($rd->status == 7)
                                                                <span class="badge bg-success">Complete</span>
                                                            @endif
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($rd->type == 'Contract')
                                                            <a href="{{ route('tracking.show', Hashids::encode($rd->id)) }}"
                                                                class="btn btn-xs btn-light"><i class="mdi mdi-eye"></i></a>
                                                        @elseif ($rd->type == 'License')
                                                            <a href="{{ route('tracking-license.show', Hashids::encode($rd->id)) }}"
                                                                class="btn btn-xs btn-light"><i class="mdi mdi-eye"></i></a>
                                                        @else
                                                            <a href="{{ route('tracking-haki.show', Hashids::encode($rd->id)) }}"
                                                                class="btn btn-xs btn-light"><i class="mdi mdi-eye"></i></a>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @else
                                                @if ($rd->type != 1 && $rd->status < 4)
                                                    <tr>
                                                        <td>
                                                            <h5 class="m-0 fw-normal">
                                                                @if ($rd->type == 'Contract')
                                                                    <a
                                                                        href="{{ route('tracking.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                                @elseif ($rd->type == 'License')
                                                                    <a
                                                                        href="{{ route('tracking-license.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                                @else
                                                                    <a
                                                                        href="{{ route('tracking-haki.show', Hashids::encode($rd->id)) }}">{{ $rd->title }}</a>
                                                                @endif
                                                            </h5>
                                                        </td>

                                                        <td>
                                                            {{ $rd->type }}
                                                        </td>

                                                        <td>
                                                            {{ getUserName($rd->created_by)->name }}
                                                        </td>

                                                        <td>
                                                            @if ($rd->is_extend == 1)
                                                                <span class="badge bg-primary">Extend Document</span>
                                                            @else
                                                                <span class="badge bg-warning">New Document</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($rd->type != 'Contract')
                                                                @if ($rd->status == 0)
                                                                    <span class="badge bg-primary">Submitted</span>
                                                                @elseif ($rd->status == 1)
                                                                    <span class="badge bg-primary">Preparation</span>
                                                                @elseif ($rd->status == 2)
                                                                    <span class="badge bg-primary">Registration</span>
                                                                @elseif ($rd->status == 3)
                                                                    <span class="badge bg-primary">Complete</span>
                                                                @elseif ($rd->status == 4)
                                                                    <span class="badge bg-success">Filing</span>
                                                                @endif
                                                            @else
                                                                @if ($rd->status == 0)
                                                                    <span class="badge bg-primary">Submitted</span>
                                                                @elseif ($rd->status == 1)
                                                                    <span class="badge bg-primary">Legal Drafting</span>
                                                                @elseif ($rd->status == 2)
                                                                    <span class="badge bg-primary">Send Draft</span>
                                                                @elseif ($rd->status == 3)
                                                                    <span class="badge bg-primary">Feedback</span>
                                                                @elseif ($rd->status == 4)
                                                                    <span class="badge bg-primary">Revisi</span>
                                                                @elseif ($rd->status == 5)
                                                                    <span class="badge bg-primary">Send Rekanan</span>
                                                                @elseif ($rd->status == 6)
                                                                    <span class="badge bg-primary">Negosiasi</span>
                                                                @elseif ($rd->status == 7)
                                                                    <span class="badge bg-success">Complete</span>
                                                                @endif
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($rd->type == 'Contract')
                                                                <a href="{{ route('tracking.show', Hashids::encode($rd->id)) }}"
                                                                    class="btn btn-xs btn-light"><i
                                                                        class="mdi mdi-eye"></i></a>
                                                            @elseif ($rd->type == 'License')
                                                                <a href="{{ route('tracking-license.show', Hashids::encode($rd->id)) }}"
                                                                    class="btn btn-xs btn-light"><i
                                                                        class="mdi mdi-eye"></i></a>
                                                            @else
                                                                <a href="{{ route('tracking-haki.show', Hashids::encode($rd->id)) }}"
                                                                    class="btn btn-xs btn-light"><i
                                                                        class="mdi mdi-eye"></i></a>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card" style="height: 480px">
                        <div class="card-body">

                            <h4 class="header-title mb-3">Alert Document</h4>

                            <div class="table-responsive" style="max-height: 400px; max: 400px;">
                                <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Judul</th>
                                            <th>Nomor</th>
                                            <th>Renew Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['contract'] as $contract)
                                            @php
                                                $end_contract = date(
                                                    'Y-m-d',
                                                    strtotime(
                                                        $contract->contract_date .
                                                            ' + ' .
                                                            $contract->duration_days .
                                                            ' days',
                                                    ),
                                                );
                                                $now = date('Y-m-d');
                                                $diff = (strtotime($end_contract) - strtotime($now)) / 60 / 60 / 24;
                                            @endphp
                                            @if ($diff <= $contract->alert_days)
                                                <tr>
                                                    <td>
                                                        {{ $contract->description }}
                                                    </td>
                                                    <td>
                                                        @if ($contract->contract_number == '0')
                                                            <a
                                                                href="{{ route('contract.detail', Hashids::encode($contract->id)) }}">-</a>
                                                        @else
                                                            <a
                                                                href="{{ route('contract.detail', Hashids::encode($contract->id)) }}">{{ $contract->contract_number }}</a>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ date('Y-m-d', strtotime($contract->contract_date . ' + ' . $contract->duration_days . ' days')) }}
                                                    </td>

                                                    <td>
                                                        <a href="{{ route('license-alert.show', Hashids::encode($contract->id)) }}"
                                                            class="btn btn-light btn-xs d-inline waves-effect waves-light"
                                                            title="Send email alert" tabindex="0" data-plugin="tippy"
                                                            data-tippy-placement="top"><i
                                                                class="fas fa-paper-plane"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->

                <div class="col-xl-6">
                    <div class="card" style="height: 480px">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Template Document</h4>

                            <div class="table-responsive" style="max-height: 400px; max: 400px;">
                                <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Company</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($data['template'] as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->company_name }}</td>
                                                <td>
                                                    <a href="{{ route('master-template.download', $item->id) }}"><i
                                                            class="fas fa-donwnload"></i> Download</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div>
@endsection

@section('js')
    <!-- Third Party js-->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
    <script src="https://apexcharts.com/samples/assets/ohlc.js"></script>

    <script>
        (dataColors = $("#bar-chart").data("colors")) &&
        (colors = dataColors.split(","));
        options = {
            chart: {
                height: 380,
                type: "bar",
                toolbar: {
                    show: !1
                }
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    endingShape: "rounded",
                    columnWidth: "55%"
                },
            },
            dataLabels: {
                enabled: !1
            },
            stroke: {
                show: !0,
                width: 2,
                colors: ["transparent"]
            },
            colors: colors,
            series: [
                @foreach ($data['document'] as $item)
                    {
                        name: "{{ $item->short_name }}",
                        data: [
                            @foreach ($item->document as $val)
                                @if ($val->category == 1)
                                    {{ $val->count_document }},
                                @endif
                                @if ($val->category == 2)
                                    {{ $val->count_document }},
                                @endif
                                @if ($val->category == 3)
                                    {{ $val->count_document }},
                                @endif
                            @endforeach
                        ]
                    },
                @endforeach
            ],
            xaxis: {
                categories: [
                    "Contract/Letter",
                    "License",
                    "HAKI"
                ],
            },
            legend: {
                offsetY: 5
            },
            yaxis: {
                title: {
                    text: "Total Document"
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                row: {
                    colors: ["transparent", "transparent"],
                    opacity: 0.2
                },
                borderColor: "#f1f3fa",
                padding: {
                    bottom: 10
                },
            },
            tooltip: {
                y: {
                    formatter: function(e) {
                        return e + " document";
                    },
                },
            },
        };
        (chart = new ApexCharts(
            document.querySelector("#bar-chart"),
            options
        )).render();
    </script>
@endsection
