@extends('layouts.app')

@section('styles')

<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="container m-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="contain-register">
                    <div class="background">
                        <div class="shape"></div>
                        <div class="shape"></div>
                    </div>
                    <form method="POST" action="{{ route('auth.register') }}">
                        @csrf

                        <div>
                            <label for="username">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" id="username">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                       <div>
                            <label for="username">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" type="text" placeholder="Email" id="username">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                       <div class="pass">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" type="password" placeholder="Password" id="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                       </div>

                        <button class="log-but">Register</button>
                        <div class="social">
                          <div class="fb"><a href="{{ route('index.login') }}">Log in</a></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
