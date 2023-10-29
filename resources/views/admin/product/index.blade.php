@extends('admin.layouts.app')
@section('content')
    <div class="product-container ">
        <div class="d-flex justify-content-between">
            <h2>Product List</h2>
            <div class="form-search d-flex algin-items-center gap-2">
                <input type="text" id="txtSearchProduct" placeholder="search here..." class="form-control" name="nameProduct">
                <button class="btn btn-primary" onclick="searchProduct()"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            <div class="">
                <select class="form-select" name="sortByCategory" id="sortByCategory">
                    <option selected value="">Sort by Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            @if (count($categories) > 0)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProductModal"
                    data-bs-backdrop="static" data-bs-keyboard="false"><i class="fa-solid fa-plus me-2"></i>New
                    Product</button>
            @else
                <a href="{{ route('admin.category.index') }}">Please create a new category to add products</a>
            @endif
        </div>
        <div class="mt-3">
            <div id="product_table">
                <div class="d-flex justify-content-center mt-5">
                    <img src="{{ asset('admin/assets/images/loading.svg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    @include('admin.product.modal_update')
@endsection
@section('web-script')
    <script>
        var globalRouter = {
            urlSearchPost: "{{ route('admin.product.search') }}",
            urlDeleteProduct: "{{ route('admin.product.delete', ['id' => ':id']) }}",
            urlDeleteProduct: "{{ route('admin.product.delete', ['id' => ':id']) }}",
            urlCreateProduct: "{{ route('admin.product.create') }}",
            urlUpdateProduct: "{{ route('admin.product.update') }}",
        }
    </script>
    <script src="{{ asset('admin/assets/js/productAdmin.js') }}"></script>
@endsection
