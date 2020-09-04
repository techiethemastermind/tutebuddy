@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="page-section bg-primary mb-32pt">
        <div class="container page__container">
            <h2 class="text-center text-white"><span>@lang('labels.frontend.cart.checkout')</span></h2>
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
                            <th style="width: 18px;" class="pr-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                        data-target="#items" id="check_all">
                                    <label class="custom-control-label" for="check_all"><span class="text-hide">Toggle
                                            all</span></label>
                                </div>
                            </th>
                            <th style="width: 40px;">No.</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Course
                                    Name</a></th>
                            <th>Price ({{ config('app.currency') }})</th>
                            <th>Course Type</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="items">
                        @if(Cart::session(auth()->user()->id)->getContent()->count() > 0)
                            @foreach(Cart::session(auth()->user()->id)->getContent() as $cart)
                            <?php
                                
                                    if ($cart->attributes->type == 'course') {
                                        $item = App\Models\Course::find($cart->id);
                                    }

                                    // dd($cart);

                                ?>
                            <tr>
                                <td class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row" id="check_1">
                                        <label class="custom-control-label" for="check_1"><span
                                                class="text-hide">Check</span></label>
                                    </div>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-lg mr-16pt">
                                            @if(!empty($item->course_image))
                                            <img src="{{ asset('/storage/uploads/' . $item->course_image) }}" alt="Avatar"
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
                                                <small class="js-lists-values-email text-50">Created By:
                                                    {{ $item->teachers[0]->name }}</small>
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
                                <td><span class="badge badge-pill badge-primary p-2"> {{ $cart->attributes->style }} </span>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="qty" value="{{ $cart->quantity }}" min="1"
                                        style="width: 80px;">
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header align-items-center">
                        <div class="h5 mb-0 mr-3">Order Details</div>
                    </div>
                    <div class="card-body mb-0">
                        <div class="d-flex">
                            <div class="flex h5">
                                Price: ( {{ Cart::getContent()->count()}}
                                    {{(Cart::getContent()->count() > 1) ? ' '.trans('labels.frontend.cart.items') : ' '.trans('labels.frontend.cart.item')}})
                            </div>
                            <div class="flex h5">
                                <strong>{{ number_format($total, 2) . ' ' . config('app.currency') }}</strong>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="flex h5 mb-0">
                                Total:
                            </div>
                            <div class="flex h5 mb-0">
                                {{ number_format(Cart::session(auth()->user()->id)->getTotal(), 2) . ' ' . config('app.currency')}}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</div>

@endsection