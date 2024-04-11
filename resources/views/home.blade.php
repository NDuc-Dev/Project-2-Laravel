@extends('layout-guest')

@section('content')
    {{-- < class="content-wraper" style="max-width: 100vw; min-height: 100vh;"> --}}

    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(guest/images/bg_1.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Welcome</span>
                        <h1 class="mb-4">The Best Coffee Testing Experience</h1>
                        <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the
                            necessary regelialia.</p>
                        <p> <a href="{{ route('menu') }}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View
                                Menu</a></p>
                    </div>

                </div>
            </div>
        </div>

        <div class="slider-item" style="background-image: url(guest/images/bg_2.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Welcome</span>
                        <h1 class="mb-4">Amazing Taste &amp; Beautiful Place</h1>
                        <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the
                            necessary regelialia.</p>
                        <p> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View
                                Menu</a></p>
                    </div>

                </div>
            </div>
        </div>

        <div class="slider-item" style="background-image: url(guest/images/bg_3.jpg);">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Welcome</span>
                        <h1 class="mb-4">Creamy Hot and Ready to Serve</h1>
                        <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the
                            necessary regelialia.</p>
                        <p> <a href="#" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View
                                Menu</a></p>
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

    <section class="ftco-about d-md-flex">
        <div class="one-half img" style="background-image: url({{ asset('guest/images/about.jpg') }});"></div>
        <div class="one-half ftco-animate">
            <div class="overlap">
                <div class="heading-section ftco-animate ">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Our Story</h2>
                </div>
                <div>
                    <p>On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would
                        have been rewritten a thousand times and everything that was left from its origin would be the word
                        "and" and the Little Blind Text should turn around and return to its own, safe country. But nothing
                        the copy said could convince her and so it didn’t take long until a few insidious Copy Writers
                        ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they
                        abused her for their.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-services">
        <div class="container">
            <div class="row">
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-choices"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading">Easy to Order</h3>
                            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost
                                unorthographic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-delivery-truck"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading">Fastest Delivery</h3>
                            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost
                                unorthographic.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 ftco-animate">
                    <div class="media d-block text-center block-6 services">
                        <div class="icon d-flex justify-content-center align-items-center mb-5">
                            <span class="flaticon-coffee-bean"></span>
                        </div>
                        <div class="media-body">
                            <h3 class="heading">Quality Coffee</h3>
                            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost
                                unorthographic.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 pr-md-5">
                    <div class="heading-section text-md-right ftco-animate">
                        <span class="subheading">Discover</span>
                        <h2 class="mb-4">Our Menu</h2>
                        <p class="mb-4">Far far away, behind the word mountains, far from the countries Vokalia and
                            Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the
                            coast of the Semantics, a large language ocean.</p>
                        <p><a href="{{ route('menu') }}" class="btn btn-primary btn-outline-primary px-4 py-3">View Full
                                Menu</a></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="menu-entry">
                                <a href="#" class="img"
                                    style="background-image: url({{ asset('guest/images/menu-1.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry mt-lg-4">
                                <a href="#" class="img"
                                    style="background-image:  url({{ asset('guest/images/menu-2.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry">
                                <a href="#" class="img"
                                    style="background-image:  url({{ asset('guest/images/menu-3.jpg') }});"></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="menu-entry mt-lg-4">
                                <a href="#" class="img"
                                    style="background-image:  url({{ asset('guest/images/menu-4.jpg') }});"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-counter ftco-bg-dark img" id="section-counter"
        style="background-image: url({{ asset('guest/images/bg_2.jpg') }});" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><i class="fa-solid fa-mug-hot"></i></div>
                                    <strong class="number" data-number="100">0</strong>
                                    <span>Coffee Branches</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><i class="fa-solid fa-light fa-award"></i></div>
                                    <strong class="number" data-number="85">0</strong>
                                    <span>Number of Awards</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><i class="fa-regular fa-face-smile"></i></div>
                                    <strong class="number" data-number="10567">0</strong>
                                    <span>Happy Customer</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <div class="icon"><i class="fa-regular fa-user"></i></div>
                                    <strong class="number" data-number="900">0</strong>
                                    <span>Staff</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-menu">
        <div class="container-fluid">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 heading-section text-center ftco-animate">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Our Products</h2>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there
                        live the blind texts.</p>
                </div>
            </div>
            <div class="row d-md-flex">
                <div class="col-lg-12 ftco-animate menu-tabs">
                    <div class="row">
                        <div class="col-md-12 nav-link-wrap mb-5">
                            <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab"
                                role="tablist" aria-orientation="vertical">

                                <a class="nav-link active" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2"
                                    role="tab" aria-controls="v-pills-2" aria-selected="false">Drinks</a>

                                <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3"
                                    role="tab" aria-controls="v-pills-3" aria-selected="false">Foods</a>
                            </div>
                        </div>
                        <div class="col-md-12 align-items-center">

                            <div class="tab-content ftco-animate" id="v-pills-tabContent">
                                <div class="tab-pane fade active show" id="v-pills-2" role="tabpanel"
                                    aria-labelledby="v-pills-2-tab">
                                    <div class="row">
                                        @foreach ($drinkProducts as $product)
                                            <div class="col-md-4 text-center">
                                                <div class="menu-wrap">
                                                    <a href="{{ route('productDetails', ['id' => $product->product_id]) }}"
                                                        class="menu-img img mb-4"
                                                        style="background-image: url({{ $product->product_images }});"></a>
                                                    <div class="text">
                                                        <h3><a
                                                                href="{{ route('productDetails', ['id' => $product->product_id]) }}">{{ $product->product_name }}</a>
                                                        </h3>
                                                        {{-- <p>{{ $product->descriptions }}</p> --}}
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span></p>
                                                        <p><button type="button"
                                                                class="btn btn-primary btn-outline-primary"
                                                                data-toggle="modal" data-target="#productModaldrink"
                                                                data-id="{{ $product->product_id }}"
                                                                id="add-to-cart-drink">
                                                                Add To Cart
                                                            </button>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-3" role="tabpanel"
                                    aria-labelledby="v-pills-3-tab">
                                    <div class="row">
                                        @foreach ($foodProducts as $product)
                                            <div class="col-md-4 text-center">
                                                <div class="menu-wrap">
                                                    <a href="{{ route('productDetails', ['id' => $product->product_id]) }}"
                                                        class="menu-img img mb-4"
                                                        style="background-image: url({{ $product->product_images }});"></a>
                                                    <div class="text">
                                                        <h3><a
                                                                href="{{ route('productDetails', ['id' => $product->product_id]) }}">{{ $product->product_name }}</a>
                                                        </h3>
                                                        {{-- <p>{{ $product->descriptions }}</p> --}}
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span></p>
                                                        <p><button type="button"
                                                                class="btn btn-primary btn-outline-primary"
                                                                data-toggle="modal" data-target="#productModalfood"
                                                                data-id="{{ $product->product_id }}"
                                                                id="add-to-cart-food">
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
                                            <button type="button" class="quantity-left-minus btn drink-minus"
                                                data-type="minus" data-field="">
                                                <i class="icon-minus"></i>
                                            </button>
                                        </span>
                                        <input type="text" id="quantitydrink" name="quantitydrink"
                                            class="form-control input-number" value="1" min="1"
                                            max="10">
                                        <span class="input-group-btn ml-2">
                                            <button type="button" class="quantity-right-plus btn drink-plus"
                                                data-type="plus" data-field="">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <p><a href="#" id="submit-add-to-cart-drink" class="btn btn-primary py-3 px-5">Add
                                        to Cart</a></p>
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
                                            <button type="button" class="quantity-left-minus btn food-minus"
                                                data-type="minus" data-field="">
                                                <i class="icon-minus"></i>
                                            </button>
                                        </span>
                                        <input type="text" id="quantityfood" name="quantityfood"
                                            class="form-control input-number" value="1" min="1"
                                            max="10">
                                        <span class="input-group-btn ml-2">
                                            <button type="button" class="quantity-right-plus btn food-plus"
                                                data-type="plus" data-field="">
                                                <i class="icon-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <p><a href="" id="submit-add-to-cart-food" class="btn btn-primary py-3 px-5">Add
                                        to Cart</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
@endsection
