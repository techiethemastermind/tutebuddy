@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Transaction Detail</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Transaction Detail
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="row">
            <div class="col-6">
                <div class="page-separator">
                    <div class="page-separator__text">Payment Detail</div>
                </div>

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Order Id: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->order_id }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Payment Id: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ $order->payment_id }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Payment Date: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M d Y h:i A') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Amount: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . $order->amount }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Payment Status: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">{{ $order->status }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-6">
                <div class="page-separator">
                    <div class="page-separator__text">Order Items</div>
                </div>

                <div class="list-group list-group-form">
                    <?php $total = 0; ?>
                    @foreach($order->items as $item)
                    <?php $total += $item->amount; ?>
                    <div class="list-group-item p-16pt">
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
                            <a href="">{{ getCurrency(config('app.currency'))['symbol'] . $item->amount }}</a>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

</div>

@endsection