@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="bg-white border-bottom-2 py-16pt ">
        <div class="container page__container">
            <div class="row align-items-center">
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">1</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">Select Course</div>
                        <p class="card-subtitle text-black-70">Wide selection of subjects you can learn from expert
                            tutors.</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">2</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">Find an Expert</div>
                        <p class="card-subtitle text-black-70">Select from the most experienced & requted Instructors.
                        </p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">3</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">Start Learning</div>
                        <p class="card-subtitle text-black-70">Get personal instruction on your chosen course.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row card-group-row">
                @foreach($parentCategories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded mr-12pt">
                                <div class="avatar avatar-sm mr-3">
                                    <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}">
                                        <img src="{{ asset('/storage/uploads/' . $category->thumb) }}" alt="avatar"
                                            class="avatar-img rounded">
                                    </a>
                                </div>
                            </div>
                            <div class="flex">
                                <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}" class="card-title mr-3">{{ $category->name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Learning Paths</div>
            </div>

            <div class="row card-group-row">
                @foreach($bundles as $bundle)
                <div class="col-sm-4 card-group-row__col">

                    <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card mb-lg-0"
                        data-toggle="popover" data-trigger="click">

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <div class="flex">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded mr-12pt z-0 o-hidden">
                                            <div class="overlay">
                                                @if(!empty($bundle->bundle_image))
                                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @else
                                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @endif
                                                <span class="overlay__content overlay__content-transparent">
                                                    <span class="overlay__action d-flex flex-column text-center lh-1">
                                                        <small class="h6 small text-white mb-0"
                                                            style="font-weight: 500;">{{ substr($bundle->title, 0, 2) }}</small>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex">
                                            <div class="card-title">{{ $bundle->title }}</div>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->courses->count() }} courses</small></p>
                                        </div>
                                    </div>
                                </div>

                                <a href="" data-toggle="tooltip" data-title="Add Favorite"
                                    data-placement="top" data-boundary="window"
                                    class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                            </div>

                        </div>
                    </div>

                    <div class="popoverContainer d-none">
                        <div class="media">
                            <div class="media-left mr-12pt">
                                @if(!empty($bundle->bundle_image))
                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                @else
                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="card-title">{{ $bundle->title }}</div>
                                <p class="text-black-50 d-flex lh-1 mb-0 small">{{ $bundle->courses->count() }} courses</p>
                            </div>
                        </div>

                        <p class="mt-16pt text-black-70">{{ $bundle->description }}</p>

                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                    <p class="flex text-black-50 lh-1 mb-0"><small>
                                        @if($bundle->category)
                                        {{ $bundle->category->name }}
                                        @else
                                        No Category
                                        @endif
                                    </small></p>
                                </div>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('bundles.show', $bundle->slug) }}" class="btn btn-outline-secondary">Begin</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Featured Courses</div>
            </div>

            <div class="row card-group-row">
                <div class="col-lg-12">
                    <div class="position-relative carousel-card">
                        <div class="js-mdk-carousel row d-block" id="carousel-courses">

                            <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-courses"
                                role="button" data-slide="next">
                                <span class="carousel-control-icon material-icons"
                                    aria-hidden="true">keyboard_arrow_right</span>
                                <span class="sr-only">Next</span>
                            </a>

                            <div class="mdk-carousel__content">

                                @foreach($featuredCourses as $course)

                                <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                                    <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card" data-toggle="popover" data-trigger="click">

                                        <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image" data-position="" data-height="140">
                                            <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course">
                                            <span class="overlay__content">
                                                <span class="overlay__action d-flex flex-column text-center">
                                                    <i class="material-icons icon-32pt">play_circle_outline</i>
                                                    <span class="card-title text-white">Preview</span>
                                                </span>
                                            </span>
                                        </a>

                                        <div class="card-body flex">
                                            <div class="d-flex">
                                                <div class="flex">
                                                    <a class="card-title" href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                                    <small class="text-50 font-weight-bold mb-4pt">{{ $course->teachers[0]->title }}</small>
                                                </div>
                                                <a href="{{ route('courses.show', $course->slug) }}" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                            </div>
                                            <div class="d-flex">
                                                <div class="rating flex">
                                                    @include('layouts.parts.rating', ['rating' =>
                                                    $course->reviews->avg('rating')])
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row justify-content-between">
                                                <div class="col-auto d-flex align-items-center">
                                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }}</small></p>
                                                </div>
                                                <div class="col-auto d-flex align-items-center">
                                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="popoverContainer d-none">
                                        <div class="media">
                                            <div class="media-left mr-12pt">
                                                <img src="{{ asset('storage/uploads/thumb/' . $course->course_image) }}" width="40" height="40" alt="{{ $course->title }}" class="rounded">
                                            </div>
                                            <div class="media-body">
                                                <div class="card-title mb-0">{{ $course->title }}</div>
                                                <p class="lh-1 mb-0">
                                                    <span class="text-black-50 small">with</span>
                                                    <span class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
                                                </p>
                                            </div>
                                        </div>

                                        <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                        <div class="mb-16pt">
                                            @foreach($course->lessons as $lesson)
                                            <div class="d-flex align-items-center">
                                                <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                                <p class="flex text-black-50 lh-1 mb-0">
                                                    <small>{{ $lesson->title }}</small></p>
                                            </div>
                                            @endforeach
                                        </div>


                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center mb-4pt">
                                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }} hours</small></p>
                                                </div>
                                                <div class="d-flex align-items-center mb-4pt">
                                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                                    <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                                </div>
                                            </div>
                                            <div class="col text-right">
                                                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">Enroll Now</a>
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
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row card-group-row">
                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">For Instructors</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-16.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                <p class="text-black-70">Join the most innovative e-learning platform to deliver education and training to year students.</p>
                                <p class="text-black-70">Create rich learning content or engage your students in live classrooms.</p>
                            </div>
                            <a href="/page/teach-on-tutebuddy" class="btn btn-primary mt-16pt">Start Teaching</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">For Business</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-5.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                <p class="text-black-70">The most powerful platform to deliver self-placed or live training to your employees.</p>
                                <p class="text-black-70">Keep your employees updated with the latest skills and technologies.</p>
                            </div>
                            <a href="/page/solutions-for-business" class="btn btn-primary mt-16pt">Start Training</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">For schools & institutions</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-9.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                <p class="text-black-70">From vocational to formal education, you can depend on experienced instructors for all your learning needs.</p>
                                <p class="text-black-70">Find an Instructor and start learning through self-placed or live classrooms.</p>
                            </div>
                            <a href="/page/solutions-for-institutions" class="btn btn-primary mt-16pt">Start Learning</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">Expert Teachers to Guide you</div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <img src="{{ asset('/assets/img/course-16.jpg') }}" alt="" class="card-img">
                </div>

                <div class="col-lg-6 font-size-16pt">
                    <div>
                        <p class="text-black-70">Expert teachers in a variety of subjects will make your learning
                            process easy and enjoyable. All our teachers are practicing experts or professionals in
                            their fields and bring their vast experience from the real world to your online learning
                            process.
                        </p>
                        <p class="text-black-70">Looking for a specific instructor? Search them by name or subject
                            below.</p>

                            <div class="form-group pb-16pt" style="position: relative;">
                                <div class="search-form">
                                    <input type="text" class="form-control" placeholder="Enter name or Subject" 
                                    value="@if(isset($_GET['_q'])) {{ $_GET['_q'] }} @endif" search-type="instructor">
                                    <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">Education for all</div>
            </div>

            <div class="row">
                <div class="col-lg-6 font-size-16pt">
                    
                    <div>
                        <p class="text-black-70">Private Lessons from experienced & verified teachers delivered to your home at convenient times.</p>
                        <p class="text-black-70">Classroom lessons for large groups and institutions.</p>
                        <p class="text-black-70">Personalized lessons for those who need extra attention and tailor made lessons.</p>
                        <p class="text-black-70">Learn at your own pace with your teacher.</p>
                    </div>

                    <div class="form-group">
                        <a href="" class="btn btn-accent">Check Now</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <img src="{{ asset('/assets/img/course-6.jpg') }}" alt="" class="card-img">
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="page-headline text-center">
                <h2>Feedback</h2>
                <p class="lead measure-lead mx-auto text-black-70">What other students turned professionals have to say
                    about us after learning with us and reaching their goals.</p>
            </div>

            <div class="position-relative carousel-card col-lg-8 p-0 mx-auto">
                <div class="row d-block js-mdk-carousel" id="carousel-feedback">
                    <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-feedback"
                        role="button" data-slide="next">
                        <span class="carousel-control-icon material-icons"
                            aria-hidden="true">keyboard_arrow_right</span>
                        <span class="sr-only">Next</span>
                    </a>
                    <div class="mdk-carousel__content">

                        @foreach($reviews as $review)

                        <div class="col-12 col-md-6">

                            <div class="card card-feedback card-body">
                                <blockquote class="blockquote mb-0">
                                    <p class="text-70 small mb-0">{{ $review->content }}</p>
                                </blockquote>
                            </div>
                            <div class="media ml-12pt">
                                <div class="media-left mr-12pt">
                                    <a href="fixed-student-profile.html" class="avatar avatar-sm">
                                        @if(!empty($review->user->avatar))
                                        <img src="{{asset('/storage/avatars/' . $review->user->avatar )}}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ substr($review->user->name, 0, 2) }}</span>
                                        @endif
                                    </a>
                                </div>
                                <div class="media-body media-middle">
                                    <a href="fixed-student-profile.html" class="card-title">Umberto Kass</a>
                                    <div class="rating mt-4pt">
                                        @include('layouts.parts.rating', ['rating' => $review->rating])
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
</div>
<!-- // END Header Layout Content -->

@endsection