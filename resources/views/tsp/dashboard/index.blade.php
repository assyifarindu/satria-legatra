@extends('layouts.tsp_master')

@section('title')
    Dashboard |
@endsection

@section('css')
    @include('tsp.dashboard.css.contract-type-composition-css')
    @include('tsp.dashboard.css.pending-legal-review-css')
    @include('tsp.dashboard.css.pending-review-css')
    @include('tsp.dashboard.css.pending-sign-customer-css')
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="dashboard-title">
                            Contract Document Dashboard
                        </h4>

                        <div class="dashboard-subtitle">
                            Monitor and track the progress of your contract documents
                        </div>

                    </div>
                </div>
            </div>

            <!-- Dashboard Row 1 -->
            <div class="row g-3 align-items-stretch">

                <div class="col-xl-4 col-lg-6">
                    @include('tsp.dashboard.components.contract-type-composition')
                </div>

                <div class="col-xl-4 col-lg-6">
                    @include('tsp.dashboard.components.pending-legal-review')
                </div>

                <div class="col-xl-4 col-lg-6">
                    @include('tsp.dashboard.components.pending-review')
                </div>

            </div>

            <!-- Dashboard Row 2 -->
            <div class="row g-3 align-items-stretch">

                <!-- Pending Sign Customer -->
                <div class="col-xl-4 col-lg-6">

                    @include('tsp.dashboard.components.pending-sign-customer')

                </div>

                <!-- Contract Customer All Site -->
                <div class="col-xl-8 col-lg-6">

                    @include('tsp.dashboard.components.contract-customer-all-site')

                </div>

            </div>

        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('js')
    @include('tsp.dashboard.js.contract-type-composition')
    @include('tsp.dashboard.js.pending-legal-review')
    @include('tsp.dashboard.js.pending-review')
    @include('tsp.dashboard.js.pending-sign-customer')
    @include('tsp.dashboard.js.contract-customer-all-site')
@endsection
