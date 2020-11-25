@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Dashboard</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">

                            Dashboard

                        </li>

                    </ol>

                </div>
            </div>
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="" class="btn btn-outline-secondary">New Report</a>
                </div>
            </div>

        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <div class="row row mb-32pt">
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $teachers_count }}</h4>
                            <div><label class="form-label">Instructors</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $students_count }}</h4>
                            <div><label class="form-label">Students</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $active_courses }}</h4>
                            <div><label class="form-label">Active Courses</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-dark text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $enrolled_courses }}</h4>
                            <div><label class="form-label">Enrolled Courses</label></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row mb-32pt">
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ getCurrency(config('app.currency'))['symbol'] . number_format($total_sales, 2) }}</h4>
                            <div><label class="form-label">Total Sales</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ getCurrency(config('app.currency'))['symbol'] . number_format($total_payments, 2) }}</h4>
                            <div><label class="form-label">Total Payments</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $course_approval }}</h4>
                            <div><label class="form-label">Courses Approval</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card border-1 border-left-3 border-left-dark text-center mb-lg-0">
                        <div class="card-body">
                            <h4 class="h2 mb-0">{{ $live_lessons }}</h4>
                            <div><label class="form-label">Live Lessons</label></div>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($pending_courses) > 0)

            <div class="page-separator">
                <div class="page-separator__text">Courses Pending Approval</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>No.</th>
                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">Title
                                    </a>
                                </th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="list">
                            @foreach($pending_courses as $course)
                            <tr>
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">{{ substr($course->title, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $course->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $course->slug }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ substr($course->teachers[0]->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $course->teachers[0]->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $course->teachers[0]->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $course->category->name }}</td>
                                <td>
                                    @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug), 'tooltip' => false])
                                    <a href="{{ route('admin.courses.publish', $course->id) }}" class="btn btn-success btn-sm" data-action="publish">
                                        <i class="material-icons">arrow_upward</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($withdraw_requests) > 0)
            <div class="page-separator">
                <div class="page-separator__text">Withdraw Requests</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.transactions') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>No.</th>
                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">Transaction ID
                                    </a>
                                </th>
                                <th>Instructor</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($withdraw_requests as $item)
                            <tr>
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $item->transaction_id }}</strong></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ substr($item->user->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $item->user->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $item->user->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $item->currency . $item->amount }}</strong></td>
                                <td><a href="javascript:void(0)" class="btn btn-accent btn-sm">Detail</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($orders) > 0)
            <div class="page-separator">
                <div class="page-separator__text">Orders</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.orders') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>No.</th>
                                <th>
                                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-order">Order</a>
                                </th>
                                <th>
                                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Date</a>
                                </th>
                                <th> Customer </th>
                                <th> Total </th>
                                <th> Action </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="pr-0"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $order->uuid }}</strong>
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                    <small class="js-lists-values-date"><strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M d Y') }}</strong></small>
                                        <small class="text-50">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</small>
                                    </div>
                                </td>

                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">

                                            <span class="avatar-title rounded-circle">{{ substr($order->user->name, 0, 2) }}</span>

                                        </div>
                                        <div class="media-body">

                                            <div class="d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-name">{{ $order->user->name }}</strong></p>
                                                <small class="js-lists-values-email text-50">{{ $order->user->email }}</small>
                                            </div>

                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-budget"><strong>{{ getCurrency(config('app.currency'))['symbol'] . $order->price }}</strong></small>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-accent btn-sm">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="page-separator">
                <div class="page-separator__text">Daily Signups</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-body">
                    <canvas id="dailysignChart" class="chart-canvas"></canvas>
                </div>
            </div>

            <div class="page-separator">
                <div class="page-separator__text">Daily Orders</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-body">
                    <canvas id="dailyOrderChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<!-- Moment.js -->
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/moment-range.min.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>

<script>

$(function() {

    var sign_days = JSON.parse('{{ $json_sign_days }}');
    var sign_instructors = JSON.parse('{{ $json_sign_instructors }}');
    var sign_students = JSON.parse('{{ $json_sign_students }}');
    var daily_orders = JSON.parse('{{ $json_daily_orders }}');

    var sign_data = {
        labels: sign_days,
        datasets: [
            { 
                data: sign_instructors,
                label: "Students",
                borderColor: "#005EA6",
                fill: false
            }, { 
                data: sign_students,
                label: "Instructors",
                borderColor: "#8e5ea2",
                fill: false
            }
        ]
    }

    var sign_chart = new Chart($('#dailysignChart'), {
        type: 'line',
        data: sign_data,
        options: {
            title: {
                display: true,
                text: 'Daily Signups'
            }
        }
    });

    var order_data = {
        labels: sign_days,
        datasets: [
            {
                data: daily_orders,
                label: "Sales ({{ config('app.currency') }})",
                borderColor: 'red',
                fill: false
            }
        ]
    }

    var order_chart = new Chart($('#dailyOrderChart'), {
        type: 'line',
        data: order_data,
        options: {
            title: {
                display: true,
                text: "Daily Orders ({{ config('app.currency') }})"
            }
        }
    });
});

</script>
@endpush

@endsection