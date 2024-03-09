@extends('auth.admin.layout-master')
@section('content')
<section class="background-radial-gradient overflow-hidden">
    <style>
        .background-radial-gradient {
            background-image: url('{{ asset("images/pexels-afta-putta-gunawan-683039.jpg") }}');
            width: 100%;
        }

    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 my-auto mt-5">
                <div class=" mb-2 mb-lg-0 position-relative">
                    <div class="card my-5">
                        <div class="card-body">
                            <div class="login-wrap p-0">
                                <h3 class="mb-4 text-center">Sign In</h3>
                                <form method="POST" action="{{ route('login') }}" class="signin-form">
                                    @csrf
                                    <div class="form-group">
                                        <div class="mb-4">
                                            <label for="user_name" class="col-md-4 col-form-label text-md-start">{{
                                                __('User Name') }}
                                            </label>
                                            <input id="user_name" type="text"
                                                class="form-control @error('user_name') is-invalid @enderror"
                                                name="user_name" value="{{ old('user_name') }}" required
                                                autocomplete="user_name" autofocus placeholder="User Name">

                                            @error('user_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                        <div class="form-group">
                                            <div class="mb-4">
                                                <label for="name" class="col-md-4 col-form-label text-md-start">{{
                                                    __('Password') }}
                                                </label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password"
                                                    placeholder="Password">
                                                {{-- <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye field-icon toggle-password"></span> --}}
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <button type="submit" class="form-control btn btn-primary submit px-3 mb-4">
                                                Sign In
                                            </button>
                                            <div class="d-flex justify-content-center">
                                                @if (Route::has('password.request'))
                                                <a class="btn btn-link w-50 text-start"
                                                    href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password ?') }}
                                                </a>
                                                @endif
                                                @if (Route::has('register'))
                                                <a class="btn btn-link w-50 text-end" href="{{ route('register') }}">
                                                    {{ __('Create new account ?') }}
                                                </a>
                                                @endif

                                            </div>
                                        </div>
                                </form>
                                <p class="w-100 text-center">&mdash; Or Sign In With &mdash;</p>
                                <div class="social d-flex text-center">
                                    <a href="#" class="px-2 py-2 mr-md-1 rounded"><i class="fa-brands fa-facebook"></i>
                                        Facebook</a>
                                    <a href="#" class="px-2 py-2 ml-md-1 rounded"><i class="fa-brands fa-google"></i>
                                        Google</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection