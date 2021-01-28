@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
[dir=ltr] .dv-sticky {
    z-index: 0;
    position: relative;
    position: -webkit-sticky;
    position: sticky;
    top: 4rem;
    display: block;
}

[dir=ltr] .review-stars-item .rating label input {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}

[dir=ltr] .review-stars-item .rating__item {
    color: rgb(39 44 51 / 0.2);
}

[dir=ltr] .review-stars-item .rating label {
    display: inherit;
}

[dir=ltr] .rating label {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    cursor: pointer;
}

[dir=ltr] .rating label:nth-child(1) {
    z-index: 4;
}

[dir=ltr] .rating label:nth-child(2) {
    z-index: 3;
}

[dir=ltr] .rating label:nth-child(3) {
    z-index: 2;
}

[dir=ltr] .rating label:nth-child(4) {
    z-index: 1;
}

[dir=ltr] .rating label:last-child {
    position: static;
}

[dir=ltr] .rating:hover label:hover input~.rating__item {
    color: #f9c32c;
}

[dir=ltr] .rating:not(:hover) label input:checked~.rating__item {
    color: #ffc926;
}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
    background-color: #0085eb;
    color: white;
    padding: 12px;
    border: none;
    opacity: 0.8;
    position: fixed;
    bottom: 23px;
    right: 28px;
    border-radius: 100% !important;
    z-index: 99;
}

/* The popup chat - hidden by default */
.chat-popup {
    display: none;
    position: fixed;
    bottom: 15px;
    right: 15px;
    z-index: 100;
    box-shadow: 0px 0 2px 0px black;
    border-radius: 5px;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
  border-radius: 5px;
}

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 15px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 100px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}

#messages_content ul {
    background: #f1f1f1;
}

</style>

@endpush

<div class="mdk-header-layout__content page-content">
    @if(auth()->check() && (auth()->user()->hasRole('Instructor') || auth()->user()->hasRole('Administrator')))
    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    <a href="{{ route('admin.courses.index') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                </div>
            </nav>
            
            <nav class="nav navbar-nav ml-sm-auto align-items-center align-items-sm-end d-none d-lg-flex">
                @if(auth()->user()->hasRole('Administrator'))
                <div class="">
                    @if($course->published == 2)
                    <a href="{{ route('admin.courses.publish', $course->id) }}" id="btn_publish" class="btn btn-primary">
                        @lang('labels.frontend.buttons.publish')
                    </a>
                    @endif

                    @if($course->published == 1)
                    <a href="{{ route('admin.courses.publish', $course->id) }}" id="btn_publish" class="btn btn-info">
                        @lang('labels.frontend.buttons.unpublish')
                    </a>
                    @endif
                </div>
                @endif

                @if(auth()->user()->hasRole('Instructor') && $is_mine)
                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-accent">@lang('labels.frontend.buttons.edit')</a>
                @endif
            </nav>
        </div>
    </div>
    @endif

    <div class="mdk-box bg-primary mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="hero py-64pt text-center text-sm-left">
                <div class="container page__container">
                    <h1 class="text-white">{{ $course->title }}</h1>
                    <p class="lead text-white-50 measure-hero-lead mb-24pt">{{ $course->short_description }}</p>

                    @if(auth()->check() && auth()->user()->hasRole('Student'))
                    @if($course->favorited())
                    <button data-route="{{ route('admin.course.addFavorite', $course->id) }}" disabled class="btn btn-white mr-12pt"><i
                            class="material-icons icon--left">favorite_border</i>
                        @lang('labels.frontend.buttons.add_favorite')
                    </button>
                    @else
                    <button data-route="{{ route('admin.course.addFavorite', $course->id) }}" id="btn_add_favorite" class="btn btn-outline-white mr-12pt"><i
                            class="material-icons icon--left">favorite_border</i> @lang('labels.frontend.buttons.add_favorite')</button>
                    @endif
                    <a href="javascript:void(0)" id="btn_add_share" class="btn btn-outline-white mr-12pt"><i class="material-icons icon--left">share</i>
                        @lang('labels.frontend.buttons.share')</a>
                    @endif

                    @if($course->progress() == 100)
                        @if(!$course->isUserCertified())
                        <form method="post" action="{{route('admin.certificates.generate')}}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" value="{{$course->id}}" name="course_id">
                            <button class="btn btn-outline-white" id="finish">
                                <i class="material-icons icon--left">done</i>
                                @lang('labels.frontend.course.finish_course')
                            </button>
                        </form>
                        @else
                        <button disabled="disabled" class="btn btn-white">
                            <i class="material-icons icon--left">done</i> @lang('labels.frontend.course.certified')
                        </button>
                        @endif
                    @endif

                </div>
            </div>
            <div
                class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                                    @if(empty($course->teachers[0]->avatar))
                                    <span
                                        class="avatar-title rounded-circle">{{ substr($course->teachers[0]->name, 0, 2) }}</span>
                                    @else
                                    <img src="{{ asset('/storage/avatars/' . $course->teachers[0]->avatar) }}"
                                        alt="{{ $course->teachers[0]->name }}" class="avatar-img rounded-circle">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <a class="card-title m-0"
                                        href="{{ route('profile.show', $course->teachers[0]->uuid) }}">{{ $course->teachers[0]->name }}</a>
                                    <p class="text-50 lh-1 mb-0">@lang('labels.frontend.course.finish_course')</p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            {{ $course->duration() }}
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">timeline</i>
                            {{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }} ~ {{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }}
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">assessment</i>
                            {{ $course->level->name }}
                        </li>
                        <li class="nav-item ml-sm-auto text-sm-center flex-column navbar-list__item">
                            <div class="rating rating-24">
                                @include('layouts.parts.rating', ['rating' => $course_rating])
                            </div>
                            <p class="lh-1 mb-0"><small class="text-muted">{{ $total_ratings }} ratings</small></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->check() && $course->isEnrolled() || $is_mine)

    <div class="container page__container">
        <div class="row">
            <div class="col-lg-7">
                <div class="border-left-2 page-section pl-32pt">

                    @if(isset($course->mediaVideo))
                    <div class="mb-32pt">
                        <div class="bg-primary embed-responsive embed-responsive-16by9" data-domfactory-upgraded="player">
                            <div class="player embed-responsive-item">
                                <div class="player__content">
                                    <div class="player__image"
                                        style="--player-image: url({{ asset('storage/uploads/' . $course->course_image) }})">
                                    </div>
                                    <a href="" class="player__play bg-primary">
                                        <span class="material-icons">play_arrow</span>
                                    </a>
                                </div>
                                <div class="player__embed d-none">
                                    <?php
                                        $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                        $embed->setAttribute([
                                            'id'=>'display_course_video',
                                            'class'=>'embed-responsive-item',
                                            'allowfullscreen' => true
                                        ]);

                                        echo $embed->getHtml();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                    @foreach($course->lessons as $lesson)

                    <div class="d-flex align-items-center page-num-container" id="sec-{{ $lesson->id }}">
                        <div class="page-num">{{ $loop->iteration }}</div>

                        @if($lesson->lesson_type == 0)
                        
                            @if($lesson->steps->count() > 0)
                            <a href="{{ route('lessons.show', [$course->slug, $lesson->slug, $lesson->steps[0]->step]) }}">
                                <h4>{{ $lesson->title }}
                                    @if($lesson->isCompleted())
                                    <span class="badge badge-dark badge-notifications ml-2 p-1">
                                        <i class="material-icons m-0">check</i>
                                    </span>
                                    @endif
                                </h4>
                            </a>
                            @else
                                <h4>{{ $lesson->title }}
                                    @if($lesson->isCompleted())
                                    <span class="badge badge-dark badge-notifications ml-2 p-1">
                                        <i class="material-icons m-0">check</i>
                                    </span>
                                    @endif
                                </h4>
                            @endif

                        @else
                            <a href="{{ route('lessons.live', [$lesson->slug, $lesson->id]) }}">
                                <h4>{{ $lesson->title }}
                                    @if($lesson->isCompleted())
                                    <span class="badge badge-dark badge-notifications ml-2 p-1">
                                        <i class="material-icons m-0">check</i>
                                    </span>
                                    @endif
                                </h4>
                            </a>
                        @endif
                    </div>

                    <p class="text-70 mb-24pt">{{ $lesson->short_text }}</p>

                    @if($lesson->lesson_type == 1)

                    <?php
                        $schedule = $lesson->schedule;
                    ?>
                    @if($schedule)
                    <p class="text-70 mb-24pt">
                        <span class="mr-20pt">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            Start: {{ $schedule->start_time }}
                        </span>

                        <span>
                            <i class="material-icons text-muted icon--left">schedule</i>
                            End: {{ $schedule->end_time }}
                        </span>
                    </p>

                    <div class="mb-32pt">
                        <a href="{{ route('lessons.live', [$lesson->slug, $lesson->id]) }}" target="_blank"
                            data-lesson-id="" class="btn btn-outline-accent-dodger-blue btn-block btn-live-session">Join
                            To Live Session</a>
                    </div>
                    @endif

                    @else

                    <div class="mb-32pt">
                        <ul class="accordion accordion--boxed js-accordion mb-0" id="toc-{{ $lesson->id }}">
                            <li class="accordion__item @if($loop->iteration == 1) open @endif">
                                <a class="accordion__toggle" data-toggle="collapse" data-parent="#toc-{{ $lesson->id }}"
                                    href="#toc-content-{{ $lesson->id }}">
                                    <span class="flex">{{ $lesson->steps->count() }} @lang('labels.frontend.course.steps')</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu">
                                    <ul class="list-unstyled collapse @if($loop->iteration == 1) show @endif"
                                        id="toc-content-{{ $lesson->id }}">

                                        @foreach( $lesson->steps as $step )

                                        <li class="accordion__menu-link">
                                            <span
                                                class="material-icons icon-16pt icon--left text-body">{{ config('stepicons')[$step['type']] }}</span>
                                            <a class="flex"
                                                href="{{ route('lessons.show', [$course->slug, $lesson->slug, $step->step]) }}">
                                                Step {{ $step['step'] }} : <span>{{ $step['title'] }}</span>
                                            </a>
                                            @if($step['duration'])
                                            <span class="text-muted">
                                                {{ $step['duration'] }} @lang('labels.frontend.general.min')
                                            </span>
                                            @else
                                            <span class="material-icons icon-16pt icon--left text-body text-muted">
                                                alarm
                                            </span>
                                            @endif
                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    @endif

                    @endforeach
                </div>
            </div>

            <div class="page-section col-lg-5 border-left-2 dv-sticky">
                <div class="container page__container">
                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.about_course')</div>
                        </div>
                        <div class="course-description">{!! $course->description !!}</div>
                    </div>

                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.what_you_learn')</div>
                        </div>
                        <ul class="list-unstyled">
                            @foreach($course->lessons as $lesson)
                            <li class="d-flex align-items-center">
                                <span class="material-icons text-50 mr-8pt">check</span>
                                <span class="text-70">{{ $lesson->title }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.about_teacher')</div>
                        </div>

                        @foreach($course->teachers as $teacher)

                        <div class="pt-sm-32pt pt-md-0 d-flex flex-column">
                            <div class="avatar avatar-xl avatar-online mb-lg-16pt">
                                @if(empty($teacher->avatar))
                                <span class="avatar-title rounded-circle">{{ substr($teacher->name, 0, 2) }}</span>
                                @else
                                <img src="{{ asset('/storage/avatars/'. $teacher->avatar) }}" alt="{{ $teacher->name }}"
                                    class="avatar-img rounded-circle">
                                @endif
                            </div>
                            <h4 class="m-0">{{ $teacher->name }}</h4>
                            <p class="lh-1">
                                <small class="text-muted">{{ $teacher->headline }}t</small>
                            </p>
                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-start">
                                @if($is_mine)
                                <button class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt" disabled> @lang('labels.frontend.buttons.follow')</button>
                                <button class="btn btn-outline-secondary" disabled>@lang('labels.frontend.buttons.view_profile')</button>
                                @else
                                <a href="{{ route('profile.show', $teacher->uuid) }}"
                                    class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt">@lang('labels.frontend.buttons.follow')</a>
                                <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-outline-secondary">@lang('labels.frontend.buttons.view_profile')</a>
                                @endif
                            </div>
                        </div>

                        @endforeach
                    </div>

                    @if(auth()->check() && auth()->user()->child()->count() > 0)
                        <div class="page-separator mt-4">
                            <div class="page-separator__text">Purchase for Child</div>
                        </div>

                        <form action="{{ route('cart.process') }}" method="POST" id="frm_checkout">@csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="price_type" value="group">
                            <input type="hidden" name="child" value="">
                            <button type="button" id="btn_checkout" class="btn btn-primary btn-block mb-8pt" 
                                data-action="checkout">Buy Now</button>
                        </form>

                        <form action="{{ route('cart.addToCart') }}" method="POST" id="frm_cart">@csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="price_type" value="group">
                            <input type="hidden" name="child" value="">
                            <button type="button" id="btn_addtocart" class="btn btn-accent btn-block mb-8pt" 
                                data-action="cart"> Add To Cart</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @else

    <div class="page-section bg-alt border-bottom-2">

        <div class="container page__container">
            <div class="row ">
                <div class="col-md-7">
                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.frontend.course.about_course')</div>
                    </div>
                    <div class="course-description">{!! $course->description !!}</div>
                </div>
                <div class="col-md-5">
                    <div class="page-separator">
                        <div class="page-separator__text bg-alt">@lang('labels.frontend.course.what_you_learn')</div>
                    </div>
                    <ul class="list-unstyled">
                        @foreach($course->lessons as $lesson)
                        <li class="d-flex align-items-center">
                            <span class="material-icons text-50 mr-8pt">check</span>
                            <span class="text-70">{{ $lesson->title }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.course.table_contents')</div>
            </div>

            <div class="row">
                <div class="col-lg-7">

                    @if(isset($course->mediaVideo))

                    <div class="mb-32pt">
                        <div class="bg-primary embed-responsive embed-responsive-16by9" data-domfactory-upgraded="player">
                            <div class="player embed-responsive-item">
                                <div class="player__content">
                                    <div class="player__image"
                                        style="--player-image: url({{ asset('storage/uploads/' . $course->course_image) }})">
                                    </div>
                                    <a href="" class="player__play bg-primary">
                                        <span class="material-icons">play_arrow</span>
                                    </a>
                                </div>
                                <div class="player__embed d-none">
                                    <?php
                                        $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                        $embed->setAttribute([
                                            'id'=>'display_course_video',
                                            'class'=>'embed-responsive-item',
                                            'allowfullscreen' => true
                                        ]);

                                        echo $embed->getHtml();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                    @foreach($course->lessons as $lesson)
                    <div class="mb-32pt">
                        <ul class="accordion accordion--boxed js-accordion mb-0" id="toc-{{ $lesson->id }}">
                            <li class="accordion__item @if($loop->iteration == 1) open @endif">
                                <a class="accordion__toggle" data-toggle="collapse" data-parent="#toc-{{ $lesson->id }}"
                                    href="#toc-content-{{ $lesson->id }}">
                                    <span class="flex">{{$lesson->position}} - {{ $lesson->title }}
                                        <small>({{ $lesson->steps->count() }} @lang('labels.frontend.course.steps'))</small></span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu">
                                    <ul class="list-unstyled collapse @if($loop->iteration == 1) show @endif"
                                        id="toc-content-{{ $lesson->id }}">

                                        @foreach( $lesson->steps as $step )

                                        <li class="accordion__menu-link">
                                            <span class="material-icons icon-16pt icon--left text-body">lock</span>
                                            <a class="flex" href="javascript:void(0)">
                                                Step {{ $step['step'] }} : <span>{{ $step['title'] }}</span>
                                            </a>
                                            @if($step['duration'])
                                            <span class="text-muted">
                                                {{ $step['duration'] }} min
                                            </span>
                                            @else
                                            <span class="material-icons icon-16pt icon--left text-body text-muted">
                                                alarm
                                            </span>
                                            @endif
                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
                
                <div class="col-lg-5 justify-content-center">

                    <div class="card">
                        <div class="card-body py-16pt">

                            <div class="text-center">
                                <span
                                    class="icon-holder icon-holder--outline-secondary rounded-circle d-inline-flex mb-8pt">
                                    <i class="material-icons">timer</i>
                                </span>
                                <h4 class="card-title"><strong>Unlock Course</strong></h4>
                                <p class="card-subtitle text-70 mb-24pt">Get access to all videos in the Course</p>

                                @if(!auth()->check())
                                <a href="{{ route('register') }}" class="btn btn-accent mb-8pt">Sign up to unlock</a>
                                <p class="mb-0">Have an account? <a href="{{ route('login') }}">Login</a></p>
                                @endif

                            </div>

                            @if(auth()->check() && auth()->user()->hasRole('Student'))
                            <div class="pl-5 pr-5">

                                <div class="form-group mb-32pt">
                                    <div class="custom-controls-stacked">

                                        @if(!empty($course->private_price))
                                        <div class="custom-control custom-radio mb-16pt">
                                            <input id="enroll_private" name="enroll_type" type="radio" enroll-type="private"
                                                data-amount="{{ $course->private_price }}" class="custom-control-input" checked="">
                                            <label for="enroll_private" class="card-title custom-control-label">
                                                Private: {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }}
                                            </label>
                                        </div>
                                        @endif

                                        @if(!empty($course->group_price))
                                        <div class="custom-control custom-radio">
                                            <input id="enroll_group" name="enroll_type" type="radio" enroll-type="group"
                                                data-amount="{{ $course->group_price }}" class="custom-control-input" checked="">
                                            <label for="enroll_group" class="card-title custom-control-label">
                                                Group: {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }}
                                            </label>
                                        </div>
                                        @endif

                                    </div>
                                </div>

                                <form action="{{ route('cart.process') }}" method="POST">@csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <input type="hidden" name="price_type" value="group">
                                    <input type="hidden" name="child" value="">
                                    <button class="btn btn-primary btn-block mb-8pt" data-action="checkout">Buy Now</button>
                                </form>

                                <form action="{{ route('cart.addToCart') }}" method="POST">@csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <input type="hidden" name="price_type" value="group">
                                    <input type="hidden" name="child" value="">
                                    <button class="btn btn-accent btn-block mb-8pt" data-action="cart"> Add To Cart</button>
                                </form>

                                @if(auth()->check() && auth()->user()->child()->count() > 0)
                                    <div class="page-separator mt-4">
                                        <div class="page-separator__text bg-alt">Purchase for Child</div>
                                    </div>

                                    <form action="{{ route('cart.process') }}" method="POST" id="frm_checkout">@csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="price_type" value="group">
                                        <input type="hidden" name="child" value="">
                                        <button type="button" id="btn_checkout" class="btn btn-primary btn-block mb-8pt" 
                                            data-action="checkout">Buy Now</button>
                                    </form>

                                    <form action="{{ route('cart.addToCart') }}" method="POST" id="frm_cart">@csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="price_type" value="group">
                                        <input type="hidden" name="child" value="">
                                        <button type="button" id="btn_addtocart" class="btn btn-accent btn-block mb-8pt" 
                                            data-action="cart"> Add To Cart</button>
                                    </form>
                                @endif

                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endif

    <div class="page-section bg-alt border-top-2 border-bottom-2">

        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Student Feedback</div>
            </div>
            <div class="row mb-32pt">
                <div class="col-md-3 mb-32pt mb-md-0">
                    <div class="display-1">{{ number_format($course_rating, 1) }}</div>
                    <div class="rating rating-24">
                        @include('layouts.parts.rating', ['rating' => $course_rating])
                    </div>
                    <p class="text-muted mb-0">{{ $total_ratings }} ratings</p>
                </div>
                <div class="col-md-9">

                    <?php
                        
                        if($total_ratings > 0) {
                            $ratings_5 = $course->reviews()->where('rating', '=', 5)->get()->count();
                            $percent_5 = number_format(($ratings_5 / $total_ratings) * 100, 1);
                            $ratings_4 = $course->reviews()->where('rating', '=', 4)->get()->count();
                            $percent_4 = number_format(($ratings_4 / $total_ratings) * 100, 1);
                            $ratings_3 = $course->reviews()->where('rating', '=', 3)->get()->count();
                            $percent_3 = number_format(($ratings_3 / $total_ratings) * 100, 1);
                            $ratings_2 = $course->reviews()->where('rating', '=', 2)->get()->count();
                            $percent_2 = number_format(($ratings_2 / $total_ratings) * 100, 1);
                            $ratings_1 = $course->reviews()->where('rating', '=', 1)->get()->count();
                            $percent_1 = number_format(($ratings_1 / $total_ratings) * 100, 1);
                        } else {
                            $ratings_5 = 0;
                            $percent_5 = 0;
                            $ratings_4 = 0;
                            $percent_4 = 0;
                            $ratings_3 = 0;
                            $percent_3 = 0;
                            $ratings_2 = 0;
                            $percent_2 = 0;
                            $ratings_1 = 0;
                            $percent_1 = 0;
                        }
                        
                    ?>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_5 }}% rated 5/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_5 }}" style="width: {{ $percent_5 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_5 }} ratings</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_4 }}% rated 4/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_4 }}" style="width: {{ $percent_4 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_4 }} ratings</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_3 }}% rated 3/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_3 }}" style="width: {{ $percent_3 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_3 }} ratings</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_2 }}% rated 2/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_2 }}" style="width: {{ $percent_2 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_2 }} ratings</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_1 }}% rated 0/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_1 }}" aria-valuemin="{{ $percent_1 }}"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_1 }} ratings</span>
                        </div>
                    </div>

                </div>
            </div>

            @foreach($course->reviews as $review)
            <div class="pb-16pt mb-16pt border-bottom row">
                <div class="col-md-3 mb-16pt mb-md-0">
                    <div class="d-flex">
                        <a href="{{ route('profile.show', $review->user->uuid) }}" class="avatar avatar-sm mr-12pt">
                            @if(!empty($review->user->avatar))
                            <img src="{{ asset('storage/avatars/' . $review->user->avatar ) }}" alt="avatar"
                                class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ substr($review->user->name, 0, 2) }}</span>
                            @endif
                        </a>
                        <div class="flex">
                            <p class="small text-muted m-0">{{ $review->created_at->diffforhumans() }}</p>
                            <a href="{{ route('profile.show', $review->user->uuid) }}" class="card-title">{{ $review->user->name }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rating mb-8pt">
                        @include('layouts.parts.rating', ['rating' => $review->rating])
                    </div>
                    <p class="text-70 mb-0">{{ $review->content }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @if(auth()->check() && auth()->user()->hasRole('Student'))
    <div id="review_section"
        class="page-section border-bottom-2 bg-alt @if($course->isReviewed() == true) d-none @endif">

        <div class="container page__container">
            <!-- Add Reviews -->
            <div class="page-separator">
                <div class="page-separator__text">Provide your review</div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="review-stars-item form-inline form-group">
                        <span class="form-label">Your Rating: </span>
                        <div class="rating rating-24 position-relative">
                            <label>
                                <input type="radio" name="stars" value="1">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="2">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="3">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="4">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="5">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    @php
                    if(isset($review) && $course->isReviewed()) {
                    $review_route = route('courses.review.update', ['id'=>$review->id]);
                    } else {
                    $review_route = route('courses.review', ['id'=>$course->id]);
                    }
                    @endphp
                    <form method="POST" action="{{ $review_route }}" id="frm_review">@csrf
                        <input type="hidden" name="rating" id="rating" value="0">
                        <label for="review" class="form-label">Message:</label>
                        <textarea name="review" class="form-control bg-light mb-3" id="review" rows="5"
                            cols="20"></textarea>
                        <button type="submit" class="btn btn-primary" value="Submit">Add review Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- // END Header Layout Content -->

<input type="hidden" id="course_description" value="{{ $course->description }}">
<div id="course_editor" style="display:none;"></div>

@if(auth()->check())

<!-- Enroll Chat -->
<button id="btn_enroll_start" class="open-button">
    <span class="material-icons icon-32pt">chat</span>
</button>

<div class="chat-popup" id="dv_enroll_chat">
    <form method="POST" action="{{ route('admin.messages.sendEnrollChat') }}" class="form-container">@csrf
        <div class="media align-items-center mt-8pt mb-16pt">
            <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                @if(empty($course->teachers[0]->avatar))
                <span
                    class="avatar-title rounded-circle">{{ substr($course->teachers[0]->name, 0, 2) }}</span>
                @else
                <img src="{{ asset('/storage/avatars/' . $course->teachers[0]->avatar) }}"
                    alt="{{ $course->teachers[0]->name }}" class="avatar-img rounded-circle">
                @endif
            </div>
            <div class="media-body">
                <a class="card-title m-0"
                    href="{{ route('profile.show', $course->teachers[0]->uuid) }}">{{ $course->teachers[0]->name }}</a>
                <p class="text-50 lh-1 mb-0">{{ $course->teachers[0]->headline }}</p>
            </div>
        </div>
        <div id="messages_content"></div>
        <textarea placeholder="Type message.." name="message" required></textarea>
        <input type="hidden" name="user_id" value="{{ $course->teachers[0]->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="thread_id" value="">
        <button type="submit" class="btn btn-primary btn-block">Send</button>
        <button type="button" id="btn_enroll_end" class="btn btn-accent btn-block">Close</button>
    </form>
</div>

@endif

<!-- Modal for childs -->
<div class="modal fade" id="modal_childs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Select a Child</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="childs_container" class="form-group p-3 font-size-16pt">
                    <!-- Childs -->
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_child_ok" class="btn btn-outline-primary">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {

    // var json_description = JSON.parse($('#course_description').val());
    // var course_quill = new Quill('#course_editor');
    // course_quill.setContents(json_description);
    // var description_html = course_quill.root.innerHTML;
    // $('div.course-description').html(description_html);

    $('input[name="stars"]').on('click', function() {
        $('#rating').val($(this).val());
    });

    $('#frm_review').on('submit', function(e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    swal({
                        title: "Submit Review",
                        type: 'success',
                    },
                    function(val) {
                        if(val) {
                            location.reload();
                        }
                    });
                }
            }
        });
    });

    $('.player__play').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.player').find('.player__embed').removeClass('d-none');
    });

    $('input[name="enroll_type"]').on('change', function() {
        $('#frm_checkout').find('input[name="price_type"]').val($(this).attr('enroll-type'));
        $('#frm_cart').find('input[name="price_type"]').val($(this).attr('enroll-type'));
    });

    $('#btn_checkout, #btn_addtocart').on('click', function(e) {
        e.preventDefault();
        var action = $(this).attr('data-action');
        $.ajax({
            method: 'GET',
            url: "{{ route('cart.getChilds') }}",
            success: function(res) {
                if(res.success && res.result) {
                    $('#childs_container').empty();
                    $.each(res.childs, function(idx, item) {
                        var ele = `<div class="custom-control custom-radio mb-2">
                            <input id="rad_child_` + item.id + `" name="radio_child" data-id="`+ item.id +`" type="radio" 
                                data-action="`+ action +`" class="custom-control-input">
                            <label for="rad_child_` + item.id + `" class="custom-control-label">`+ item.name +`</label>
                        </div>`;

                        $('#childs_container').append($(ele));
                    });

                    $('#modal_childs').modal('toggle');
                } else {
                    $('#frm_' + action).submit();
                }
            }
        });
    });

    $('#btn_child_ok').on('click', function(e) {
        var child_id = $('input[name="radio_child"]:checked', '#childs_container').attr('data-id');
        var action = $('input[name="radio_child"]:checked', '#childs_container').attr('data-action');
        $('#frm_' + action).find('input[name="child"]').val(child_id);
        $('#modal_childs').modal('toggle');
        $('#frm_' + action).submit();
    });

    // Ajax Header for Ajax Call
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Subscribe Course
    $('.btn-enroll').on('click', function() {

        var route = "{{ route('ajax.course.subscribe') }}";
        var type = $(this).attr('enroll-type');
        var course_id = $(this).attr('course-id');

        swal({
            title: "Unlock This Course!",
            text: "This Course will be unlocked",
            type: 'success',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            dangerMode: false,
        }, function(val) {
            if (val) {
                $.ajax({
                    method: 'post',
                    url: route,
                    data: {
                        course_id: course_id,
                        type: type
                    },
                    success: function(res) {

                        if (res.success) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

    $('#btn_add_favorite').on('click', function(e) {
        
        var route = $(this).attr('data-route');
        $.ajax({
            method: 'GET',
            url: route,
            beforeSend: function() {
                // setting a timeout
                $('#btn_add_favorite').addClass('is-loading is-loading-sm');
            },
            success: function(res) {
                if(res) {
                    $('#btn_add_favorite').attr('disabled', 'disabled');
                    $('#btn_add_favorite').removeClass('btn-outline-white is-loading is-loading-sm');
                    $('#btn_add_favorite').addClass('btn-white');
                    $('#btn_add_favorite').html('<i class="material-icons icon--left">favorite_border</i> Added to Favorite');
                }
            },
            error: function(err) {
                console.log(err);
            },
            complete: function() {
                $('#btn_add_favorite').removeClass('is-loading is-loading-sm');
            }
        });
    });

    var timer;

    // Pre Enroll Chat
    $('#btn_enroll_start').on('click', function() {
        timer = setInterval(loadMessage, 2000);
        $('#dv_enroll_chat').toggle('medium');
    });

    $('#dv_enroll_chat form').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    $('#messages_content').append()
                    if(res.action == 'send') {
                        $('#messages_content').append($('<ul class="d-flex flex-column list-unstyled p-2"></ul>'));
                    }
                    $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                    $('textarea[name="message"]').val('');
                }
            }
        });
    });

    $('#btn_enroll_end').on('click', function() {
        clearInterval(timer);
        $('#dv_enroll_chat').toggle('medium');
    });

    $('#btn_publish').on('click', function(e) {

        e.preventDefault();
        var button = $(this);

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                console.log(res);
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                        button.text('Unpublish');
                        button.removeClass('btn-primary').addClass('btn-info');
                    } else {
                        swal("Success!", 'Unpublished successfully', "success");
                        button.text('Publish');
                        button.removeClass('btn-info').addClass('btn-primary');
                    }
                    
                }
            }
        });
    });

    function loadMessage() {
        $.ajax({
            method: 'GET',
            url: '{{ route("admin.messages.getEnrollThread") }}',
            data: {
                course_id: '{{ $course->id }}',
                user_id: '{{ $course->teachers[0]->id }}',
                type: 'student'
            },
            success: function(res) {
                if(res.success) {
                    $('#dv_enroll_chat').find('input[name="thread_id"]').val(res.thread_id);
                    $('#messages_content').html(res.html);
                    // $(res.html).hide().appendTo('#messages_content').toggle(500);
                }
            }
        });
    }
});
</script>
@endpush

@endsection