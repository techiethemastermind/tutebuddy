@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="page-section bg-primary mb-32pt">
        <div class="container page__container">
            <h2 class="text-center text-white"><span>@lang('labels.frontend.cart.cart')</span></h2>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="mb-lg-32pt">

            <div class="page-separator">
                <div class="page-separator__text">Cart Items:</div>
            </div>

            <div class="card table-responsive" data-toggle="lists"
                data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>
                <table class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">No.</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Course
                                    Name</a></th>
                            <th>Price ({{ config('app.currency') }})</th>
                            <th>Course Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="items">
                        @if(Cart::session(auth()->user()->id)->getContent()->count() > 0)
                            @foreach(Cart::session(auth()->user()->id)->getContent() as $cart)
                            <?php
                                
                                    if ($cart->attributes->product_type == 'course') {
                                        $item = App\Models\Course::find($cart->id);
                                    }

                                    if ($cart->attributes->product_type == 'bundle') {
                                        $item = App\Models\Bundle::find($cart->id);
                                    }

                                ?>
                            <tr>
                                <td class="pr-0"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-lg mr-16pt">
                                            <?php
                                                $item_img = ($cart->attributes->product_type == 'course') ? $item->course_image : $item->bundle_image;
                                            ?>
                                            @if(!empty($item_img))
                                            <img src="{{ asset('/storage/uploads/' . $item_img) }}" alt="Avatar"
                                                class="avatar-img rounded">
                                            @else
                                            <span
                                                class="avatar-title rounded bg-primary text-white">{{ substr($item->title, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <p class="card-title mb-1"><strong
                                                        class="js-lists-values-name">{{ $item->title }}</strong></p>
                                                @if($cart->attributes->type == 'bundle')
                                                <small class="js-lists-values-email text-50">Created By:
                                                    {{ $item->user->name }}</small>
                                                @else
                                                <small class="js-lists-values-email text-50">Created By:
                                                    {{ $item->teachers[0]->name }}</small>
                                                @endif
                                                @if($item->reviews->count() > 0)
                                                <div class="rating">
                                                    @include('layouts.parts.rating', ['rating' =>
                                                    $item->reviews->avg('rating')])
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="">{{ number_format($cart->price, 2) }}</h5>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-accent p-2"> {{ $cart->attributes->product_type }} </span>
                                    <span class="badge badge-pill badge-primary p-2"> {{ $cart->attributes->price_type }} </span>
                                </td>
                                <td>
                                    <a class="text-danger" href="{{route('cart.remove', ['course'=>$item])}}">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="card-title text-center">Empty Cart</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="form-group text-right">
            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">Process Checkout</a>
        </div>
    </div>

</div>

@endsection