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
            <div class="page-separator__text">Outstanding Payments</div>
        </div>

        <div class="alert alert-soft-warning mb-lg-32pt">
            <div class="d-flex flex-wrap align-items-center">
                <div class="mr-8pt">
                    <i class="material-icons">access_time</i>
                </div>
                <div class="flex" style="min-width: 180px">
                    <small class="text-100">
                        Please pay your amount due of
                        <strong>$9.00</strong> for invoice <a href="fixed-billing-invoice.html"
                            class="text-underline">10002331</a>
                    </small>
                </div>
                <a href="fixed-billing-payment.html" class="btn btn-sm btn-link">Pay Now</a>
            </div>
        </div>

        <div class="page-separator">
            <div class="page-separator__text">Payment History</div>
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
                        <td><a href="fixed-billing-invoice.html" class="text-underline">10002331</a>
                        </td>
                        <td>26 Sep 2018</td>
                        <td class="text-center">$9</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i
                                        class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary">Download <i
                                        class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><a href="fixed-billing-invoice.html" class="text-underline">10003815</a>
                        </td>
                        <td>29 Apr 2018</td>
                        <td class="text-center">$9</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i
                                        class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary">Download <i
                                        class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><a href="fixed-billing-invoice.html" class="text-underline">10007382</a>
                        </td>
                        <td>31 Mar 2018</td>
                        <td class="text-center">$9</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i
                                        class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary">Download <i
                                        class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><a href="fixed-billing-invoice.html" class="text-underline">10004876</a>
                        </td>
                        <td>30 May 2018</td>
                        <td class="text-center">$9</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i
                                        class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary">Download <i
                                        class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><a href="fixed-billing-invoice.html" class="text-underline">10009392</a>
                        </td>
                        <td>30 Apr 2018</td>
                        <td class="text-center">$9</td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary mr-16pt">View invoice <i
                                        class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="fixed-billing-invoice.html"
                                    class="btn btn-sm btn-outline-secondary">Download <i
                                        class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>

    </div>

@endsection