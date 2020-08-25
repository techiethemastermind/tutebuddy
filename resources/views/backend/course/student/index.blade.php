@extends('layouts.app')

@section('content')

@push('after-styles')

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div data-push data-responsive-width="992px" class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content">

            <div class="page-section">
                <div class="container page__container">

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-24pt"
                        style="white-space: nowrap;">
                        <small class="flex text-muted text-headings text-uppercase mr-3 mb-2 mb-sm-0">Displaying 4 out
                            of 10 courses</small>
                    </div>


                    <div class="page-separator">
                        <div class="page-separator__text">Popular Courses</div>
                    </div>

                    <div class="row card-group-row">

                        @foreach($popular_courses as $course)

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal card-group-row__card"
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="{{ route('courses.show', $course->slug) }}" class="js-image" data-position="">
                                    <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course"
                                        height="140">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="{{ route('courses.show', $course->slug) }}">
                                                    {{ $course->title }}
                                                </a>
                                                <small class="text-50 font-weight-bold mb-4pt">
                                                    {{ $course->teachers[0]->name }}
                                                </small>
                                            </div>
                                            <a href="{{ route('courses.show', $course->slug) }}" data-toggle="tooltip"
                                                data-title="Remove Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
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
                                            <span
                                                class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
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
                                            <small>{{ $lesson->title }}</small>
                                        </p>
                                    </div>
                                    @endforeach
                                </div>


                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->level->name }}
                                                </small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @endforeach
                    </div>

                    <div class="mb-32pt">

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

                    <div class="page-separator">
                        <div class="page-separator__text">Featured Courses</div>
                    </div>

                    <div class="row card-group-row">

                        @foreach($featured_courses as $course)

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover" data-trigger="click">


                                <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image"
                                    data-position="" data-height="140">
                                    <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course"
                                        height="140">
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
                                            <a class="card-title" href="{{ route('courses.show', $course->slug) }}">
                                                {{ $course->title }}
                                            </a>
                                            <small class="text-50 font-weight-bold mb-4pt">
                                                {{ $course->teachers[0]->name }}
                                            </small>
                                        </div>
                                        <a href="{{ route('courses.show', $course->slug) }}" data-toggle="tooltip"
                                            data-title="Add Favorite" data-placement="top" data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span
                                                    class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
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
                                            <span
                                                class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                <div class="mb-16pt">

                                    @foreach($course->lessons as $lesson)
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>{{ $lesson->title }}</small></p>
                                    </div>
                                    @endforeach
                                </div>


                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->level->name }}</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        @endforeach

                    </div>

                    <div class="mb-32pt">

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

                    <div class="page-separator">
                        <div class="page-separator__text">Trending Courses</div>
                    </div>

                    <div class="row card-group-row">

                        @foreach($trending_courses as $course)

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover" data-trigger="click">


                                <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image"
                                    data-position="" data-height="140">
                                    <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course"
                                        height="140">
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
                                            <a class="card-title"
                                                href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                            <small
                                                class="text-50 font-weight-bold mb-4pt">{{ $course->teachers[0]->name }}</small>
                                        </div>
                                        <a href="{{ route('courses.show', $course->slug) }}" data-toggle="tooltip"
                                            data-title="Add Favorite" data-placement="top" data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span
                                                    class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
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
                                            <span
                                                class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                <div class="mb-16pt">

                                    @foreach($course->lessons as $lesson)
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>{{ $lesson->title }}</small></p>
                                    </div>
                                    @endforeach
                                </div>


                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->level->name }}</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                            class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>

                        @endforeach

                    </div>


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

        <div class="mdk-drawer js-mdk-drawer " id="library-drawer" data-align="end">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-light sidebar-right py-16pt" data-perfect-scrollbar
                    data-perfect-scrollbar-wheel-propagation="true">

                    <div class="d-flex align-items-center mb-24pt  d-lg-none">
                        <form action="index.html" class="search-form search-form--light mx-16pt pr-0 pl-16pt">
                            <input type="text" class="form-control pl-0" placeholder="Search">
                            <button class="btn" type="submit"><i class="material-icons">search</i></button>
                        </form>
                    </div>

                    <div class="sidebar-heading">Category</div>
                    <ul class="sidebar-menu">

                        @foreach($parentCategories as $category)
                        <li class="sidebar-menu-item">
                            <a href="" class="sidebar-menu-button">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                <span class="sidebar-menu-text">{{ $category->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>
$(document).ready(function() {
    //
});
</script>

@endpush


@endsection