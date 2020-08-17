@extends('layouts.app')

@section('content')
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">
            <div class="col-lg-6 p-0 mx-auto">

                <div class="page-separator mb-4">
                    <div class="page-separator__text">Login To Account</div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="card card-body p-5">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('E-Mail') }}:</label>
                        <input id="email" name="email" type="text"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Your email address ...">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">{{ __('Password') }}:</label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Your first and last name ...">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <p class="text-right">
                            <a href="fixed-reset-password.html" class="small">Forgot your password?</a>
                        </p>
                    </div>
                    <button class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
    <div class="page-separator justify-content-center m-0">
        <div class="page-separator__text">or sign-in with</div>
    </div>
    <div class="bg-body pt-32pt pb-32pt pb-md-64pt text-center">
        <div class="container page__container">
            <a href="fixed-index.html" class="btn btn-secondary btn-block-xs">Facebook</a>
            <a href="fixed-index.html" class="btn btn-secondary btn-block-xs">Twitter</a>
            <a href="fixed-index.html" class="btn btn-secondary btn-block-xs">Google+</a>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@endsection