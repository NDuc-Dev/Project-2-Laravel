@extends('auth.admin.layout-master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <h4 class="card-title py-3 px-3">Update Staff Info</h4>
                        <div class="card-body d-md-flex py-4">
                            <div class="col-md-4 border-right">
                                <div class=" d-flex flex-column align-items-center text-center px-4 py-5">
                                    <img class="rounded-circle mt-5" width="150px"
                                        src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                                    <span class="font-weight-bold">{{ $user->name }}</span>
                                    <span class="text-white-50">{{ $user->email }}</span>
                                </div>
                            </div>

                            <form class="forms-sample col-md-8 ps-md-5" method="POST"
                                action="{{ route('admin.putUpdateStaff', ['id' => $user->user_id]) }}" id="form-validate-update">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="user_id" value="{{ $user->user_id }}">
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $user->name }}" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('Email address') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $user->email }}" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <label for="phone">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ $user->phone }}" placeholder="Phone">
                                </div>
                                <div class="form-group">
                                    <label for="user_name"> {{ __('User Name') }}</label>
                                    <input type="text" disabled class="form-control" id="user_name" name="user_name"
                                        value="{{ $user->user_name }}" placeholder="User Name">
                                </div>
                                {{-- <div class="form-group">
                                        <label>File upload</label>
                                        <input type="file" name="img[]" class="file-upload-default">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-primary"
                                                    type="button">Upload</button>
                                            </span>
                                        </div>
                                    </div> --}}
                                <div class="form-group">
                                    <label for="role"
                                        class="col-md-4 col-form-label text-md-start">{{ __('Role') }}</label>
                                    <select id="role" name="role" class="form-control">
                                        <option selected>--Choose a Role--</option>
                                        <option value="bartender" {{ $user->role == 'bartender' ? 'selected' : '' }}>
                                            Bartender
                                        </option>
                                        <option value="saler" {{ $user->role == 'saler' ? 'selected' : '' }}>Seller
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-outline-primary me-2">Submit</button>
                                <button type="button" class="btn btn-outline-danger me-2 reset-password">Reset
                                    Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/updatestaff.js') }}"></script>
@endsection
