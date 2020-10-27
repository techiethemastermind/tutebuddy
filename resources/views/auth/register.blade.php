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

            <div class="page-separator mb-4">
                <div class="page-separator__text">Sign Up As {{ $reg_type }}</div>
            </div>

            <div class="row">
                
                <div class="col-lg-5 p-0 mx-auto">
                    
                    <form method="POST" action="{{ route('register') }}" class="card card-body p-32pt">
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

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" value="" id="chk_terms" required="">
                                <label class="custom-control-label" for="chk_terms">
                                    I agree to the <a href="/page/terms-and-conditions" class="text-primary">Terms and Conditions</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create account</button>
                        <input type="hidden" name="recaptcha_v3" id="recaptcha_v3">
                    </form>
                </div>

                @if($reg_type == 'Instructor')
                <div class="col-lg-7 align-items-center">
                    <div class="flex" style="max-width: 100%">
                        <div class="card p-relative o-hidden mb-0">
                            <div class="card-header card-header-tabs-basic nav px-0" role="tablist">
                                <a href="#tab1" data-toggle="tab" role="tab" aria-selected="true" class="active">Become An Instructor</a>
                                <a href="#tab2" data-toggle="tab" role="tab" aria-selected="true" class="">Instructor Rules</a>
                                <a href="#tab3" data-toggle="tab" role="tab" aria-selected="true" class="">Start With Courses</a>
                            </div>
                            <div class="card-body tab-content text-70">
                                <div id="tab1" class="tab-pane fade in active show">
                                    <p>Unlock the potential of reaching out to millions of students through our online tutoring. You can offer pre-designed courses of your own or teach students on a one-on-one sessions.</p>
                                    <p>Use our powerful course builder to create engaging courses for your students and offer services at your own price. We offer multiple delivery media like text, audio, video or live streaming to help you provide the best means of transferring knowledge to your students.</p>
                                    <p>You can also use our revolutionary platform to organize live classrooms or on-on-one training to your students on demand.</p>
                                    <p>The best part is you don't pay anything to become a member.</p>
                                    <p>So what are you waiting for? Submit your application to become a member of our growing family of skilled educators.</p>
                                </div>

                                <div id="tab2" class="tab-pane fade">
                                    <p>Instructors must be 18 years of age and above with the legal authority to sign binding contracts with tutebuddy.com. Please review some of the important rules of working on tutebuddy.com as an instructor.</p>
                                    <p>Instructors are required to submit course material that does not contain any inappropriate, offensive, racist, hateful, sexist, pornographic, false, misleading, incorrect, infringing, defamatory or libelous content or information. Doing so will result in the immediate termination of the account.</p>
                                    <p>Instructors must ensure that all content posted is their original work and does not infringe on the copyright of others.</p>
                                    <p>All content is subject to approval by tutebuddy.com and we reserve the right to block any content that violates the terms of service.</p>
                                    <p>For more details please read the <a href="/page/teach-on-tutebuddy" class="text-primary-dodger-blue">instructor terms and conditions</a> and <a href="/page/terms-and-conditions" class="text-primary-dodger-blue">General terms and conditions</a> page.</p>
                                </div>

                                <div id="tab3" class="tab-pane fade">
                                    <p>By joining as an Instructor, you will get access to a host of tools that will enable you to offer innovative methods to engage with your students.</p>
                                    <p>Register with tutebuddy.com and start creating courses using powerful multimedia tools.</p>
                                    <p>You do not have to pay anything to be a part of tutebuddy.com</p>
                                    <p>Offer Text, Video and Live training courses to your students. Communicate, assess and certify your courses. </p>
                                    <p>We hope this will be a rewarding experience for you and your students.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-7">
                    <div class="card card-body p-4">
                        <img src="{{ asset('assets/img/bg_register_now_1.jpg') }}" alt="" class="avatar-img rounded">
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="page-separator justify-content-center m-0">
            <div class="page-separator__text">or sign-in with</div>
        </div>
        <div class="page-section text-center">
            <div class="container page__container">
                <a href="" class="btn btn-secondary btn-block-xs">Facebook</a>
                <a href="" class="btn btn-secondary btn-block-xs">Twitter</a>
                <a href="" class="btn btn-secondary btn-block-xs">Google+</a>
            </div>
        </div>
    </div>
</div>

@if(config("access.captcha.registration") > 0)

@push('after-scripts')

<script src="https://www.google.com/recaptcha/api.js?render={{ config('captcha.key') }}"></script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('captcha.key') }}", {action: 'register'}).then(function(token) {
            if(token) {
                $("#recaptcha_v3").val(token);
            }
        });
    });
</script>

@endpush

@endif

@endsection