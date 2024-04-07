@extends('layout-guest')
@section('content')
    <section class="home-slider owl-carousel">

        <div class="slider-item" style="background-image: url({{ asset('guest/images/bg_3.jpg') }});"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">

                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Checkout</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
                            <span>Checout</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 ftco-animate">

                    <div class="row pt-3 d-flex">
                        <div class="col-md-12 d-flex">
                            <div class="cart-detail cart-total ftco-bg-dark p-3 p-md-4">
                                <h3 class="billing-heading mb-4">Cart Informations</h3>
                                <div class="cart-list">

                                    <table class="table">
                                        <thead class="thead-primary">
                                            <tr class="text-center">
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

                                                    <td class="price">
                                                        {{ number_format($item['productSize'], 0, ',', ',') }} VND
                                                    </td>

                                                    <td class="quantity">
                                                        <p class="quantity">{{ $item['quantity'] }}</p>
                                                    </td>

                                                    <td class="total">
                                                        {{ number_format($item['productSize'] * $item['quantity'], 0, ',', ',') }}
                                                        VND
                                                    </td>
                                                </tr><!-- END TR-->
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                        </div>
                    </div>

                    <form action="{{ route('vnpay-payment') }}" id="form-validate" method="POST"
                        class="billing-form ftco-bg-dark mt-5 p-3 p-md-5">
                        @csrf
                        <h3 class="mb-4 billing-heading">Billing Details </h3>
                        <small>Please note: Our online ordering service is only available for orders within the inner
                            districts of Hanoi. Please provide complete and accurate delivery information. In case of any
                            issues, we will not be held responsible.
                        </small>
                        <div class="row align-items-end">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Name">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Delivery Address</label>
                                    <input type="text" id="address" name="address" class="form-control"
                                        placeholder="House number and street name">
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="Phone number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <small>
                                        Please fill in this field if you wish to receive information about the invoice.
                                    </small>
                                    <input type="text" id="email" name="email" class="form-control"
                                        placeholder="Email Address">
                                </div>
                            </div>
                            <div class="w-100"></div>

                        </div>
                        <p>
                            <button type="submit" id="place-order-btn" name="redirect"
                                class="btn btn-primary py-3 px-4">Payment via
                                vnpay</button>
                        </p>
                    </form>

                </div>

            </div>
        </div>
    </section>
    <script src="{{ asset('js/checkout.js') }}"></script>
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const responseCode = urlParams.get('vnp_ResponseCode');
            const orderId = urlParams.get('vnp_TxnRef');
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            if (responseCode) {
                if (responseCode !== null && responseCode === '00') {
                    Swal.fire({
                        title: "SUCCESS",
                        text: 'Payment Successfully',
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $.ajax({
                        url: 'clear-cart',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            $.ajax({
                                url: 'send-mail',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                data: {
                                    order_id: orderId,
                                },
                                success: function(response) {

                                    window.location.href = '/cart';
                                },
                                error: function(xhr, status, error) {
                                    console.error(error);
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    Swal.fire({
                        title: "ERROR",
                        text: 'Payment failed',
                        icon: "error",
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            }
        });
    </script>
@endsection
