@extends('auth.admin.layout-master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Orders Management </h3>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Order Pending</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="orderPendingTable">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Order is being processed</h4>
                    <div id="orderProcessingBefore" class="card bg-dark">
                        <div class="card-body">
                            <div style="text-align: center;">There are no orders being processed</div>
                        </div>
                    </div>
                    <div id="orderProcessingAfter" class="card bg-dark d-none">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">Order ID: <span id="order-id"></span></div>
                                <div class="col-md-6">Status: <label class="badge badge-warning py-1">Inprogress</label>
                                </div>
                            </div>
                            <table id="orderProcessingTable" class="table"></table>
                        </div>
                        <button class="btn btn-success" id="complete-order-btn">Complete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/receiveOrder.js') }}"></script>
@endsection
