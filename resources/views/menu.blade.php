@extends('layout-guest')

@section('content')
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url({{ asset('guest/images/bg_3.jpg') }})"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">

                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Our Menu</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
                            <span>Menu</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="ftco-intro">
        <div class="container-wrap">
            <div class="wrap d-md-flex align-items-xl-end">
                <div class="info">
                    <div class="row no-gutters">
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="icon"><span class="icon-phone"></span></div>
                            <div class="text">
                                <h3>+84 965 709 059</h3>
                                <p>A small river named Kim Nguu flows by their place and supplies.</p>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="icon"><span class="icon-my_location"></span></div>
                            <div class="text">
                                <h3>4th floor, VTC Online Building</h3>
                                <p>18 Tam Trinh Street, Hai Ba Trung, Ha Noi</p>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex ftco-animate">
                            <div class="icon"><span class="icon-clock-o"></span></div>
                            <div class="text">
                                <h3>Open Monday-Friday</h3>
                                <p>8:00am - 9:00pm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="ftco-menu mb-5 pb-5">
        <div class="container-fluid">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 heading-section text-center ftco-animate">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Our Products</h2>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live
                        the blind texts.</p>
                </div>
            </div>
            <div class="row d-md-flex">
                <div class="col-lg-12 ftco-animate menu-tabs">
                    <div class="row">
                        <div class="col-md-12 nav-link-wrap mb-5">
                            <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">

                                <a class="nav-link active" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2"
                                    role="tab" aria-controls="v-pills-2" aria-selected="false">Drinks</a>

                                <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab"
                                    aria-controls="v-pills-3" aria-selected="false">Foods</a>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex align-items-center">
                            <div class="tab-content ftco-animate" id="v-pills-tabContent">
                                <div class="tab-pane fade active show" id="v-pills-2" role="tabpanel"
                                    aria-labelledby="v-pills-2-tab">
                                    <div class="row">
                                        @foreach ($drinkProducts as $product)
                                            <div class="col-md-4 text-center">
                                                <div class="menu-wrap">
                                                    <a href="#" class="menu-img img mb-4"
                                                        style="background-image: url({{ $product->product_images }});"></a>
                                                    <div class="text">
                                                        <h3><a href="#">{{ $product->product_name }}</a></h3>
                                                        <p>{{ $product->descriptions }}</p>
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span></p>
                                                        <p><button type="button"
                                                                class="btn btn-primary btn-outline-primary"
                                                                data-toggle="modal" data-target="#productModaldrink"
                                                                data-id="{{ $product->product_id }}" id="add-to-cart-drink">
                                                                Add To Cart
                                                            </button>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
                                    <div class="row">
                                        @foreach ($foodProducts as $product)
                                            <div class="col-md-4 text-center">
                                                <div class="menu-wrap">
                                                    <a href="#" class="menu-img img mb-4"
                                                        style="background-image: url({{ $product->product_images }});"></a>
                                                    <div class="text">
                                                        <h3><a href="#">{{ $product->product_name }}</a></h3>
                                                        <p>{{ $product->descriptions }}</p>
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span>
                                                        </p>
                                                        <p><button type="button"
                                                                class="btn btn-primary btn-outline-primary"
                                                                id="add-to-cart-food" data-toggle="modal"
                                                                data-target="#productModalfood"
                                                                data-id="{{ $product->product_id }}">
                                                                Add To Cart
                                                            </button>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="productModaldrink" tabindex="-1" aria-labelledby="productModaldrinkLabel"
        aria-hidden="true" style="top: 50px;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: #000;">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Product</h5>
                    <button type="button" class="close" id="close-drink" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="product-id-drink" value="">
                <div class="modal-body">
                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-lg-6 mb-5 ftco-animate">
                                <a href="" class="image-popup" id="href-img-drink">
                                    <img src="" class="img-fluid" id="img-drink" alt="">
                                </a>
                            </div>
                            <div class="col-lg-6 product-details pl-md-5 ftco-animate">
                                <h3 id="product-name-drink"></h3>
                                <p class="price">
                                    <span id="priceSpandrink"></span>
                                </p>
                                <p id="product-descriptions-drink"></p>
                                <div class="row mt-4">
                                    <div class="col-md-7">
                                        <div class="form-group d-flex">
                                            <div class="select-wrap">
                                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                                <select name="" id="sizeSelect" class="form-control">
                                                    <option value="1" selected>S</option>
                                                    <option value="2">M</option>
                                                    <option value="3">L</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100"></div>
                                    <div class="input-group col-md-7 d-flex mb-3">
                                        <span class="input-group-btn mr-2">
                                            <button type="button" class="quantity-left-minus btn drink-minus" data-type="minus"
                                                data-field="">
                                                <i class="icon-minus"></i>
                                            </button>
                                        </span>
                                        <input type="text" id="quantitydrink" name="quantitydrink"
                                            class="form-control input-number" value="1" min="1"
                                            max="10">
                                        <span class="input-group-btn ml-2">
                                            <button type="button" class="quantity-right-plus btn drink-plus" data-type="plus"
                                                data-field="">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <p><a href="#" id="submit-add-to-cart-drink" class="btn btn-primary py-3 px-5">Add to Cart</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="productModalfood" tabindex="-1" aria-labelledby="productModalfoodLabel"
        aria-hidden="true" style="top: 50px;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: #000;">
                <div class="modal-header">
                    <h5 class="modal-title" id="">Product</h5>
                    <button type="button" class="close" id="close-food" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="product-id-food" value="">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 mb-5 ftco-animate">
                                <a href="" class="image-popup" id="href-img-food"><img src=""
                                        class="img-fluid" id="img-food" alt=""></a>
                            </div>
                            <div class="col-lg-6 product-details pl-md-5 ftco-animate">
                                <h3 id="product-name-food"></h3>
                                <p class="price"><span id="priceSpanfood"></span></p>
                                <p id="product-descriptions-food"></p>
                                <div class="row mt-4">
                                    <div class="w-100"></div>
                                    <div class="input-group col-md-7 d-flex mb-3">
                                        <span class="input-group-btn mr-2">
                                            <button type="button" class="quantity-left-minus btn food-minus" data-type="minus"
                                                data-field="">
                                                <i class="icon-minus"></i>
                                            </button>
                                        </span>
                                        <input type="text" id="quantityfood" name="quantityfood"
                                            class="form-control input-number" value="1" min="1"
                                            max="10">
                                        <span class="input-group-btn ml-2">
                                            <button type="button" class="quantity-right-plus btn food-plus" data-type="plus"
                                                data-field="">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <p><a href=""  id="submit-add-to-cart-food" class="btn btn-primary py-3 px-5">Add to Cart</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script>
           var cart = {!! json_encode(Session::get('cart')) !!};
    </script>
@endsection
