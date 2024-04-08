@extends('layout-guest')
@section('content')
    <section>
        <div class="d-flex justify-content-center mt-5">
            <div class="col-md-8 col-xs-6 mt-5">
                <h3 class="mb-4 text-center subheading">{{ __('Sign Up') }}</h3>
                <form method="POST" action="{{ route('registerAjax') }}" class="login-form" id="form-validate">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input id="name" type="text" class="form-control" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="form-control" placeholder="Phone" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                            required autocomplete="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <input type="text" id="user_name" name="user_name" value="{{ old('username') }}" required
                            class="form-control" placeholder="User Name" />
                    </div>

                    <div class="form-group mb-4">
                        <input id="password" type="password" class="form-control" name="password" required
                            autocomplete="new-password" placeholder="Password">
                    </div>

                    <div class="form-group mb-4">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password" placeholder="Confirm Password">
                    </div>


                    <!-- Submit button -->
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-white mb-5">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script src="{{asset('js/register.js')}}"></script>
@endsection
