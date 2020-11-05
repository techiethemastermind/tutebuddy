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
                    <h2 class="mb-0">Sales</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            My Sales
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">My Sales</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-order js-lists-values-date">
                <table id="tbl_sales" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='10'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-order">Order</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Date</a>
                            </th>
                            <th> Customer </th>
                            <th> Total </th>
                            <th> Status </th>
                            <th> Action </th>
                            <th style="width: 24px;"></th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle">
                        <tr>
                            <td class="pr-0"></td>

                            <td>
                                <strong>10002331</strong>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-date"><strong>19/02/2019</strong></small>
                                    <small class="text-50">18 days</small>
                                </div>
                            </td>

                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">

                                        <span class="avatar-title rounded-circle">BN</span>

                                    </div>
                                    <div class="media-body">

                                        <div class="d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">Billy Nunez</strong></p>
                                            <small class="js-lists-values-email text-50">annabell.kris@yahoo.com</small>
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget"><strong>$1,200</strong></small>
                                    <small class="text-50">Invoice Sent</small>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>
                            </td>

                            <td>
                                <button class="btn btn-accent btn-sm">Detail</button>
                            </td>

                            <td class="text-right">
                                <a href="" class="text-50"><i class="material-icons">more_vert</i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(function() {
        // Code here
    });
</script>

@endpush

@endsection