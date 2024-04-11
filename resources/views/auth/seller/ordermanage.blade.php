@extends('auth.admin.layout-master')
@section('content')
    <style>
        .product-dropdown>.dropdown-divider {
            border-top: 1px solid #2c2e33;
        }

        .modal-dialog-scrollable .modal-content {
            overflow: unset;
        }

        .tt-menu {
            display: none !important;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Orders Management </h3>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="create-order-tab" data-bs-toggle="tab" data-bs-target="#create-order"
                        type="button" role="tab" aria-controls="create-order" aria-selected="true">Create New</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="order-ready-tab" data-bs-toggle="tab" data-bs-target="#order-ready"
                        type="button" role="tab" aria-controls="order-ready" aria-selected="false">Ready</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="delivery-order-tab" data-bs-toggle="tab" data-bs-target="#delivery-order"
                        type="button" role="tab" aria-controls="delivery-order"
                        aria-selected="false">Delivering</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rror-order-tab" data-bs-toggle="tab" data-bs-target="#error-order"
                        type="button" role="tab" aria-controls="error-order" aria-selected="false">Error</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="create-order" role="tabpanel" aria-labelledby="create-order-tab">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title">Create New Order</h4>
                            </br>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="card-body p-1">
                                        <h4 class="card-title">Products</h4>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="result">
                                                <thead>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 mt-4">
                                    <div class="card bg-dark">
                                        <div id='order-details-after' class="card-body d-none">
                                            {{-- <h4 class="card-title">Order</h4> --}}
                                            <div class="order-details ">
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <p class="text-light">Code: <span id="order-code"></span></p>
                                                        <p class="text-light">Table: <span id="order-table"></span></p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <p id="order-code" class="text-light">Staff:
                                                            {{ Auth::user()->name }}
                                                        </p>
                                                        <p id="order-date" class="text-light"></p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table m-md-0" id="productOrder">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <div class="row mt-4 mx-0">
                                                        <div class="col-lg-7 ms-auto p-0">
                                                        </div>
                                                        <div class="col-lg-5 ms-auto p-0">
                                                            <h6>Total: <span id="total"></span> VND</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" mt-2 p-0 row">
                                                <button class="btn btn-inverse-primary btn-md" id="submit-order-btn"
                                                    style="flex: auto" type="button">
                                                    Create
                                                </button>
                                                <button class="btn btn-inverse-danger btn-md mt-2" id="cancel-order-btn"
                                                    style="flex: auto" type="button">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>

                                        <div id='order-details-before' class="card-body">
                                            {{-- <h4 class="card-title">Order</h4> --}}
                                            <div class="order-details-before">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <p id="" class="text-light">Code: </p>
                                                        <p id="" class="text-light">Table: </p>
                                                    </div>
                                                    <div class="col-7">
                                                        <p id="" class="text-light">Staff:
                                                            {{ Auth::user()->name }}
                                                        </p>
                                                        <p id="" class="text-light">Date: </p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover m-md-0" id="productOrderBefore">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <div class="row mt-4 mx-0">
                                                        <div class="col-md-7 ms-auto p-0">
                                                        </div>
                                                        <div class="col-md-5 ms-auto p-0">
                                                            <h6 id="total">ToTal:</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="order-ready" role="tabpanel" aria-labelledby="order-ready-tab">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title">Order Ready</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="orderReadyTable">
                                    <thead>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="delivery-order" role="tabpanel" aria-labelledby="delivery-order-tab">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title">Order Delivering</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="orderDelivery">
                                    <thead>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="error-order" role="tabpanel" aria-labelledby="error-order-tab">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title">Order Error</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="orderError">
                                    <thead>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card mt-5 d-none" id="order-err-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0" id="error-order">
                                        Edit Order
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="card-body p-1">
                                                <h4 class="card-title">Products</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" id="result-product-edit">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 mt-4">
                                            <div class="card bg-dark">
                                                <div id='order-error' class="card-body">
                                                    <div class="order-err-details ">
                                                        <div class="row">
                                                            <div class="col-lg-5">
                                                                <p class="text-light">Code: <span
                                                                        id="order-code-err"></span></p>
                                                                <p class="text-light">Table: <span
                                                                        id="order-table-err"></span></p>
                                                            </div>
                                                            <div class="col-lg-7">
                                                                <p id="order-staff" class="text-light">Staff:
                                                                    {{ Auth::user()->name }}
                                                                </p>
                                                                <p class="text-light">Type: <span
                                                                        id="order-type-err" class="text-info"></span></p>
                                                                <p id="order-date" class="text-light"></p>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table m-md-0" id="productOrderError">
                                                                <thead>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                            <div class="row mt-4 mx-0">
                                                                <div class="col-lg-7 ms-auto p-0">
                                                                </div>
                                                                <div class="col-lg-5 ms-auto p-0">
                                                                    <h6>Total: <span id="total"></span> VND</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" mt-2 p-0 row">
                                                        <button class="btn btn-inverse-primary btn-md"
                                                            id="submit-order-btn" style="flex: auto" type="button">
                                                            Submit
                                                        </button>
                                                        <button class="btn btn-inverse-danger btn-md mt-2"
                                                            id="cancel-order-btn" style="flex: auto" type="button">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/ordermanage.js') }}"></script>
@endsection
