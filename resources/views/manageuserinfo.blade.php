@extends('layout-guest')
@section('content')
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url({{ asset('guest/images/bg_3.jpg') }})"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">

                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">My Info</h1>
                        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
                            <span>My Info</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="ftco-menu mb-5 pb-5">
        <div class="container-fluid">
            <div class="row d-md-flex">
                <div class="col-lg-12 ftco-animate menu-tabs">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-2-tab" data-toggle="pill" href="#v-pills-2"
                                role="tab" aria-controls="v-pills-2" aria-selected="false">Info</a>

                            <a class="nav-link" id="v-pills-3-tab" data-toggle="pill" href="#v-pills-3" role="tab"
                                aria-controls="v-pills-3" aria-selected="false">Password</a>
                        </div>
                    </div>
                    <div class="col-md-12 align-items-center">
                        <div class="tab-content ftco-animate" id="v-pills-tabContent">
                            <div class="tab-pane fade active show" id="v-pills-2" role="tabpanel"
                                aria-labelledby="v-pills-2-tab">
                                <form class="login-form" method="POST"
                                    action="{{ route('updateUserInf') }}" id="form-validate-update">
                                    @csrf
                                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->user_id }}">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user->name }}" placeholder="Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ $user->phone }}" placeholder="Phone">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="{{ $user->delivery_address }}" placeholder="Enter your Address">
                                    </div>
                                    <div class="form-group mb-2">
                                        <button type="submit" class="btn btn-white">Update Info</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
                                <h3 class="mb-4 text-center subheading">Reset PassWord</h3>
                                <form method="POST" action="{{ route('postNewPassword') }}" class="login-form"
                                    id="form-validate">
                                    @csrf
                                    <input id="u_id" type="hidden" class="form-control" name="u_id"
                                        value="{{ $user->user_id }}">
                                    <div class="form-group">
                                        <div class="mb-4">
                                            <input id="password" type="password" class="form-control" name="password"
                                                value="" required autocomplete="password" autofocus
                                                placeholder="Enter New Password.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="mb-4">
                                            <input id="confirmation_password" type="password" class="form-control"
                                                name="confirmation_password" value="" required
                                                autocomplete="confirmation_password" autofocus
                                                placeholder="Confirm Password.">
                                        </div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <button type="submit" class="btn btn-white mb-5">
                                            Reset Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('js/manageuserinfo.js') }}"></script>
@endsection
