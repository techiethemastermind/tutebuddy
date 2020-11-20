@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Order Detail</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Order Detail
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.orders.invoice', $order->id) }}"
                        class="btn btn-primary">Download Invoice</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    @if($order->refunded())
                    <button id="btn_refund" class="btn btn-accent" disabled>Refund Requested</button>
                    @else
                    <button id="btn_refund" class="btn btn-accent">Refund Request</button>
                    @endif
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
                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . ' ' . $order->price }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Tax: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . ' ' . $order->tax}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Total: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . ' ' . $order->amount}}</strong>
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
                    @php $total = 0; @endphp
                    @foreach($order->items as $item)
                    @php $total += $item->amount; @endphp
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
                            <a href="javascript(void:0)">{{ getCurrency(config('app.currency'))['symbol'] . $item->amount }}</a>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal for Refund Request -->
<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-0">
                    <div class="p-3">
                        <div class="form-group">
                            <h4 class="mb-0">Amount: {{ getCurrency(config('app.currency'))['symbol'] . ' ' . $order->amount}}</h4>
                        </div>
                        <div class="form-group">
                            <label for="form-label">Reason:</label>
                            <textarea id="reason" name="reason" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_confirm" class="btn btn-outline-primary btn-update">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    $(function() {
        $('#btn_refund').on('click', function(e) {
            $('#refundModal').modal('toggle');
        });

        $('#btn_confirm').on('click', function(e) {
            $.ajax({
                method: 'GET',
                url: '/dashboard/orders/refund/' + '{{ $order->id }}',
                data: {
                    reason: $('#reason').val()
                },
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        $('#refundModal').modal('toggle');
                        $('#btn_refund').text('Refund Requested');
                        $('#btn_refund').attr('disabled', 'disabled');
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection