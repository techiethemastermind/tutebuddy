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
                    <h2 class="mb-0">@lang('labels.backend.my_account.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.my_account.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        @include('layouts.parts.alert-messages')

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header p-0 nav">
                    <div class="row no-gutters" role="tablist">

                        <div class="col-auto">
                            <a href="#account" data-toggle="tab" role="tab" aria-selected="true"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.personal_information')</strong>
                                </span>
                            </a>
                        </div>

                        @if($user->hasRole('Instructor'))
                        <div class="col-auto border-left border-right">
                            <a href="#profession" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.personal_information')</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        <div class="col-auto border-left border-right">
                            <a href="#password" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.change_password')</strong>
                                </span>
                            </a>
                        </div>

                        @if(auth()->user()->hasRole('Administrator') || auth()->user()->hasRole('Instructor'))
                        <div class="col-auto border-left border-right">
                            <a href="#bank" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.banking')</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('Student'))
                            <div class="col-auto border-left border-right">
                                <a href="#child" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">@lang('labels.backend.my_account.child_account')</strong>
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
                                    <label class="form-label">@lang('labels.backend.my_account.your_photo')</label>
                                    <div class="profile-avatar mb-16pt">
                                        @if($user->avatar)
                                            <img src="{{ asset('/storage/avatars/' . $user->avatar) }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @else
                                            <img src="{{ asset('/images/no-avatar.jpg') }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                                data-preview="#user_avatar">
                                            <label class="custom-file-label" for="avatar_file">@lang('labels.backend.general.choose_file')</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="media-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.profile_name')</label>
                                        {!! Form::text('name', null, array('placeholder' => "Name",'class' =>
                                        'form-control')) !!}
                                        <small class="form-text text-muted">
                                            @lang('string.backend.my_account.profile_name')
                                        </small>
                                    </div>

                                    @if($user->hasRole('Instructor'))

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.headline')</label>
                                        {!! Form::text('headline', null, array('placeholder' => "Headline", 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.about')</label>
                                        {!! Form::textarea('about', null, array('placeholder' => "About", 'class' =>
                                        'form-control', 'rows' => 5)) !!}
                                    </div>

                                    @endif

                                    <div class="page-separator mt-32pt">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.contact_information')</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.email_address')</label>
                                                {!! Form::text('email', null, array('placeholder' => "Email Address", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.phone_number')</label>
                                                {!! Form::text('phone_number', null, array('placeholder' => "Phone Number", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.country')</label>
                                                {!! Form::text('country', null, array('placeholder' => "Country", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.state')</label>
                                                {!! Form::text('state', null, array('placeholder' => "State", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.city')</label>
                                                {!! Form::text('city', null, array('placeholder' => "City", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.zip_code')</label>
                                                {!! Form::text('zip', null, array('placeholder' => "Zip Code", 'class' =>
                                                'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.address')</label>
                                        {!! Form::text('address', null, array('placeholder' => "", 'class' =>
                                        'form-control')) !!}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.timezone')</label>
                                                <select name="timezone" class="form-control"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            @if($user->profile == 0)
                            <button type="submit" class="btn btn-primary">@lang('labels.backend.general.save_changes')</button>
                            @elseif($user->profile == 3)
                            <button type="submit" class="btn btn-primary" disabled>@lang('labels.backend.general.save_changes')</button>
                            @elseif($user->profile == 1)
                            <button type="submit" class="btn btn-primary">@lang('labels.backend.buttons.update')</button>
                            @endif
                        </div>

                        {!! Form::close() !!}
                    </div>

                    <!-- Tab Content for Professional Information -->
                    <div id="profession" class="tab-pane p-4 fade text-70">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                            ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">
                                            @lang('labels.backend.my_account.profession_certification')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_qualifications" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-qualifications">

                            @if(!empty($user->qualifications))

                                @foreach(json_decode($user->qualifications) as $qualification)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')"
                                        value="{{ $qualification }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.achievement')</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_achievements" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-achievements">

                            @if(!empty($user->achievements))

                                @foreach(json_decode($user->achievements) as $achievement)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')"
                                        value="{{ $achievement }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt col-11">
                            <div class="page-separator">
                                <div class="page-separator__text bg-white">@lang('labels.backend.my_account.experience')</div>
                            </div>
                            {!! Form::textarea('experience', null, array('placeholder' => "Experience", 'class' =>
                                'form-control', 'rows' => 5)) !!}
                        </div>

                        <div class="form-group mt-64pt col-11">
                            <div class="page-separator">
                                <div class="page-separator__text bg-white">@lang('labels.backend.my_account.profession')</div>
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

                            <div class="form-group text-right">
                                @if($user->profile == 0)
                                <button type="submit" class="btn btn-primary">@lang('labels.backend.general.save_changes')</button>
                                @elseif($user->profile == 3)
                                <button type="submit" class="btn btn-primary" disabled>@lang('labels.backend.general.save_changes')</button>
                                @elseif($user->profile == 1)
                                <button type="submit" class="btn btn-primary">@lang('labels.backend.buttons.update')</button>
                                @endif
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>

                    <!-- Tab Content for Profile Setting -->
                    <div id="password" class="tab-pane p-4 fade text-70">
                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group mb-48pt">
                            <label class="form-label" for="current_pwd">@lang('labels.backend.my_account.current_password'):</label>
                            <input id="current_pwd" name="current_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.current_password_placeholder')" tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_pwd">@lang('labels.backend.my_account.new_password'):</label>
                            <input id="new_pwd" name="new_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.new_password_placeholder')">
                            <span class="invalid-feedback" role="alert">
                                Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cfm_pwd">@lang('labels.backend.my_account.confirm_password'):</label>
                            <input id="cfm_pwd" name="confirm_password" type="password" class="form-control" placeholder="Confirm your new password ..." tute-no-empty>
                        </div>

                        <input type="hidden" name="update_type" value="password">

                        <button type="submit" class="btn btn-primary mt-48pt">@lang('labels.backend.my_account.save_password')</button>
                        {!! Form::close() !!}
                    </div>

                    <!-- Tab content for billing information -->
                    <div id="bank" class="tab-pane p-4 fade text-70">

                        <div class="col-lg-10 p-0">
                            <div class="list-group list-group-form">
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="flex">
                                        @lang('labels.backend.my_account.bank_note')
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <fieldset aria-labelledby="label-type" class="m-0 form-group">
                                        <div class="form-row align-items-center">
                                            <label for="payment_cc" id="label-type" class="col-md-3 col-form-label form-label">
                                            @lang('labels.backend.my_account.payment_type')
                                            </label>
                                            <div role="group" aria-labelledby="label-type" class="col-md-9">
                                                <div role="group" class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-secondary">
                                                        <input type="radio" id="payment_bank" name="payment_type" value="cc" checked="" aria-checked="true">
                                                        @lang('labels.backend.my_account.bank_detail')
                                                    </label>
                                                    <label class="btn btn-outline-secondary active">
                                                        <input type="radio" id="payment_account" name="payment_type" value="pp" aria-checked="true">
                                                        @lang('labels.backend.my_account.link_account')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                {!! Form::model($user->bank, ['method' => 'POST', 'route' => ['admin.myaccount.update', $user->id]]) !!}
                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.account_number') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('account_number', null, 
                                                array(
                                                    'placeholder' => "Account Number",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.ifsc') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('ifsc', null, 
                                                array(
                                                    'placeholder' => "IFSC",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.beneficiary_name') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('account_holder_name', null, 
                                                array(
                                                    'placeholder' => "Beneficiary Name",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.account_type')</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('account_type', null, 
                                                array(
                                                    'placeholder' => "Account Type",
                                                    'class' => 'form-control'
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="update_type" value="bank">
                                            <button type="submit" class="btn btn-primary">@lang('labels.backend.general.save_changes')</button>
                                        </div>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                    <!-- Tab for Child Account -->
                    <div id="child" class="tab-pane p-4 fade text-70">

                        <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent">

                            @foreach($user->child() as $child)
                            <div class="accordion__item" lesson-id="{{ $child->id }}">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#child-{{ $child->id }}" data-parent="#parent">
                                    <span class="flex">{{ $child->name }}</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="child-{{ $child->id }}">
                                    <div class="accordion__menu-link">
                                        <form method="POST" action="{{ route('admin.myaccount.child.update') }}" class="w-100" enctype="multipart/form-data">@csrf

                                            <div class="page-separator">
                                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.username'): </label>
                                                <span class="font-size-16pt">{{ $child->username }}</span>
                                                <input type="hidden" name="child_id" class="form-control" value="@if($child){{ $child->id }}@endif">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.child_name')</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="@if($child) {{ $child->name }} @endif" placeholder="@lang('labels.backend.my_account.child_name')">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.child_nick_name')</label>
                                                <input type="text" name="nick_name" class="form-control"
                                                    value="@if($child) {{ $child->nick_name }} @endif" placeholder="Nick Name">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.password')</label>
                                                <input type="password" name="password" class="form-control" value="" 
                                                    placeholder="@lang('labels.backend.my_account.password')">
                                                <span class="invalid-feedback" role="alert">
                                                    Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.confirm_password')</label>
                                                <input type="password" name="confirm_password" class="form-control" value="" 
                                                    placeholder="@lang('labels.backend.my_account.password')">
                                            </div>

                                            <div class="form-group mt-32pt">
                                                <button type="submit" class="btn btn-primary">@lang('labels.backend.my_account.update_child_account')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <!-- <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="chkChild" @if($user->child()) checked="" @endif>
                                <label class="custom-control-label" for="chkChild">
                                    @lang('labels.backend.my_account.add_child_account.title')
                                </label>
                                <small class="form-text text-muted">
                                    @lang('labels.backend.my_account.add_child_account.description')
                                </small>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <button type="button" id="btn_add_child" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ Add Child</button>
                        </div>

                        <!-- ============== -->

                        <form id="frm_child" method="POST" action="{{ route('admin.myaccount.child') }}" enctype="multipart/form-data"
                            style="display: none;">@csrf

                            <div class="page-separator">
                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.child_name')</label>
                                <input type="text" name="name" class="form-control"
                                    value="" placeholder="@lang('labels.backend.my_account.child_name')">
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.child_nick_name')</label>
                                <input type="text" name="nick_name" class="form-control"
                                    value="" placeholder="Nick Name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.password')</label>
                                <input type="password" name="password" class="form-control" value="" 
                                    placeholder="@lang('labels.backend.my_account.password')">
                                <span class="invalid-feedback" role="alert">
                                    Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                                </span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.confirm_password')</label>
                                <input type="password" name="confirm_password" class="form-control" value="" 
                                    placeholder="@lang('labels.backend.my_account.password')">
                            </div>

                            <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">@lang('labels.backend.my_account.parent_phone_number')</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ $user->phone_number }}">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">@lang('labels.backend.my_account.send_otp')</button>
                                </div>
                            </div>

                            <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">@lang('labels.backend.my_account.enter_otp')</label>
                                    <input type="text" name="otp" class="form-control" value="">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">@lang('labels.backend.buttons.verify')</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    @lang('labels.backend.my_account.upload_parent_id')
                                </label>
                                <div class="custom-file">
                                    <input type="file" id="file" class="custom-file-input">
                                    <label for="file" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                </div>
                                <small class="form-text text-muted">
                                    @lang('labels.backend.my_account.upload_parent_description')
                                </small>
                            </div>

                            <!-- <div class="form-group">
                                <label class="form-label">
                                    @lang('labels.backend.my_account.relationship_to_child')
                                </label>
                                <input type="text" name="relation" class="form-control"
                                    value="{{ $user->relationship }}">
                            </div> -->

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildTerm">
                                    <label class="custom-control-label" for="chkChildTerm">
                                        @lang('string.backend.my_account.terms_and_condition_note')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildlegal">
                                    <label class="custom-control-label" for="chkChildlegal">
                                        @lang('string.backend.my_account.legal_note')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt-32pt">
                                <button type="submit" class="btn btn-primary">@lang('labels.backend.my_account.create_child_account')</button>
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

            // if ($('#chkChild').prop('checked')) {
            //     $('#frm_child').show();
            // };

            // $('#chkChild').on('change', function () {
            //     if ($(this).prop('checked')) {
            //         $('#frm_child').show();
            //     } else {
            //         $('#frm_child').hide();
            //     }
            // });

            $('#btn_add_child').on('click', function(e) {
                $('#frm_child').show();
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

            var tmp = `<div class="row form-inline mb-8pt">
                            <div class="col-10">
                                <input type="text" class="form-control w-100" placeholder="Professional Qualifications and Certifications">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                            </div>
                        </div>`;

            // Add Qualitications
            $('#btn_add_qualifications').on('click', function(e) {
                var row_qualification = $(tmp).clone();
                row_qualification.find('input').attr('name', 'qualification[]');
                row_qualification.appendTo('#profession .wrap-qualifications');
            });

            // Add Achievements
            $('#btn_add_achievements').on('click', function(e) {
                var row_achievement = $(tmp).clone();
                row_achievement.find('input').attr('name', 'achievement[]');
                row_achievement.appendTo('#profession .wrap-achievements');
            });

            $('#profession').on('click', 'button.remove', function(e) {
                $(this).closest('.row').remove();
            });

            $('form').submit(function (e) {
                e.preventDefault();

                $(this).ajaxSubmit({
                    success: function (res) {
                        // console.log(res);
                        if(res.success) {
                            swal("Success!", res.message, "success");
                            if(res.action != undefined && res.action == 'child') {
                                location.reload();
                            }
                        } else {
                            swal("Error!", res.message, "error");
                        }
                    }
                });
            });

            var pattern = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/;

            $('#new_pwd').on('keyup', function(e) {
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

            $('input[name="password"]').on('keyup', function(e) {
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
                if(pattern.test(password)){
                    return true;
                }else{
                    return false;
                }
            }
            
        });
    </script>

@endpush

@endsection