@extends('layout-guest')
@section('content')
    <section>
        <div class="d-flex justify-content-center mt-5">
            <div class="col-md-8 col-xs-6 mt-5">
                <h3 class="mb-4 text-center subheading">Reset PassWord</h3>
                <form method="POST" action="{{ route('postNewPassword') }}" class="login-form" id="form-validate">
                    @csrf
                    <input id="u_id" type="hidden" class="form-control" name="u_id" value="{{$u_id}}">
                    <div class="form-group">
                        <div class="mb-4">
                            <input id="password" type="password" class="form-control" name="password" value="" required
                                autocomplete="password" autofocus placeholder="Enter New Password.">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-4">
                            <input id="confirmation_password" type="password" class="form-control" name="confirmation_password" value="" required
                                autocomplete="confirmation_password" autofocus placeholder="Confirm Password.">
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
    </section>
    <script src="{{asset('js/getNewPass.js')}}"></script>
@endsection
