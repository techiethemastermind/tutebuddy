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
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('search.course') }}" class="btn btn-outline-secondary">Browser</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <div class="row card-group-row">

                <div class="col-lg-6 mb-8pt mb-sm-0">
                    <div class="page-separator">
                        <div class="page-separator__text">Available Courses</div>
                    </div>
                    <a class="card border-0 mb-0" href="{{ route('search.course') }}">
                        <img src="{{ asset('assets/img/achievements/flinto.png') }}" alt="Flinto" class="card-img"
                            height="210">
                        <div class="fullbleed bg-primary" style="opacity: .5;"></div>
                        <span
                            class="card-body d-flex flex-column align-items-center justify-content-center fullbleed">
                            <span class="row flex-nowrap">
                                <span
                                    class="col-auto text-center d-flex flex-column justify-content-center align-items-center">
                                    <span
                                        class="h2 text-white text-uppercase font-weight-normal m-0 d-block">{{ $total_courses_count }}</span>
                                    <span class="h3 text-white text-uppercase font-weight-normal m-0 d-block">On
                                        Demand Classes</span>
                                </span>
                            </span>
                        </span>
                    </a>
                </div>

                <div class="col-lg-6">

                    <div class="page-separator">
                        <div class="page-separator__text">My Courses</div>
                    </div>

                    <div class="position-relative carousel-card">
                        <div class="js-mdk-carousel row d-block" id="carousel-courses">

                            <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-courses"
                                role="button" data-slide="next">
                                <span class="carousel-control-icon material-icons"
                                    aria-hidden="true">keyboard_arrow_right</span>
                                <span class="sr-only">Next</span>
                            </a>

                            <div class="mdk-carousel__content">

                                @foreach($purchased_courses as $course)

                                <div class="col-12 col-sm-6">

                                    <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                        data-partial-height="44" data-toggle="popover" data-trigger="click">

                                        <a href="{{ route('courses.show', $course->slug) }}" class="js-image"
                                            data-position="">
                                            <img src="{{ asset('storage/uploads/' . $course->course_image) }}"
                                                alt="course" height="168">
                                            <span class="overlay__content align-items-start justify-content-start">
                                                <span class="overlay__action card-body d-flex align-items-center">
                                                    <i class="material-icons mr-4pt">play_circle_outline</i>
                                                    <span class="card-title text-white">Resume</span>
                                                </span>
                                            </span>
                                        </a>

                                        <span
                                            class="corner-ribbon corner-ribbon--default-right-top corner-ribbon--shadow bg-accent text-white">
                                            NEW
                                        </span>

                                        <div class="mdk-reveal__content">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex">
                                                        <a class="card-title"
                                                            href="{{ route('courses.show', $course->slug) }}">
                                                            {{ $course->title }}
                                                        </a>
                                                        <small class="text-50 font-weight-bold mb-4pt">
                                                            {{ $course->teachers[0]->name }}
                                                        </small>
                                                    </div>
                                                    <a href="{{ route('courses.show', $course->slug) }}"
                                                        data-toggle="tooltip" data-title="Add Favorite"
                                                        data-placement="top" data-boundary="window"
                                                        class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="rating flex">
                                                        @if($course->reviews->count() > 0)
                                                        @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                                        @else
                                                            <small class="text-50">No rating received</small>
                                                        @endif
                                                    </div>
                                                    <small class="text-50">{{ $course->duration() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="popoverContainer d-none">
                                        <div class="media">
                                            <div class="media-left mr-12pt">
                                                <img src="{{ asset('storage/uploads/thumb/' . $course->course_image) }}"
                                                    width="40" height="40" alt="Angular" class="rounded">
                                            </div>
                                            <div class="media-body">
                                                <div class="card-title mb-0">{{ $course->title }}</div>
                                                <p class="lh-1 mb-0">
                                                    <span class="text-black-50 small">with</span>
                                                    <span class="text-black-50 small font-weight-bold">
                                                        {{ $course->teachers[0]->name }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                        <p class="my-16pt text-black-70">
                                            {{ $course->short_description }}
                                        </p>

                                        <div class="mb-16pt">
                                            @foreach($course->lessons as $lesson)
                                            <div class="d-flex align-items-center">
                                                <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                                <p class="flex text-black-50 lh-1 mb-0">
                                                    <small>{{ $lesson->title }}</small></p>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="my-32pt">
                                            <div class="d-flex align-items-center mb-8pt justify-content-center">
                                                <div class="d-flex align-items-center mr-8pt">
                                                    <span
                                                        class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }}</small></p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span
                                                        class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="#current-lesson-link"
                                                    class="btn btn-primary mr-8pt">Resume</a>
                                                <a href="{{ route('courses.show', $course->slug) }}"
                                                    class="btn btn-outline-secondary ml-0">Start over</a>
                                            </div>
                                        </div>

                                        @if($course->reviews->count() > 0)
                                        <div class="d-flex align-items-center">
                                            <small class="text-black-50 mr-8pt">Rating</small>
                                            <div class="rating mr-8pt">
                                                @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                            </div>
                                            <small class="text-black-50">
                                                {{ number_format((float)$course->reviews->avg('rating'), 1, '.', '') }}/5
                                            </small>
                                        </div>
                                        @endif

                                    </div>

                                </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="page-separator">
                <div class="page-separator__text">My Live Lessons</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table id="tbl_schedule" class="table mb-0 thead-border-top-0 table-nowrap">
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
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">Weekday
                                    </a>
                                </th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Course title</th>
                                <th>Lesson title</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="schedule_list"></tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-lg-16pt">
                <div class="col-lg-6 mb-8pt mb-sm-0">
                    <div class="page-separator">
                        <div class="page-separator__text">Available Instructors</div>
                    </div>
                    <a class="card border-0 mb-0" href="{{ route('search.course') }}">
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

                <div class="col-lg-6">
                    <div class="page-separator">
                        <div class="page-separator__text">Top Instructors</div>
                    </div>

                    <div class="position-relative carousel-card">
                        <div class="js-mdk-carousel row d-block" id="carousel-teachers">

                            <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-teachers"
                                role="button" data-slide="next">
                                <span class="carousel-control-icon material-icons"
                                    aria-hidden="true">keyboard_arrow_right</span>
                                <span class="sr-only">Next</span>
                            </a>

                            <div class="mdk-carousel__content">

                                @foreach($teachers as $user)

                                <div class="col-12 col-sm-6">
                                    <div class="card card-body">
                                        <div class="text-center">
                                            <p class="mb-16pt">
                                                <img src="{{ asset('/storage/avatars/' . $user->avatar) }}"
                                                    class="rounded-circle" width="64" height="64">
                                            </p>
                                            <h4 class="m-0">{{ $user->name }}</h4>
                                            <p class="lh-1">
                                                <small class="text-muted">{{ $user->about }}</small>
                                            </p>
                                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-start">
                                                <a href="fixed-teacher-profile.html" class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt">Follow</a>
                                                <a href="fixed-teacher-profile.html" class="btn btn-outline-secondary">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-separator">
                <div class="page-separator__text">All Instructors</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-name"
                data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table id="tbl_instructors" class="table mb-0 thead-border-top-0 table-nowrap">
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

                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-name">Name
                                    </a>
                                </th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="instructor_list"></tbody>
                    </table>
                </div>
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
                                <div class="card-subtitle text-50">{{ Carbon\Carbon::parse($testResult->updated_at)->diffForHumans() }}</div>
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
                                    <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i class="material-icons">more_horiz</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('lessons.show', [
                                            $testResult->test->course->slug,
                                            $testResult->test->lesson->slug,
                                            $testResult->test->step->step]) }}" class="dropdown-item">Continue</a>
                                        <a href="{{ route('test.result', $testResult->test->id) }}" class="dropdown-item">View Result</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('lessons.show', [
                                            $testResult->test->course->slug,
                                            $testResult->test->lesson->slug,
                                            $testResult->test->step->step]) }}" class="dropdown-item text-danger">Reset Quiz</a>
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
$(function() {

    var course_table = $('#tbl_schedule').DataTable({
        lengthChange: false,
        searching: false,
        ordering: false,
        info: false,
        ajax: "{{ route('admin.dashboard.table.getScheduleByAjax') }}",
        columns: [
            { data: 'index'},
            { data: 'weekday'},
            { data: 'start'},
            { data: 'end'},
            { data: 'course'},
            { data: 'lesson'},
            { data: 'action'}
        ]
    });

    var instructor_table = $('#tbl_instructors').DataTable({
        lengthChange: false,
        searching: false,
        ordering: false,
        info: false,
        ajax: "{{ route('admin.dashboard.table.getInstructorsByAjax') }}",
        columns: [
            { data: 'index'},
            { data: 'name'},
            { data: 'action'}
        ]
    });
});
</script>

@endpush