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
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#form-focus">Add +</button>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Order in pending</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="staffTable">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="form-focus" tabindex="-1" data-bs-focus="false" role="dialog"
                aria-labelledby="form-focus" aria-hidden="true" id="dialog">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            Create New Order
                            <h5 class="modal-title" id="form-focus"></h5>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-regular fa-circle-xmark ps-1 display-5"></i>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-lg-6">
                                <div class="form-group mt-3 ms-2 me-2"id="scrollable-dropdown-menu">
                                    <label for="search-input">Search Product</label>
                                    <input type="text" id="search-input" name="search-input" class="typeahead"
                                        spellcheck="false" autocomplete="off" placeholder="Search products">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Menu</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="productTable">
                                            <thead>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">

                                    <div class="card-body">
                                        <h4 class="card-title">Order</h4>
                                        <div id='order-details' class="order-details d-none">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p id="order-code" class="text-muted"></p>
                                                    <p id="order-table" class="text-muted"></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p id="order-code" class="text-muted">Order Staff:
                                                        {{ Auth::user()->name }}
                                                    </p>
                                                    <p id="order-date" class="text-muted"></p>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="productOrder">
                                                    <thead>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <div class="row mt-4 mx-0">
                                                    <div class="mt-2 col-md-6 ms-auto p-0">
                                                        <h6 id="total"></h6>
                                                    </div>
                                                    <div class="col-md-6 p-0 d-flex">
                                                        <button class="btn btn-info btn-md " style="flex: auto" type="button">
                                                            Submit
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
    <script>
        var products = @json($products);
        console.log(products);
    </script>
@endsection
