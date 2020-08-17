@extends('layouts.app')

@section('content')

<?php
    $reg_type = 'Student';
    if(isset($_GET['r']) && $_GET['r'] == 't') {
        $reg_type = 'Instructor';
    }
?>

<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">
            <div class="col-lg-6 p-0 mx-auto">
                <div class="page-separator mb-4">
                    <div class="page-separator__text">Sign Up As {{ $reg_type }}</div>
                </div>
                <form method="POST" action="{{ route('register') }}" class="card card-body p-5">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="name">Your first and last name:</label>
                        <input id="name" type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Your first and last name ...">

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Your email:</label>
                        <input id="email" type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Your email address ...">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group mb-24pt">
                        <label class="form-label" for="password">Password:</label>
                        <input id="password" type="password" name="password" class="form-control"
                            placeholder="Your password ...">
                    </div>
                    <div class="form-group mb-24pt">
                        <label class="form-label" for="password">Confirm Password:</label>
                        <input id="password-confirm" type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm password ...">
                    </div>
                    <input type="hidden" name="role" value="{{ $reg_type }}">
                    <button type="submit" class="btn btn-primary">Create account</button>
                </form>
            </div>
        </div>
        <div class="page-separator justify-content-center m-0">
            <div class="page-separator__text">or sign-in with</div>
        </div>
        <div class="page-section text-center">
            <div class="container page__container">
                <a href="fixed-signup-payment.html" class="btn btn-secondary btn-block-xs">Facebook</a>
                <a href="fixed-signup-payment.html" class="btn btn-secondary btn-block-xs">Twitter</a>
                <a href="fixed-signup-payment.html" class="btn btn-secondary btn-block-xs">Google+</a>
            </div>
        </div>
    </div>
</div>

@endsection