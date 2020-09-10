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
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>

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
                <div class="col-md-4">
                    <a class="card border-0 mb-0" href="{{ route('admin.courses.index') }}">
                        <img src="{{ asset('assets/img/achievements/flinto.png') }}" alt="Flinto" class="card-img"
                            height="210">
                        <div class="fullbleed bg-primary" style="opacity: .5;"></div>
                        <span
                            class="card-body d-flex flex-column align-items-center justify-content-center fullbleed">
                            <span class="row flex-nowrap">
                                <span
                                    class="col-auto text-center d-flex flex-column justify-content-center align-items-center">
                                    <span
                                        class="h2 text-white text-uppercase font-weight-normal m-0 d-block">{{ $courses_count }}</span>
                                    <span class="h3 text-white text-uppercase font-weight-normal m-0 d-block">Courses</span>
                                </span>
                            </span>
                        </span>
                    </a>
                </div>

                <div class="col-md-4">
                    <a class="card border-0 mb-0" href="{{ route('admin.users.index') }}">
                        <img src="{{ asset('assets/img/achievements/flinto.png') }}" alt="Flinto" class="card-img"
                            height="210">
                        <div class="fullbleed bg-accent" style="opacity: .5;"></div>
                        <span
                            class="card-body d-flex flex-column align-items-center justify-content-center fullbleed">
                            <span class="row flex-nowrap">
                                <span
                                    class="col-auto text-center d-flex flex-column justify-content-center align-items-center">
                                    <span
                                        class="h2 text-white text-uppercase font-weight-normal m-0 d-block">{{ $teachers_count }}</span>
                                    <span class="h3 text-white text-uppercase font-weight-normal m-0 d-block">Instructors</span>
                                </span>
                            </span>
                        </span>
                    </a>
                </div>

                <div class="col-md-4">
                    <a class="card border-0 mb-0" href="{{ route('courses.search') }}">
                        <img src="{{ asset('assets/img/achievements/flinto.png') }}" alt="Flinto" class="card-img"
                            height="210">
                        <div class="fullbleed bg-dark" style="opacity: .5;"></div>
                        <span
                            class="card-body d-flex flex-column align-items-center justify-content-center fullbleed">
                            <span class="row flex-nowrap">
                                <span
                                    class="col-auto text-center d-flex flex-column justify-content-center align-items-center">
                                    <span
                                        class="h2 text-white text-uppercase font-weight-normal m-0 d-block">{{ $students_count }}</span>
                                    <span class="h3 text-white text-uppercase font-weight-normal m-0 d-block">Students</span>
                                </span>
                            </span>
                        </span>
                    </a>
                </div>

            </div>

            <div class="page-separator">
                <div class="page-separator__text">Recent Orders</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table id="tbl_schedule" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#order_list" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">Ordered By
                                    </a>
                                </th>
                                <th>Amount</th>
                                <th>Time</th>
                                <th>View</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="order_list"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@endsection