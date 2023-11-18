@extends('auth.layouts.app')
@section('title', 'Đăng nhập - Male Fashion')

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
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" name="email" autofocus class="form-control" id="email"
                                    aria-describedby="emailHelp">
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
                            <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng nhập</button>
                            <div class="d-flex align-items-center justify-content-center">
                                <p class="fs-4 mb-0 fw-bold">Bạn chưa có tài khoản?</p>
                                <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Đăng ký ngay</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
