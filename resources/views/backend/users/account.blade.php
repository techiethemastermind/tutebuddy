@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
[dir=ltr] .avatar-2by1 {
    width: 8rem;
    height: 2.5rem;
}

[dir=ltr] label.content-left {
    justify-content: left;
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

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
                                        <strong class="card-title">My Account</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left">
                                <a href="#billing" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Billing</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left border-right">
                                <a href="#payment" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Payment</strong>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body tab-content">

                        <!-- Tab Content for General Setting -->
                        <div id="account" class="tab-pane p-4 fade text-70 active show">

                            {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' => ['admin.myaccount.update', $user->id]]) !!}

                            <div class="form-group">
                                <label class="form-label">Your photo</label>
                                <div class="media align-items-center">
                                    <a href="" class="media-left mr-16pt">
                                        @if($user->avatar)
                                        <img src="{{asset('/storage/avatars/' . $user->avatar) }}" id="user_avatar" alt="people" width="56" class="rounded-circle" />
                                        @else
                                        <img src="{{asset('/storage/avatars/no-avatar.jpg')}}" id="user_avatar" alt="people" width="56" class="rounded-circle" />
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                                data-preview="#user_avatar">
                                            <label class="custom-file-label" for="avatar_file">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Profile name</label>
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                <small class="form-text text-muted">Your profile name will be used as part of your public profile URL address.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                            </div>

                            <div class="form-group">
                                <label class="form-label">About you</label>
                                <textarea name="about" rows="3" class="form-control" placeholder="About you ...">{{ $user->about }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" checked="" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">Display your real name on your profile</label>
                                    <small class="form-text text-muted">If unchecked, your profile name will be displayed instead of your full name.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" checked="" id="customCheck2">
                                    <label class="custom-control-label" for="customCheck2">Allow everyone to see your profile</label>
                                    <small class="form-text text-muted">If unchecked, your profile will be private and no one except you will be able to view it.</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save changes</button>

                            {!! Form::close() !!}
                        </div>

                        <!-- Tab content for Logo and Favicon -->
                        <div id="billing" class="tab-pane p-4 fade text-70">

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
                                                <input type="checkbox" class="custom-control-input" checked="" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1">Enable automatic renewal</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">Payment information</label>
                                        <div class="col-sm-9 d-flex align-items-center">
                                            <img src="{{ asset('assets/img/visa.svg') }}" alt="visa" width="38" class="mr-16pt">
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

                        <!-- Tab for Mail configuration -->
                        <div id="payment" class="tab-pane p-4 fade text-70">

                            <div class="page-separator">
                                <div class="page-separator__text bg-white">Outstanding Payments</div>
                            </div>

                            <div class="alert alert-soft-warning mb-lg-32pt">
                                <div class="d-flex flex-wrap align-items-center">
                                    <div class="mr-8pt">
                                        <i class="material-icons">access_time</i>
                                    </div>
                                    <div class="flex" style="min-width: 180px">
                                        <small class="text-100">
                                            Please pay your amount due of
                                            <strong>$9.00</strong> for invoice <a href="fixed-billing-invoice.html" class="text-underline">10002331</a>
                                        </small>
                                    </div>
                                    <a href="fixed-billing-payment.html" class="btn btn-sm btn-link">Pay Now</a>
                                </div>
                            </div>

                            <div class="page-separator">
                                <div class="page-separator__text bg-white">Payment History</div>
                            </div>

                            <div class="card table-responsive">
                                <table class="table table-flush table-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Invoice no.</th>
                                            <th>Date</th>
                                            <th class="text-center">Amount</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>




                                        <tr>
                                            <td><a href="fixed-billing-invoice.html" class="text-underline">10002331</a></td>
                                            <td>26 Sep 2018</td>
                                            <td class="text-center">$9</td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary">Download <i class="icon--right material-icons">file_download</i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><a href="fixed-billing-invoice.html" class="text-underline">10003815</a></td>
                                            <td>29 Apr 2018</td>
                                            <td class="text-center">$9</td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary">Download <i class="icon--right material-icons">file_download</i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><a href="fixed-billing-invoice.html" class="text-underline">10007382</a></td>
                                            <td>31 Mar 2018</td>
                                            <td class="text-center">$9</td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary">Download <i class="icon--right material-icons">file_download</i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><a href="fixed-billing-invoice.html" class="text-underline">10004876</a></td>
                                            <td>30 May 2018</td>
                                            <td class="text-center">$9</td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary">Download <i class="icon--right material-icons">file_download</i></a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td><a href="fixed-billing-invoice.html" class="text-underline">10009392</a></td>
                                            <td>30 Apr 2018</td>
                                            <td class="text-center">$9</td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center">
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                                    <a href="fixed-billing-invoice.html" class="btn btn-sm btn-outline-secondary">Download <i class="icon--right material-icons">file_download</i></a>
                                                </div>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    
$(document).ready(function() {

    var active_tab = '{{ $_GET["active"] }}';
    $('div[role="tablist"]').find('a').removeClass('active');
    $('div[role="tablist"]').find('a[href="#' + active_tab + '"]').addClass('active');

    $('div.tab-pane').removeClass('show');
    $('div.tab-pane').removeClass('active');
    $('#' + active_tab).addClass('active');
    $('#' + active_tab).addClass('show');
});

$('#account form').submit(function(e){
    e.preventDefault();

    $(this).ajaxSubmit({
        success: function(res) {
            console.log(res);
            if(res.success) {
                swal("Success!", "Successfully updated", "success");
            }
        }
    });
});

</script>

@endpush

@endsection