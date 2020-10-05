@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

    <style>
        [dir=ltr] .avatar-2by1 {
            width: 8rem;
            height: 2.5rem;
        }

        [dir=ltr] label.content-left {
            justify-content: left;
        }

        .profile-avatar img {
            object-fit: cover;
            display: block;
            width: 250px;
            height: 250px;
            object-position: top;
        }
    </style>

@endpush

<?php

if(!isset($_GET["active"])) {
    $_GET["active"] = 'account';
}

?>


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">My Account</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Account
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header p-0 nav">
                    <div class="row no-gutters" role="tablist">

                        <div class="col-auto">
                            <a href="#account" data-toggle="tab" role="tab" aria-selected="true"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">Information</strong>
                                </span>
                            </a>
                        </div>

                        @if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Instructor'))
                        <div class="col-auto border-left border-right">
                            <a href="#bank" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">Banking</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('Student'))
                            <div class="col-auto border-left border-right">
                                <a href="#child" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Child Account</strong>
                                    </span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body tab-content">

                    <!-- Tab Content for Profile Setting -->
                    <div id="account" class="tab-pane p-4 fade text-70 active show">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="media">
                                <div class="media-left mr-32pt">
                                    <label class="form-label">Your photo</label>
                                    <div class="profile-avatar mb-16pt">
                                        @if($user->avatar)
                                            <img src="{{ asset('/storage/avatars/' . $user->avatar) }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @else
                                            <img src="{{ asset('/storage/avatars/no-avatar.jpg') }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                                data-preview="#user_avatar">
                                            <label class="custom-file-label" for="avatar_file">Choose file</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="media-body">
                                    <div class="form-group">
                                        <label class="form-label">Profile name</label>
                                        {!! Form::text('name', null, array('placeholder' => 'Name','class' =>
                                        'form-control')) !!}
                                        <small class="form-text text-muted">Your profile name will be used as part of
                                            your public profile URL address.</small>
                                    </div>

                                    @if($user->hasRole('Instructor'))

                                    <div class="form-group">
                                        <label class="form-label">Headline</label>
                                        {!! Form::text('headline', null, array('placeholder' => 'Headline', 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">About you</label>
                                        {!! Form::textarea('about', null, array('placeholder' => 'About You...', 'class' =>
                                        'form-control', 'rows' => 5)) !!}
                                    </div>

                                    @endif

                                    <div class="page-separator mt-32pt">
                                        <div class="page-separator__text bg-white">Contact Information</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address</label>
                                                {!! Form::text('email', null, array('placeholder' => 'Email', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                {!! Form::text('phone_number', null, array('placeholder' => 'Phone Number', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Country</label>
                                                {!! Form::text('country', null, array('placeholder' => 'Country', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">State</label>
                                                {!! Form::text('state', null, array('placeholder' => 'State', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">City</label>
                                                {!! Form::text('city', null, array('placeholder' => 'City', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Zip code</label>
                                                {!! Form::text('zip', null, array('placeholder' => 'Zip Code', 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        {!! Form::text('address', null, array('placeholder' => 'Address', 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Timezone</label>
                                                <select name="timezone" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>

                                    @if($user->hasRole('Instructor'))
                                    <div class="page-separator mt-32pt">
                                        <div class="page-separator__text bg-white">Profession</div>
                                    </div>

                                    <div class="form-group">
                                        <select id="categories" name="categories[]" class="form-control" multiple="multiple">
                                        @if(!empty($user->profession))

                                        @php $pros = json_decode($user->profession); @endphp

                                        @foreach($pros as $pro)
                                        <?php
                                            $category = App\Models\Category::find($pro);
                                            $name = !empty($category) ? $category->name : $pro;
                                        ?>
                                        <option value="{{ $pro }}" selected >{{ $name }}</option>
                                        @endforeach

                                        @endif
                                        </select>
                                    </div>

                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>

                        {!! Form::close() !!}
                    </div>

                    <!-- Tab content for billing information -->
                    <div id="bank" class="tab-pane p-4 fade text-70">

                        <div class="list-group list-group-form">
                            <div class="list-group-item">
                                <div class="form-group row align-items-center mb-0">
                                    <label class="col-form-label form-label col-sm-3">Your current plan</label>
                                    <div class="col-sm-9 d-flex align-items-center">
                                        <div class="flex">Basic, $9 per month</div>
                                        <a href="fixed-billing-upgrade.html" class="text-secondary">Change plan</a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-group row align-items-center mb-0">
                                    <label class="col-form-label form-label col-sm-3">Billing cycle</label>
                                    <div class="col-sm-9">
                                        <p>You will be charged $9 on Aug 20, 2018</p>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" checked=""
                                                id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Enable automatic
                                                renewal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-group row align-items-center mb-0">
                                    <label class="col-form-label form-label col-sm-3">Payment information</label>
                                    <div class="col-sm-9 d-flex align-items-center">
                                        <img src="{{ asset('assets/img/visa.svg') }}" alt="visa"
                                            width="38" class="mr-16pt">
                                        <div class="flex">Visa ending with 2819</div>
                                        <a href="fixed-billing-payment.html" class="text-secondary">Change method</a>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-group row align-items-center mb-0">
                                    <label class="col-form-label form-label col-sm-3">Cancel</label>
                                    <div class="col-sm-9">
                                        <a href="" class="btn btn-outline-secondary">Cancel subscription</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab for Child Account -->
                    <div id="child" class="tab-pane p-4 fade text-70">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="chkChild" @if($child) checked=""
                                    @endif>
                                <label class="custom-control-label" for="chkChild">Add Child account?</label>
                                <small class="form-text text-muted">If checked then you can add child account</small>
                            </div>
                        </div>

                        <form id="frm_child" method="POST" action="" enctype="multipart/form-data"
                            style="display: none;">

                            <div class="page-separator">
                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Child name</label>
                                <input type="text" name="name" class="form-control"
                                    value="@if($child) {{ $child->name }} @endif" placeholder="Name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Child Nick Name</label>
                                <input type="text" name="nick_name" class="form-control"
                                    value="@if($child) {{ $child->nick_name }} @endif" placeholder="Nick Name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" value="">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" value="">
                            </div>

                            <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">Parent Phone number</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ $user->phone_number }}">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">Send OTP</button>
                                </div>
                            </div>

                            <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">Enter OTP</label>
                                    <input type="text" name="otp" class="form-control" value="">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">Verify</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Upload Parent ID</label>
                                <div class="custom-file">
                                    <input type="file" id="file" class="custom-file-input">
                                    <label for="file" class="custom-file-label">Choose file</label>
                                </div>
                                <small class="form-text text-muted">Upload a clear ID in png, jpeg or PDF format</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Relationship to child</label>
                                <input type="text" name="relation" class="form-control"
                                    value="{{ $user->relationship }}">
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildTerm">
                                    <label class="custom-control-label" for="chkChildTerm">
                                        I agree to the Terms and Conditions on behalf of my ward/child
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildlegal">
                                    <label class="custom-control-label" for="chkChildlegal">
                                        I am the legal guardian of the child whose account I am creating and have the
                                        legal right to consent for this account
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt-32pt">
                                <button type="submit" class="btn btn-primary">CREATE CHILD ACCOUNT</button>
                            </div>
                        </form>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

    <!-- Timezone Picker -->
    <script src="{{ asset('assets/js/timezones.full.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.js') }}"></script>

    <script>
        $(function () {
            var active_tab = '{{ $_GET["active"] }}';
            $('div[role="tablist"]').find('a').removeClass('active');
            $('div[role="tablist"]').find('a[href="#' + active_tab + '"]').addClass('active');

            $('div.tab-pane').removeClass('show');
            $('div.tab-pane').removeClass('active');
            $('#' + active_tab).addClass('active');
            $('#' + active_tab).addClass('show');

            if ($('#chkChild').prop('checked')) {
                $('#frm_child').show();
            };

            $('#chkChild').on('change', function () {
                if ($(this).prop('checked')) {
                    $('#frm_child').show();
                } else {
                    $('#frm_child').hide();
                }
            });

            // Timezone
            $('select[name="timezone"]').timezones();
            $('select[name="timezone"]').val('{{ $user->timezone }}').change();

            var select = $('#categories').select2({
                ajax: {
                    url: "{{ route('admin.select.getCategoriesByAjax') }}",
                    dataType: 'json',
                    delay: 250
                },
                tags: true
            });

            $('#account form').submit(function (e) {
                e.preventDefault();

                $(this).ajaxSubmit({
                    success: function (res) {
                        console.log(res);
                        if (res.success) {
                            swal("Success!", "Successfully updated", "success");
                        }
                    }
                });
            });
        });
    </script>

@endpush

@endsection