@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.payment.withdraws.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.payment.withdraws.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-order js-lists-values-date">
                <table id="tbl_withdraws" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>@lang('labels.backend.payment.table.instructor')</th>
                            <th> @lang('labels.backend.payment.table.fee') </th>
                            <th> @lang('labels.backend.payment.table.amount') </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td class="pr-0"></td>
                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        @if(!empty($result['user']->avatar))
                                        <img src="{{ asset('/storage/avatars/' . $result['user']->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ substr($result['user']->name, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="media-body">

                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-lead">{{ $result['user']->name }}</strong></p>
                                                <small class="js-lists-values-email text-50">{{ $result['user']->headline }}</small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget">
                                        <strong>{{ getCurrency(config('app.currency'))['symbol'] . number_format($result['fee'], 2) }}</strong>
                                    </small>
                                    <small class="text-50">Account Fee</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget">
                                        <strong>{{ getCurrency(config('app.currency'))['symbol'] . number_format($result['withdraw'], 2) }}</strong>
                                    </small>
                                    <small class="text-50">Total Withdraw Amount</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection