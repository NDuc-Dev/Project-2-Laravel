@extends('auth.admin.layout-master')
@section('content')
    <style>
        .twitter-typeahead {
            display: block !important;
        }

        .product-dropdown>.dropdown-divider {
            border-top: 1px solid #2c2e33;
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

            <div class="modal fade" id="form-focus" tabindex="-1" role="dialog" aria-labelledby="form-focus"
                aria-hidden="true" id="dialog">
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
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group mt-3 ms-3 me-3"id="scrollable-dropdown-menu">
                                    <label for="search-input">Search Product</label>
                                    <input type="text" id="search-input" name="search-input" class="typeahead"
                                        spellcheck="false" autocomplete="off" placeholder="Search products">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">Product List</h4>
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
                            <div class="col-md-6 col-xs-12">
                                <div class="card-body">
                                    <h4 class="card-title">Order details</h4>
                                    <p id="order-code" class="text-muted">Order Code: ******</p>
                                    <p id="order-table" class="text-muted">OrderTable: **</p>
                                    <div class="table-responsive">
                                        <table class="table" id="productOrder">
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
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/ordermanage.js') }}"></script>
    <script>
        var products = @json($products);
        console.log(products);
        var foodcategories = @json($foodcategories);
        console.log(foodcategories);
        var drinkCategories = @json($drinkCategories);
        console.log(drinkCategories);
    </script>
@endsection
