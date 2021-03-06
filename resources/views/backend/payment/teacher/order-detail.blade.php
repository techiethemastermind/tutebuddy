@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.payment.order_detail.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.payment.order_detail.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="row mb-32pt">
            <div class="col-6">
                <div class="page-separator">
                    <div class="page-separator__text">@lang('labels.backend.payment.order_detail.payment_detail')</div>
                </div>

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.order_id'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->uuid }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.payment_date'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M d Y h:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.amount'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">{{ getCurrency(config('app.currency'))['symbol'] . $order->amount }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.payment_status'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">{{ $order->status }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-6">
                <div class="page-separator">
                    <div class="page-separator__text">@lang('labels.backend.payment.order_detail.customer')</div>
                </div>

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.general.name'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->user->name }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.general.email'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->user->email }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.general.phone_number'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->user->phone_number }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Address: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->user->address . ', ' . $order->user->city . ', ' . $order->user->country }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.payment.order_detail.course_details')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-order js-lists-values-date">
                <table class="table mb-0 thead-border-top-0 table-nowra">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.course') </th>
                            <th> @lang('labels.backend.table.price') </th>
                            <th> @lang('labels.backend.table.end_date') </th>
                            <th> @lang('labels.backend.payment.table.payment_status') </th>
                            <th>  </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                        @if(!empty($item->course))
                        <?php
                            $completed = false;
                            if(\Carbon\Carbon::parse($item->course->end_date)->diffInDays(\Carbon\Carbon::now()) > 7 &&
                            $item->course->end_date < \Carbon\Carbon::now()->format('Y-m-d')) {
                                $completed = true;
                            }
                        ?>
                        <tr>
                            <td></td>
                            <td>
                                <div class="d-flex align-items-center" style="white-space: nowrap;">

                                    <div class="avatar avatar-32pt mr-8pt">
                                        @if(empty($item->course->course_image))
                                        <span class="avatar-title rounded-circle">{{ substr($item->course->title, 0, 2) }}</span>
                                        @else
                                        <img src="{{ asset('storage/uploads/' . $item->course->course_image ) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @endif
                                    </div>

                                    <div class="flex ml-4pt">
                                        <div class="d-flex flex-column">
                                            <p class="mb-0"><strong>{{ $item->course->title }}</strong></p>
                                            <small class="text-50">{{ $item->course->category->name }}</small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget">
                                        <strong>{{ getCurrency(config('app.currency'))['symbol'] . $item->price }}</strong>
                                    </small>
                                    <small class="text-50">@lang('labels.backend.payment.order_detail.completed')</small>
                                </div>
                            </td>
                            <td><strong>{{ \Carbon\Carbon::parse($item->course->end_date)->format('M d Y') }}</strong></td>
                            <td>
                                @if($completed)
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">@lang('labels.backend.payment.order_detail.completed')</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>
                                @else
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">@lang('labels.backend.payment.order_detail.progressing')</small>
                                    <span class="indicator-line rounded bg-info"></span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if(!$completed)
                                <a href="" class="btn btn-sm btn-accent">@lang('labels.backend.payment.order_detail.refund')</a>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection