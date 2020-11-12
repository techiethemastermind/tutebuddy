@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Order Details</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Order Details
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Order Detail</div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card card-sm">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="flex">
                                <strong class="card-title">Courses</strong>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush border-top">
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

                    <div class="card-footer">
                        <div class="d-flex align-items-center">
                            <div class="flex">
                                <strong class="card-title">Total</strong>
                            </div>
                            <small class="text-70"><strong>{{ getCurrency(config('app.currency'))['symbol'] . number_format($total, 2) }}</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection