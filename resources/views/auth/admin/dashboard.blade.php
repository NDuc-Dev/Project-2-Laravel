@extends('auth.admin.layout-master')
@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div id="daily_income" class="d-flex align-items-center align-self-start">
                                        <h4 id="daily-income-val" class="mb-0"></h4>
                                        <p id="daily-income-growth-rate" class="ms-2 mb-0 font-weight-medium"></p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-daily-income">
                                        <span id="daily-income-icon-down-up" class="mdi icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Daily Income</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 id="daily-order-val" class="mb-0"></h4>
                                        <p id="daily-order-growth-rate" class="ms-2 mb-0 font-weight-medium"></p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-daily-order">
                                        <span id="daily-order-icon-down-up" class="mdi icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Daily Order</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Revenue on month</h4>
                            <canvas id="transaction-history" class="transaction-chart"></canvas>
                            <div class="row">
                                <div class="bg-gray-dark col-12 my-2">
                                    <div class="text-center">
                                        <h6 class="mb-1">Total</h6>
                                        <h6 id="total-revenue-val" class="font-weight-bold text-success"></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="bg-gray-dark col-sm-6 my-2">
                                    <div class="text-center">
                                        <h6 class="mb-1"style="color: rgba(54, 162, 235, 0.5);">Direct Revenue</h6>
                                        <h6 id="direct-revenue-val" class="font-weight-bold mb-0"
                                            style="color: rgba(54, 162, 235, 1);"></h6>
                                    </div>
                                </div>
                                <div class="bg-gray-dark col-sm-6 my-2">
                                    <div class="text-center">
                                        <h6 class="mb-1" style="color: rgba(255, 99, 132, 0.5);">Online Revenue</h6>
                                        <h6 id="online-revenue-val" class="font-weight-bold mb-0"
                                            style="color:rgba(255,99,132,1); "></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-5">Rush Hour</h4>
                            <canvas id="lineChart" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Revenue</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h4 id="total-revenue-val-1" class="mb-0"></h4>
                                        <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
                                    </div>
                                    <h6 class="text-muted font-weight-nozrmal">11.38% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Direct Revenue</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h4 id="direct-revenue-val-1" class="mb-0"></h4>
                                        <p class="text-success ms-2 mb-0 font-weight-medium">+8.3%</p>
                                    </div>
                                    <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-wallet-travel text-danger ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Online Revenue</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h4 id="online-revenue-val-1" class="mb-0"></h4>
                                        <p class="text-danger ms-2 mb-0 font-weight-medium">-2.1% </p>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">2.27% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-monitor text-success ms-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    @endsection
