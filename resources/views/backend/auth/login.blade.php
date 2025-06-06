@extends('backend.auth.auth_master')

@section('auth_title')
Login | Admin Panel
@endsection

@section('auth-content')
<!-- login area start -->
<style>
    .bg-login-image {
        background: url(../backend/assets/images/bg.png);
        background-position: center;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        /* background-size: 600px; */
        height: 100vh;
    }

    body.dark-mode {
        background-color: #121212;
        color: #ffffff;
    }

    .card.dark-mode {
        background-color: #1e1e1e;
        color: #ffffff;
    }

    .form-control.dark-mode {
        background-color: #333333;
        color: #ffffff;
    }

    .btn.dark-mode {
        background-color: #444444;
        color: #ffffff;
    }

    .card {
        background: none;
    }

    body {
        overflow: hidden;
    }
</style>
<div class="row justify-content-center bg-login-image">

    <div class="col-xl-10 col-lg-12 col-md-9">
        {{-- <div class="text-center mb-3">
            <button id="toggle-dark-mode" class="btn btn-secondary">Toggle Dark Mode</button>
        </div> --}}
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0 ">
                <!-- Nested Row within Card Body -->
                <div class="row justify-content-center">
                    {{-- <div class="col-lg-6 d-none d-lg-block bg-login-image"></div> --}}
                    <div class="col-lg-6" style="background: #fff; max-width: 30%;margin-top: 5%;border-radius: 4%;">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Admin Sign In!</h1>
                            </div>
                            @include('backend.layouts.partials.messages')
                            <form method="POST" action="{{ route('admin.login.submit') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Email address</b></label>
                                    <input type="text" class="form-control form-control-user" name="email"
                                        id="exampleInputEmail1" aria-describedby="emailHelp"
                                        placeholder="Email Address">
                                    <div class="text-danger"></div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><b>Password</b></label>
                                    <input type="password" class="form-control form-control-user" name="password"
                                        id="exampleInputPassword1" placeholder="Password">
                                    <div class="text-danger"></div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" name="remember"
                                            id="customControlAutosizing">
                                        <label class="custom-control-label" for="customControlAutosizing">Remember
                                            Me</label>
                                    </div>
                                </div>
                                <input type="submit" name="submitForm" id="form_submit" value="Sign In"
                                    class="btn btn-primary btn-user btn-block" />
                                <hr>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- login area end -->
@endsection
