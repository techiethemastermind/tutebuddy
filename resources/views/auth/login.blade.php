@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">
            <div class="col-lg-6 p-0 mx-auto">

                <div class="page-separator mb-4">
                    <div class="page-separator__text">@lang('labels.auth.login.title')</div>
                </div>

                @error('captcha')
                <div class="alert alert-accent" role="alert">
                    <div class="d-flex flex-wrap align-items-center">
                        <i class="material-icons mr-8pt">error</i>
                        <div class="media-body" style="min-width: 180px">
                            {{ $message }}
                        </div>
                    </div>
                </div>
                @enderror

                <form method="POST" action="{{ route('login') }}" class="card card-body p-5">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="username">@lang('labels.auth.login.username'):</label>
                        <input id="username" name="username" type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="@lang('labels.auth.login.username_placeholder')">

                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">@lang('labels.auth.login.password'):</label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="@lang('labels.auth.login.password') ...">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <p class="text-right">
                            <a href="/password/reset" class="small">@lang('labels.auth.login.forgot_password')</a>
                        </p>
                    </div>
                    <input type="hidden" name="role" value="{{ Request::is('admin') ? 'admin' : 'user' }}">
                    <button class="btn btn-primary">@lang('labels.auth.login.login_button')</button>
                    <input type="hidden" name="recaptcha_v3" id="recaptcha_v3">
                </form>
            </div>
        </div>
    </div>
    <div class="page-separator justify-content-center m-0">
        <div class="page-separator__text">@lang('labels.auth.login.sign_with')</div>
    </div>
    <div class="bg-body pt-32pt pb-32pt pb-md-64pt text-center">
        <div class="container page__container">
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.facebook')</a>
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.twitter')</a>
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.google_plus')</a>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@if(config("access.captcha.registration") > 0)

@push('after-scripts')

<script src="https://www.google.com/recaptcha/api.js?render={{ config('captcha.key') }}"></script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('captcha.key') }}", {action: 'login'}).then(function(token) {
            if(token) {
                $("#recaptcha_v3").val(token);
            }
        });
    });
</script>

@endpush

@endif

@endsection