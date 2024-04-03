@extends('layout-guest')
@section('content')
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url({{ asset('guest/images/bg_3.jpg') }});"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Cart</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
                            <span>Cart</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ftco-section ftco-cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $productIdAndSizeId => $item)
                                    <tr class="text-center" id="product_row_{{ $productIdAndSizeId }}">
                                        <td class="product-remove"><a href="#" class="remove-product"
                                                data-productIdAndSizeId="{{ $productIdAndSizeId }}"><span
                                                    class="icon-close"></span></a>
                                        </td>

                                        <td class="image-prod">
                                            <div class="img"
                                                style="background-image:url({{ asset($item['product']->product_images) }});">
                                            </div>
                                        </td>

                                        <td class="product-name">
                                            @if (in_array($item['size']->size_id, [1, 2, 3]))
                                                <h3>{{ $item['product']->product_name . ' - ' . $item['size']->size_name }}
                                                </h3>
                                            @else
                                                <h3>{{ $item['product']->product_name }}
                                                </h3>
                                            @endif

                                        </td>

                                        <td class="price">{{ number_format($item['productSize'], 0, ',', ',') }} VND
                                        </td>

                                        <td class="quantity">
                                            <div class="input-group mb-3">
                                                <input type="textr" name="quantity"
                                                    class="quantity form-control input-number"
                                                    data-productIdAndSizeId="{{ $productIdAndSizeId }}"
                                                    data-product-price="{{ $item['productSize'] }}"
                                                    value="{{ $item['quantity'] }}" min="1" max="10">
                                            </div>
                                        </td>

                                        <td class="total">
                                            {{ number_format($item['productSize'] * $item['quantity'], 0, ',', ',') }} VND
                                        </td>
                                    </tr><!-- END TR-->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
                    <div class="cart-total mb-3">
                        <h3>Cart Totals</h3>
                        <p class="d-flex">
                            <span>Subtotal</span>
                            <span id="sub-total"></span>
                        </p>
                        <hr>
                        <p class="d-flex total-price">
                            <span>Total</span>
                            <span id="total"></span>
                        </p>
                    </div>
                    <p class="text-center"><a href="{{route('checkout')}}" class="btn btn-primary py-3 px-4">Checkout</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/cart.js') }}"></script>
@endsection
