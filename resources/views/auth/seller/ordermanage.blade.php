@extends('auth.admin.layout-master')
@section('content')
    <style>
        .twitter-typeahead {
            display: block !important;
        }

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
                {{-- <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Tables</a></li>
                <li class="breadcrumb-item active" aria-current="page">Basic tables</li>
              </ol>
            </nav> --}}
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Create New Order</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-3 ms-2 me-2"id="scrollable-dropdown-menu">
                                <label for="search-input">Search Product</label>
                                <input type="text" id="search-input" name="search-input" class="typeahead"
                                    spellcheck="false" autocomplete="off" placeholder="Search products">
                            </div>
                            <div class="card-body p-1">
                                <h4 class="card-title">Result</h4>
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
                        <div class="col-md-6 mt-4">
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

            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Order pending</h4>
                    <div class="table-responsive">
                        <table class="table table-hover"  id="orderPendingTable">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/ordermanage.js') }}"></script>
    <script>
        var products = @json($products);
    </script>
@endsection
