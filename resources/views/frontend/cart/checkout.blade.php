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

        <div class="row mb-32pt">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header align-items-center">
                        <div class="h5 mb-0 mr-3 form-label">Order Details</div>
                    </div>
                    <div class="card-body mb-0">
                        <div class="d-flex mb-16pt">
                            <div class="flex form-label">
                                Price: ( {{ Cart::getContent()->count()}}
                                    {{(Cart::getContent()->count() > 1) ? ' '.trans('labels.frontend.cart.items') : ' '.trans('labels.frontend.cart.item')}})
                            </div>
                            <div class="flex form-label">
                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . ' ' . number_format($total, 2) }}</strong>
                            </div>
                        </div>

                        @if($taxData != null)
                            @foreach($taxData as $tax)
                            <div class="d-flex mb-16pt">
                                <div class="flex form-label mb-0">
                                    {{ $tax['name']}}:
                                </div>
                                <div class="flex form-label mb-0">
                                    {{ getCurrency(config('app.currency'))['symbol'] . ' ' . number_format($tax['amount'],2)}}
                                </div>
                            </div>
                            @endforeach
                        @endif
                        
                        <div class="d-flex">
                            <div class="flex form-label mb-0">
                                Total:
                            </div>
                            <div class="flex form-label mb-0">
                                {{ getCurrency(config('app.currency'))['symbol'] . ' ' . number_format(Cart::session(auth()->user()->id)->getTotal(), 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group text-right">
            <button id="rzp-button1" class="btn btn-primary">Pay Now</button>
        </div>
    </div>

</div>

@push('after-scripts')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    var options = {
        "key": "{{ env('RAZOR_KEY') }}",
        "name": "Tutebuddy Payment",
        "description": "Tutebuddy Payment Transaction",
        "image": "{{ asset('images/footer-bar-logo.png') }}",
        "order_id": "{{ $orderId }}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
        "prefill": {
            "name": "{{ auth()->user()->name }}",
            "email": "{{ auth()->user()->email }}",
            "contact": "{{ auth()->user()->phone_number }}"
        },
        "notes": {
            "address": "{{ auth()->user()->address }}"
        },
        "theme": {
            "color": "#3399cc"
        },
        "handler": function (response) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: "POST",
                url: "{{ route('cart.razorpay') }}",
                data: {
                    payment_id: response.razorpay_payment_id,
                    razor_order_id: response.razorpay_order_id,
                    signature: response.razorpay_signature,
                    order_id: '{{ $orderId }}',
                    amount: '{{ $total }}'
                },
                success: function(res) {
                    if(res.success) {
                        
                        swal({
                            title: "Payment successed",
                            text: "You will redirected to Dashboard",
                            type: 'success',
                            showCancelButton: true,
                            showConfirmButton: true,
                            confirmButtonText: 'Confirm',
                            cancelButtonText: 'Cancel',
                            dangerMode: false,

                        }, function(val) {
                            if (val) {
                                var redirect_url = "{{ route('admin.dashboard') }}"
                                window.location.href = redirect_url;
                            }
                        });
                    } else {
                        swal('Error!', res.message, 'error');
                    }
                }
            });
        }
    };

    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
    }
</script>

@endpush

@endsection