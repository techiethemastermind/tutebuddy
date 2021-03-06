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
                <div class="page-separator__text">@lang('labels.auth.register.title') {{ $reg_type }}</div>
            </div>

            <div class="row">
                
                <div class="col-lg-5 p-0 mx-auto">
                    
                    <form id="frm_register" method="POST" action="{{ route('register') }}" class="card card-body p-32pt">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">@lang('labels.auth.register.first_last_name') *:</label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.first_last_name_placeholder')" tute-no-empty>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">@lang('labels.auth.register.your_email') *:</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.your_email_placeholder')" value="{{ old('email') }}" tute-no-empty>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="timezone">@lang('labels.auth.register.your_timezone') *:</label>
                            <select name="timezone" class="form-control  @error('timezone') is-invalid @enderror"></select>
                        </div>

                        <div class="form-group mb-24pt">
                            <label class="form-label" for="password">@lang('labels.auth.register.password'):</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.password_placeholder')">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span class="invalid-feedback" role="alert">
                                Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                            </span>
                        </div>
                        <div class="form-group mb-24pt">
                            <label class="form-label" for="password">@lang('labels.auth.register.confirm_password'):</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control"
                                placeholder="@lang('labels.auth.register.confirm_password_placeholder')">
                        </div>
                        <input type="hidden" name="role" value="{{ $reg_type }}">

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" value="" id="chk_terms" required="">
                                <label class="custom-control-label" for="chk_terms">
                                    @lang('string.auth.register.terms_and_conditions')
                                </label>
                            </div>
                        </div>

                        <button type="button" id="btn_register" class="btn btn-primary">@lang('labels.auth.register.create_account')</button>
                        <input type="hidden" name="recaptcha_v3" id="recaptcha_v3">
                    </form>
                </div>

                @if($reg_type == 'Instructor')
                <div class="col-lg-7 align-items-center">
                    <div class="flex" style="max-width: 100%">
                        <div class="card p-relative o-hidden mb-0">
                            <div class="card-header card-header-tabs-basic nav px-0 font-size-16pt" role="tablist">
                                <a href="#tab1" data-toggle="tab" role="tab" aria-selected="true" class="active"> 
                                    @lang('labels.auth.register.become_instructor')</a>
                                <a href="#tab2" data-toggle="tab" role="tab" aria-selected="true" class="">
                                    @lang('labels.auth.register.instructor_rules')</a>
                                <a href="#tab3" data-toggle="tab" role="tab" aria-selected="true" class="">
                                    @lang('labels.auth.register.start_with_course')</a>
                            </div>
                            <div class="card-body tab-content text-70 font-size-16pt">
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
            <div class="page-separator__text">@lang('labels.auth.login.sign_with')</div>
        </div>
        <div class="page-section text-center">
            <div class="container page__container">
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.facebook')</a>
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.twitter')</a>
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.google_plus')</a>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

@if(config("access.captcha.registration") > 0)

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

@endif

<script>
    $(function() {
        var offset = new Date().getTimezoneOffset() / 60;
        if(Math.abs(offset) < 10) {
            
            if(offset < 0) {
                offset = '+0' + Math.round(Math.abs(offset)) + ':00';
            } else {
                offset = '-0' + Math.round(Math.abs(offset)) + ':00';
            }
        } else {
            if(offset < 0) {
                offset = '+' + Math.round(Math.abs(offset)) + ':00';
            } else {
                offset = '-' + Math.round(Math.abs(offset)) + ':00';
            }
        }
        $('select[name="timezone"]').timezones();
        $('select[name="timezone"] option[data-offset="'+ offset +'"]').prop('selected', true);

        var pattern_pwd = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/;
        var pattern_name = /^[a-zA-Z]+ [a-zA-Z]+$/;

        $('#name').on('blur', function(e) {
            if(!pattern_name.test($(this).val())) {
                if(!$(this).hasClass('is-invalid')) {
                    $(this).addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
        });

        $('#password').on('keyup', function(e) {
            var rlt = checkPassword($(this).val());
            if(!rlt) {
                if(!$(this).hasClass('is-invalid')) {
                    $(this).addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
        });

        function checkPassword(password) {
            if(pattern_pwd.test(password)){
                return true;
            }else{
                return false;
            }
        }

        $('#btn_register').on('click', function(e) {
            var isCheckedTerms = $('#chk_terms').is(":checked");

            if($('#frm_register').find('.is-invalid').length > 0) {
                swal('Error!', 'Please fix invalid fields', 'error');
            } else {
                if(isCheckedTerms) {
                    $('#frm_register').submit();
                } else {
                    swal('Error!', 'Please Check our Terms and Conditions ', 'error');
                }
            }
        });

    });
</script>

@endpush

@endsection