@extends('layout-guest')
@section('content')
    <section>
        <div class="d-flex justify-content-center mt-5">
            <div class="col-md-8 col-xs-6 mt-5">
                <h3 class="mb-4 text-center subheading">Sign In</h3>
                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf
                    <div class="form-group">
                        <div class="mb-4">
                            <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror"
                                name="user_name" value="{{ old('user_name') }}" required autocomplete="user_name" autofocus
                                placeholder="User Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="current-password" placeholder="Password">
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-white mb-5">
                            Sign In
                        </button>
                        <div class="d-flex justify-content-center">
                            <a class="w-50 text-start" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password ?') }}
                            </a>
                            <a class="w-50 text-end" style="text-align: right;" href="{{ route('register') }}">
                                {{ __('Create new account ?') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
