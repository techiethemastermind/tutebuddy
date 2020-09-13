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
                    <h2 class="mb-0">Dashboard</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Dashboard
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <!-- Schedules -->
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>Upcomming Lessons</strong></p>
                    <a href="#" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time"
                    data-lists-sort-desc="true">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#schedule_list" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>
                                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-time">Weekday</a>
                                </th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Course title</th>
                                <th>Lesson title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="schedule_list">

                            @foreach($schedules as $schedule)
                                @if(!empty($schedule->lesson))
                                <tr>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input js-check-selected-row"
                                                data-domfactory-upgraded="check-selected-row">
                                            <label class="custom-control-label"><span
                                                    class="text-hide">Check</span></label>
                                        </div>
                                    </td>

                                    <td>
                                        <strong>{{ App\Models\Schedule::WEEK_DAYS[\Carbon\Carbon::parse($schedule->date)->dayOfWeek] }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>{{ $schedule->start_time }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $schedule->end_time }}</strong>
                                    </td>
                                    <td>
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-primary text-white">
                                                    {{ substr($schedule->course->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong>{{ $schedule->course->title }}</strong></small>
                                                    <small
                                                        class="js-lists-values-location text-50">{{ $schedule->course->teachers[0]->name }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-accent text-white">
                                                    {{ substr($schedule->lesson->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong>{{ $schedule->lesson->title }}</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        if($schedule->lesson->lesson_type == 1) {
                                            $route = route('lessons.live', [$schedule->lesson->slug, $schedule->lesson->id]);
                                        } else {
                                            $route = route('lessons.show', [$schedule->course->slug, $schedule->lesson->slug, 1]);
                                        }
                                        ?>
                                        <a href="{{ $route }}" target="_blank" class="btn btn-primary btn-sm">Join</a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">upcomming lessons</small>
                </div>
            </div>


            <!-- Enrolled Students -->
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>Students Roaster (Enrolled Students)</strong></p>
                    <a href="#" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#schedule_list" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>Name</th>
                                <th>Course</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row"
                                            data-domfactory-upgraded="check-selected-row">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Check</span>
                                        </label>
                                    </div>
                                </td>

                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(!empty($student->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $student->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ substr($student->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-name">{{ $student->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $student->email }}</small>
                                                </div>
                                                <div class="d-flex align-items-center ml-24pt">
                                                    <i class="material-icons text-20 icon-16pt">comment</i>
                                                    <small class="ml-4pt"><strong class="text-50">1</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong>{{ $student->studentCourse()->title }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $student->studentCourse()->start_date }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $student->studentCourse()->end_date }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">{{ $student->studentCourse()->progress() }}%</small>
                                        @if($student->studentCourse()->progress() > 99)
                                        <span class="indicator-line rounded bg-success"></span>
                                        @else
                                        <span class="indicator-line rounded bg-primary"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
$(function() {
    // 
});
</script>

@endpush