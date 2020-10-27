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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>

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

            <!-- My Lessons Section -->
            @if(count($schedules) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Live Lessons</strong></p>
                    <a href="{{ route('admin.student.liveSessions') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browse All</a>
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
                                        <strong>{{ \Carbon\Carbon::parse(timezone()->convertToLocal(\Carbon\Carbon::parse($schedule->start_time)))->format('H:i:s') }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse(timezone()->convertToLocal(\Carbon\Carbon::parse($schedule->end_time)))->format('H:i:s') }}</strong>
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

                                        $result = live_schedule($schedule->lesson);
                                        ?>
                                        @if($result['status'])
                                        <a href="{{ $route }}" target="_blank" class="btn btn-primary btn-sm">Join</a>
                                        @else
                                        <button type="button" class="btn btn-md btn-outline-primary" disabled>Scheduled</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Top 5 live sessions</small>
                </div>
            </div>
            @endif

            <!-- My Courses Section -->
            @if(count($purchased_courses) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Courses</strong></p>
                    <a href="{{ route('admin.student.courses') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browse All</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#course_list" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>Title</th>
                                <th>Instructor</th>
                                <th>Category</th>
                                <td>Lessons</td>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="course_list">
                            @foreach($purchased_courses as $course)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($course->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $course->title }}</strong></small>
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
                                                    <p class="mb-0"><strong class="js-lists-values-lead">
                                                    {{ $course->teachers[0]->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">Teacher</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $course->category->name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $course->lessons->count() }}</strong>
                                </td>
                                <td>
                                    @if($course->published == 1)
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                            <span class="indicator-line rounded bg-primary"></span>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                            <span class="indicator-line rounded bg-warning"></span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug)])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Purchase courses by you</small>
                </div>
            </div>
            @endif

            <!-- My Instructors Section -->
            @if(count($teachers) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <span class="page-separator__text bg-white mb-0"><strong>My Instructors</strong></span>  
                    <a href="{{ route('admin.student.instructors') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browse All</a>
                </div>
                <div class="table-responsive" data-toggle="lists">
                    <table class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#instructor_list" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="instructor_list">
                            @foreach($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(empty($teacher->avatar))
                                            <span class="avatar-title rounded-circle">{{ substr($teacher->name, 0, 2) }}</span>
                                            @else
                                            <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $teacher->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $teacher->headline }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    <a href="" target="_blank" class="btn btn-primary btn-sm">Follow</a>
                                    <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-accent btn-sm">View Profile</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Instructors</small>
                </div>
            </div>
            @endif

            <!-- My Assignments Section -->
            @if(count($assignments) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Assignments</strong></p>
                    <a href="{{ route('admin.student.assignments') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browse All</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table id="tbl_assignment" class="table mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                            data-target="#assignment" data-domfactory-upgraded="toggle-check-all">
                                        <label class="custom-control-label">
                                            <span class="text-hide">Toggle all</span>
                                        </label>
                                    </div>
                                </th>

                                <th>Subject</th>
                                <th>Due Date</th>
                                <th>Total Mark</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="assignment">
                            @foreach($assignments as $assignment)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ substr($assignment->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $assignment->title }}</strong></small>
                                                <small class="text-70">
                                                    Course: {{ $assignment->lesson->course->title }} |
                                                    Lesson: {{ $assignment->lesson->title }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $assignment->due_date }}</strong></td>
                                <td><strong>{{ $assignment->total_mark }}</strong></td>
                                <td>@include('backend.buttons.show', ['show_route' => route('student.assignment.show', [$assignment->lesson->slug, $assignment->id])])</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Assignments</small>
                </div>
            </div>
            @endif

            <!-- My Paths -->
            @if(count($bundles) > 0)
            <div class="page-separator d-flex">
                <div class="page-separator__text">My Paths</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.student.bundles') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row mb-16pt">
                @foreach($bundles as $bundle)
                <div class="col-sm-4 card-group-row__col">
                    <div class="card card-sm stack stack--1 card-group-row__card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <div class="flex">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded mr-12pt z-0 o-hidden">
                                            <div class="overlay">
                                                <a href="{{ route('bundles.show', $bundle->slug) }}" target="_blank">
                                                @if(!empty($bundle->bundle_image))
                                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @else
                                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex">
                                            <a href="{{ route('bundles.show', $bundle->slug) }}" target="_blank">
                                                <div class="card-title">{{ $bundle->title }}</div>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->courses->count() }} courses</small></p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- My Tests -->
            @if(count($testResults) > 0)
            <div class="page-separator">
                <div class="page-separator__text">My Tests</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row mb-16pt">

                @foreach($testResults as $testResult)

                    <div class="card-group-row__col col-md-6">

                        <div class="card card-group-row__card card-sm">
                            <div class="card-body d-flex align-items-center">
                                <a href="{{ route('student.test.show', [ $testResult->test->lesson->slug, $testResult->test->id]) }}"
                                    class="avatar overlay overlay--primary avatar-4by3 mr-12pt">
                                    <img src="{{ asset('/storage/uploads/thumb/' . $testResult->test->course->course_image ) }}"
                                        alt="{{ $testResult->test->title }}" class="avatar-img rounded">
                                    <span class="overlay__content"></span>
                                </a>
                                <div class="flex mr-12pt">
                                    <a class="card-title" href="{{ route('student.test.show', [ $testResult->test->lesson->slug, $testResult->test->id]) }}">{{ $testResult->test->title }}</a>
                                    <div class="card-subtitle text-50">
                                        {{ Carbon\Carbon::parse($testResult->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="lead text-headings lh-1">{{ $testResult->test_result }}</span>
                                    <small class="text-50 text-uppercase text-headings">Score</small>
                                </div>
                            </div>

                        </div>
                    </div>

                @endforeach

            </div>
            @endif

            <!-- My Discussions Section -->
            @if(count($discussions) > 0)
            <div class="page-separator">
                <div class="page-separator__text">Discussions</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.discussions.topics') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">Browse All</a>
                    </div>
                </div>
            </div>

            <div class="card">
                @foreach($discussions as $discussion)
                <div class="list-group-item p-3">
                    <div class="row align-items-start">
                        <div class="col-md-3 mb-8pt mb-md-0">
                            <div class="media align-items-center">
                                <div class="media-left mr-12pt">
                                    <a href="" class="avatar avatar-sm">
                                        @if(!empty($discussion->user->avatar))
                                        <img src="{{ asset('/storage/avatars/' . $discussion->user->avatar) }}" alt="{{ $discussion->user->avatar }}"
                                        class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ substr($discussion->user->name, 0, 2) }}</span>
                                        @endif
                                    </a>
                                </div>
                                <div class="d-flex flex-column media-body media-middle">
                                    <a href="" class="card-title">{{ $discussion->user->name }}</a>
                                    <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->updated_at))->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-8pt mb-md-0">
                            <p class="mb-8pt">
                                <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="text-body">
                                    <strong>{{ $discussion->title }}</strong></a>
                            </p>

                            <?php $topics = json_decode($discussion->topics); ?>
                            @foreach($topics as $topic)
                            <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="chip chip-outline-secondary">
                                {{ $discussion->topic($topic) }}
                            </a>
                            @endforeach
                        </div>
                        <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                            <h5 class="m-0">{{ $discussion->results->count() }}</h5>
                            <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

    <script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

    <script>
        $(function () {
            //
        });
    </script>

@endpush

@endsection
