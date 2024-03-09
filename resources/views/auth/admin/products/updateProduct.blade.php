@extends('auth.admin.layout-master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <h4 class="card-title py-3 px-3">Update Product Info</h4>
                        <div class="card-body d-md-flex py-4">
                            <div class="col-md-4 border-right">
                                <div class=" d-flex flex-column align-items-center text-center px-4 py-5">
                                    <img id="product-image" class="mb-3" width="150px" src="{!! asset($product->product_images) !!}">
                                    <div class="ms-auto me-auto justify-content-center mb-2">
                                        <button id="change-image-btn" type="button"
                                            class="btn btn-inverse-info btn-sm">Change Image</button>
                                    </div>
                                    <span class="font-weight-bold">{{ $product->product_name }}</span>
                                    <span class="text-white-50">Category : {{ $product->product_category }}</span>
                                </div>
                            </div>
                            <form class="forms-sample col-md-8 ps-md-5" method="POST"
                                action="{{ route('admin.putUpdateProduct', ['id' => $product->product_id]) }}"
                                id="form-validate-update">
                                @csrf
                                @method('PUT')
                                <input type="file" id="image-input" name="image-input" accept="image/*" capture="user"
                                    class="d-none">
                                <input type="hidden" id="product_images" value="{{ $product->product_images }}">
                                <input type="hidden" id="product_id" value="{{ $product->product_id }}">
                                <div class="form-group">
                                    <label for="product_name">{{ __('Product Name') }}</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        value="{{ $product->product_name }}" placeholder="Product Name">
                                </div>
                                <div class="form-group">
                                    <label for="descriptions">{{ __('Descriptions') }}</label>
                                    <input type="text" class="form-control" id="descriptions" name="descriptions"
                                        value="{{ $product->descriptions }}" placeholder="Name">
                                </div>
                                @if ($productSize->count() == 3)
                                    @foreach ($productSize as $size)
                                        @if ($size->size_id === 1)
                                            <div class="form-group ">
                                                <label for="priceS">{{ __('Price Size S') }}</label>
                                                <input type="text" class="form-control" id="priceS" name="priceS"
                                                    placeholder="Price Size S" value="{{ $size->unit_price }}">
                                            </div>
                                        @elseif ($size->size_id === 2)
                                            <div class="form-group ">
                                                <label for="priceM">{{ __('Price Size S') }}</label>
                                                <input type="text" class="form-control" id="priceM" name="priceM"
                                                    placeholder="Price Size M" value="{{ $size->unit_price }}">
                                            </div>
                                        @elseif($size->size_id === 3)
                                            <div class="form-group ">
                                                <label for="priceL">{{ __('Price Size M') }}</label>
                                                <input type="text" class="form-control" id="priceL" name="priceL"
                                                    placeholder="Price Size L" value="{{ $size->unit_price }}">
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif ($productSize->count() == 1)
                                    @foreach ($productSize as $size)
                                        @if ($size->size_id == 4)
                                            <div class="form-group ">
                                                <label for="priceU">{{ __('Unit Price') }}</label>
                                                <input type="text" class="form-control" id="priceU" name="priceU"
                                                    placeholder="Unit Price" value="{{ $size->unit_price }}">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <button type="submit" class="btn btn-outline-primary me-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/updateproduct.js') }}"></script>
@endsection
