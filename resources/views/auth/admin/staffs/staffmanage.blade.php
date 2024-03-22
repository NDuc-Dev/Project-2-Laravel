@extends('auth.admin.layout-master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Users Management </h3>
                {{-- <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Tables</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Basic tables</li>
                  </ol>
                </nav> --}}
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#form-focus">Add +</button>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Staffs List</h4>
                    <div class="table-responsive">
                        <table class="table table-hover" id="staffTable">
                            <thead>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="form-focus" tabindex="-1" role="dialog" aria-labelledby="form-focus"
                aria-hidden="true" id="dialog">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            Create New Staff
                            <h5 class="modal-title" id="form-focus"></h5>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-regular fa-circle-xmark ps-1 display-5"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <div class="card"> --}}
                                {{-- <div class="card-body"> --}}
                                    <form method="POST" action="{{ route('admin.postCreateStaffs') }}" id="form-validate">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">{{ __('Email address') }}</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="Phone">
                                        </div>
                                        <div class="form-group">
                                            <label for="user_name">User Name</label>
                                            <input type="text" class="form-control" id="user_name" name="user_name"
                                                placeholder="User Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <label for="role"
                                                class="col-md-4 col-form-label text-md-start">{{ __('Role') }}</label>
                                            <select id="role" name="role" class="form-control">
                                                <option value="" selected>--Choose a Role--</option>
                                                <option value="bartender">Bartender</option>
                                                <option value="seller">Seller</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-outline-primary me-2"
                                            data-url="{{ route('admin.checkUName') }}">Submit</button>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#form-focus">Cancel</button>
                                    </form>
                                {{-- </div> --}}
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/staffmanage.js') }}"></script>
@endsection
