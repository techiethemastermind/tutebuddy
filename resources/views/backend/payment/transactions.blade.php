@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Transactions</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Transactions
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Payment History</div>
        </div>

        <div class="card table-responsive">
            <table id="tbl_transactions" class="table table-flush table-nowrap">
                <thead>
                    <tr>
                        <th style="width: 18px;" class="pr-0"></th>
                        <th>No.</th>
                        <th>Order Id.</th>
                        <th>Date</th>
                        <th class="text-center">Amount</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="" class="text-underline">{{ $transaction->order_id }}</a>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d Y h:i A') }}</td>
                        <td class="text-center">{{ $transaction->amount . ' (' . (getCurrency(config('app.currency'))['symbol']) . ')' }}</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="" class="btn btn-sm btn-outline-secondary mr-16pt">View Detail
                                    <i class="icon--right material-icons">keyboard_arrow_right</i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(count($transactions) < 1)
                    <tr>
                        <td colspan="6" class="text-center">No Transactions</td>
                    </tr>
                    @endif

                </tbody>
            </table>
            
            <div class="card-footer p-8pt">
                @if($transactions->hasPages())
                {{ $transactions->links('layouts.parts.page') }}
                @else
                <ul class="pagination justify-content-start pagination-xsm m-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true" class="material-icons">chevron_left</span>
                            <span>Prev</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Page 1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span>Next</span>
                            <span aria-hidden="true" class="material-icons">chevron_right</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection