@extends('auth.admin.layout-master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Category Management </h3>
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#form-focus">New Category
                    +</button>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Category List</h4>
                    <div class="table-responsive">
                        <table class="table" id="categoryTable">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="modal fade" id="form-focus" tabindex="-1" role="dialog" aria-labelledby="form-focus"
                    aria-hidden="true" id="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                Create New Category
                                <h5 class="modal-title" id="form-focus"></h5>
                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa-regular fa-circle-xmark ps-1 display-5"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="form-validate" method="POST" action="{{ route('admin.createCategory') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="category_name">{{ __('Category Name') }}</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            placeholder="Category Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="descriptions">{{ __('Category Descriptions') }}</label>
                                        <input type="text" class="form-control" id="descriptions" name="descriptions"
                                            placeholder="Descriptions">
                                    </div>

                                    <div class="form-group">
                                        <label for="categorySelect"
                                            class="col-md-4 col-form-label text-md-start">{{ __('Category group') }}</label>
                                        <select id="categorySelect" name="categorySelect" class="form-control">
                                            <option value="" selected>--Choose a Category--</option>
                                            <option value="1" >Drink</option>
                                            <option value="2" >Food</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary me-2">Submit</button>
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#form-focus">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="modal fade" id="modal-product-list" tabindex="-1" role="dialog"
                    aria-labelledby="modal-product-list" aria-hidden="true" id="dialog">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                Product List
                                <h5 class="modal-title" id="modal-product-list"></h5>
                                <button type="button" class="btn btn-dark btn-close-product" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa-regular fa-circle-xmark ps-1 display-5"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table" id="productsTable">
                                            <thead>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src={{ asset('js/categorymanage.js') }}></script>
    @endsection
