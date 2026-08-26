@extends('layouts.tsp_master')

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
                <div>
                    <div class="card" style="height: 480px">
                        <div class="card-body">
                            <h4 class="header-title mb-0 text-center text-xl">Selamat Datang di Legatra</h4>

                            <div id="cardCollpase5" class="collapse pt-3 show" dir="ltr">
                                <div id="bar-chart" class="apex-charts" data-colors="#6658dd,#1abc9c,#CED4DC"></div>
                            </div> <!-- collapsed end -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->

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
@endsection
