@extends('layout-guest')
@section('content')
<section>
    <div class="d-flex justify-content-center mt-5">
        <div class="col-md-8 col-xs-6 mt-5">
            <h3 class="mb-4 text-center subheading">Forgot Password</h3>
            <form method="POST" action="{{ route('PostForgetPassword') }}" class="login-form">
                @csrf
                <div class="form-group">
                    <div class="mb-4">
                        <input id="email" type="text" class="form-control"
                            name="email" value="{{ old('user_name') }}" required autocomplete="email" autofocus
                            placeholder="Enter your email you registered your account with us.">
                    </div>
                </div>
                <div class="form-group mb-2">
                    <button type="submit" class="btn btn-white mb-5">
                        Send confirmation email
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<script src="{{asset('js/forgot-password.js')}}"></script>
@endsection