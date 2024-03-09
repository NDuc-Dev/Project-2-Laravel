@extends('auth.admin.layout-master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Product Management </h3>
                {{-- <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Basic tables</li>
                  </ol>
                </nav> --}}
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#form-focus">New Product
                    +</button>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Product List</h4>
                    <div class="table-responsive">
                        <table class="table" id="productTable">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="modal fade" id="form-focus" tabindex="-1" role="dialog" aria-labelledby="form-focus"
                    aria-hidden="true" id="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                Create New Product
                                <h5 class="modal-title" id="form-focus"></h5>
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa-regular fa-circle-xmark ps-1 display-5"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{-- <div class="card"> --}}
                                {{-- <div class="card-body"> --}}
                                <form id="form-validate" method="POST" action="{{ route('admin.createProduct') }}"
                                    enctype="multipart/form-data">
                                    {{-- <form id="form-validate" enctype="multipart/form-data"> --}}

                                    @csrf
                                    <div class="form-group">
                                        <label for="image-input">{{ __('Product Image') }}
                                            <span class="text-muted h6" style="font-weight: 300;"> (It is recommended to use
                                                PNG format images with
                                                dimensions of 570px x 570px for optimal display)
                                            </span>
                                        </label>
                                        <input type="file" id="image-input" name="image-input" accept="image/*"
                                            capture="user" style="display: none;">
                                        <div id="image-select"
                                            class=" d-flex flex-column align-items-center border ms-auto me-auto justify-content-center mt-2"
                                            style="width: 140px; height: 140px; cursor: pointer; background-size: contain;
                                            background-repeat: no-repeat;
                                            background-position: center;">
                                            <span class="text-muted h6 content-before" style="font-weight: 300;">570px x
                                                570px</span>
                                            <i class="fa-solid fa-image content-before text-muted"></i>
                                        </div>
                                        <div class="d-flex ms-auto me-auto justify-content-center mt-2">
                                            <button id="remove-image-btn" type="button"
                                                class="btn btn-inverse-danger btn-sm d-none">Remove Image</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name">{{ __('Product Name') }}</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name"
                                            placeholder="Product Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="descriptions">{{ __('Product Descriptions') }}</label>
                                        <input type="text" class="form-control" id="descriptions" name="descriptions"
                                            placeholder="Descriptions">
                                    </div>
                                    <div class="form-group">
                                        <label for="group_category">{{ __('Group Category') }}</label>
                                        <select id="group_category" name="group_category" class="form-control">
                                            <option value="" selected>-- Choose a Group Category --</option>
                                            @foreach ($dataGroupCategory as $Category)
                                                <option value="{{ $Category->group_id }}">
                                                    {{ $Category->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group drink-size d-none">
                                        <label for="priceS">Price Size S</label>
                                        <input type="text" class="form-control" id="priceS" name="priceS"
                                            placeholder="Unit Price (VND)">
                                    </div>
                                    <div class="form-group drink-size d-none">
                                        <label for="priceM">Price Size M</label>
                                        <input type="text" class="form-control" id="priceM" name="priceM"
                                            placeholder="Unit Price (VND)">
                                    </div>
                                    <div class="form-group drink-size d-none">
                                        <label for="priceL">Price Size L</label>
                                        <input type="text" class="form-control" id="priceL" name="priceL"
                                            placeholder="Unit Price (VND)">
                                    </div>
                                    <div class="form-group food-size d-none">
                                        <label for="priceU">Unit Price</label>
                                        <input type="text" class="form-control" id="priceU" name="priceU"
                                            placeholder="Unit Price (VND)">
                                    </div>
                                    <div class="form-group">
                                        <label for="categorySelect"
                                            class="col-md-4 col-form-label text-md-start">{{ __('Category') }}</label>
                                        <select id="categorySelect" name="categorySelect" class="form-control">
                                            <option value="" selected>--Choose a Category--</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary me-2">Submit</button>
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#form-focus">Cancel</button>
                                </form>
                                {{-- </div> --}}
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var products = @json($dataProducts);
        var dataCategory = @json($dataCategory);
    </script>
    <script src={{ asset('js/productmanage.js') }}></script>
@endsection
