@extends('auth.layouts.app')
@section('title', 'Đăng ký - Male Fashion')

@section('content')
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="{{ route('home') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                            <img src="{{ asset('img/logo.png') }}" width="180" alt="">
                        </a>
                        <p class="text-center">Chào mừng bạn đến với cửa hàng của chúng tôi</p>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên của bạn</label>
                                <input type="text"name="name" class="form-control" id="name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Địa chỉ Email</label>
                                <input type="email" name="email" class="form-control" id="email">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" id="password">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation">
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng ký
                            </button>
                            <div class="d-flex align-items-center justify-content-center">
                                <p class="fs-4 mb-0 fw-bold">Bạn đã có tài khoản?</p>
                                <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Đăng nhập ngay</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
