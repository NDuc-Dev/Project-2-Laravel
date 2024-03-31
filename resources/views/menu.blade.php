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
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-md-7 heading-section text-center ftco-animate">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Our Products</h2>
                    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live
                        the blind texts.</p>
                </div>
            </div>
            <div class="row d-md-flex">
                <div class="col-lg-12 ftco-animate p-md-5">
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
                                                        {{-- <p>{{ $product->descriptions}}</p> --}}
                                                        <p>{{ $product->descriptions }}</p>
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span></p>
                                                        {{-- <p><button data-toggle="modal" data-target="#form-focus"
                                                                class="btn btn-primary btn-outline-primary">Add
                                                                to cart</button></p> --}}
                                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                            data-target="#exampleModal">
                                                            Launch demo modal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        {{-- <div class="modal fade" id="form-focus" tabindex="-1" role="dialog"
                                            aria-labelledby="form-focus" aria-hidden="true" id="dialog">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div> --}}
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
                                                        {{-- <p>{{ $product->descriptions}}</p> --}}
                                                        <p>{{ $product->descriptions }}</p>
                                                        <p class="price"><span>{{ $product->unit_price }} VND</span>
                                                        </p>
                                                        <p><a href="" class="btn btn-primary btn-outline-primary">Add
                                                                to cart</a></p>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
