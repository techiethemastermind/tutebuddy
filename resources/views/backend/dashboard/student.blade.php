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

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Live Lessons</strong></p>
                    <a href="{{ route('courses.search') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
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
                                    <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-time">Weekday
                                    </a>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Top 5 live sessions</small>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Courses</strong></p>
                    <a href="{{ route('courses.search') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
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

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>My Assignments</strong></p>
                    <a href="" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
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
                                <td>due date</td>
                                <td>{{ $assignment->total_mark }}</td>
                                <td>@include('backend.buttons.show', ['show_route' => route('admin.assignments.show', $assignment->id)])</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Assignments</small>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <span class="page-separator__text bg-white mb-0"><strong>My Instructors</strong></span>  
                    <a href="" class="btn btn-md btn-outline-accent-dodger-blue float-right">Browser All</a>
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
                                                    <small class="js-lists-values-email text-50">{{ $teacher->about }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    <a href="" target="_blank" class="btn btn-primary btn-sm">Follow</a>
                                    <a href="" target="_blank" class="btn btn-accent btn-sm">View Profile</a>
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

            <div class="page-separator">
                <div class="page-separator__text">My Paths</div>
            </div>

            <div class="row card-group-row">
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
                                                <img src="{{ asset('/storage/uploads/thumb/'. $bunlde->bundle_image) }}" width="40" height="40" alt="Angular" class="rounded">
                                                @else
                                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="Angular" class="rounded">
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

            <div class="page-separator">
                <div class="page-separator__text">My Tests</div>
            </div>

            <div class="row card-group-row">

                @foreach($testResults as $testResult)

                    <div class="card-group-row__col col-md-6">

                        <div class="card card-group-row__card card-sm">
                            <div class="card-body d-flex align-items-center">
                                <a href="{{ route('lessons.show', [
                                        $testResult->test->course->slug,
                                        $testResult->test->lesson->slug,
                                        $testResult->test->step->step]) }}"
                                    class="avatar overlay overlay--primary avatar-4by3 mr-12pt">
                                    <img src="{{ asset('/storage/uploads/thumb/' . $testResult->test->course->course_image ) }}"
                                        alt="{{ $testResult->test->title }}" class="avatar-img rounded">
                                    <span class="overlay__content"></span>
                                </a>
                                <div class="flex mr-12pt">
                                    <a class="card-title" href="{{ route('lessons.show', [
                                        $testResult->test->course->slug,
                                        $testResult->test->lesson->slug,
                                        $testResult->test->step->step]) }}">{{ $testResult->test->title }}</a>
                                    <div class="card-subtitle text-50">
                                        {{ Carbon\Carbon::parse($testResult->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="lead text-headings lh-1">{{ $testResult->test_result }}</span>
                                    <small class="text-50 text-uppercase text-headings">Score</small>
                                </div>
                            </div>

                            <!-- <div class="progress rounded-0" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 37%;"
                                aria-valuenow="37" aria-valuemin="0" aria-valuemax="100"></div>
                        </div> -->

                            <div class="card-footer">
                                <div class="d-flex align-items-center">
                                    <div class="flex mr-2">
                                        <a href="{{ route('lessons.show', [
                                        $testResult->test->course->slug,
                                        $testResult->test->lesson->slug,
                                        $testResult->test->step->step]) }}" class="btn btn-light btn-sm">

                                            <i class="material-icons icon--left">playlist_add_check</i> Reset
                                            <span class="badge badge-dark badge-notifications ml-2">5</span>

                                        </a>
                                    </div>

                                    <div class="dropdown">
                                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                                class="material-icons">more_horiz</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="{{ route('lessons.show', [
                                            $testResult->test->course->slug,
                                            $testResult->test->lesson->slug,
                                            $testResult->test->step->step]) }}" class="dropdown-item">Continue</a>
                                            <a href="{{ route('test.result', $testResult->test->id) }}"
                                                class="dropdown-item">View Result</a>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ route('lessons.show', [
                                            $testResult->test->course->slug,
                                            $testResult->test->lesson->slug,
                                            $testResult->test->step->step]) }}" class="dropdown-item text-danger">Reset
                                                Quiz</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>

            <div class="page-separator">
                <div class="page-separator__text">Discussions</div>
            </div>

            <div class="card">

                <div class="list-group list-group-flush">

                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-3 mb-8pt mb-md-0">
                                <div class="media align-items-center">
                                    <div class="media-left mr-12pt">
                                        <a href="" class="avatar avatar-sm">
                                            <span class="avatar-title rounded-circle">LB</span>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column media-body media-middle">
                                        <a href="" class="card-title">Laza Bogdan</a>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <p class="mb-8pt"><a href="fixed-discussion.html" class="text-body"><strong>Using
                                            Angular HttpClientModule instead of HttpModule</strong></a></p>


                                <a href="fixed-discussion.html" class="chip chip-outline-secondary">Angular
                                    fundamentals</a>

                            </div>
                            <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                                <h5 class="m-0">1</h5>
                                <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item p-3">
                        <div class="row align-items-start">
                            <div class="col-md-3 mb-8pt mb-md-0">
                                <div class="media align-items-center">
                                    <div class="media-left mr-12pt">
                                        <a href="" class="avatar avatar-sm">
                                            <span class="avatar-title rounded-circle">AC</span>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column media-body media-middle">
                                        <a href="" class="card-title">Adam Curtis</a>
                                        <small class="text-muted">3 days ago</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-8pt mb-md-0">
                                <p class="mb-0"><a href="fixed-discussion.html" class="text-body"><strong>Why am I
                                            getting an error when trying to install angular/http@2.4.2</strong></a></p>

                            </div>
                            <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                                <h5 class="m-0">1</h5>
                                <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer p-8pt">

                    <ul class="pagination justify-content-start pagination-xsm m-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">chevron_left</span>
                                <span>Prev</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Page 1">
                                <span>1</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Page 2">
                                <span>2</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span>Next</span>
                                <span aria-hidden="true" class="material-icons">chevron_right</span>
                            </a>
                        </li>
                    </ul>
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
        $(function () {
            //
        });
    </script>

@endpush
