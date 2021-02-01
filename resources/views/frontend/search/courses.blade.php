@extends('layouts.app')

@section('content')

@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .list-group-flush>.list-group-item {
        border-width: 0 0 5px;
    }
    [dir=ltr] .sidebar-light .sidebar-submenu .sidebar-menu-text {
        border-left: none;
        padding-left: .25rem;
    }
    [dir=ltr] .sidebar-light .sidebar-submenu.sm-last {
        padding-left: 1.25rem;
    }
    [dir=ltr] .sidebar-light .sidebar-submenu .sidebar-menu-text.active {
        color: #0085eb;
    }
    [dir=ltr] .sidebar-light .open>.sidebar-menu-button .sidebar-menu-text {
        color: #0085eb;
    }
    [dir=ltr] .sidebar-light.sidebar-right {
        padding-bottom: 240px !important;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    
    <div data-push data-responsive-width="1200px" class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content">
        
            <div class="pt-32pt">
                <div
                    class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
                    <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                        <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                            <h2 class="mb-0">@lang('labels.frontend.search.browse_course')</h2>

                            @if(auth()->check())
                            <ol class="breadcrumb p-0 m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                                <li class="breadcrumb-item active">
                                    @lang('labels.frontend.search.browse_course')
                                </li>
                            </ol>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="page-section">
                <div class="container page__container" style="min-height: 50vh;">

                    <div class="form-group pb-16pt position-relative">
                        <!-- <div class="search-form input-group-lg">
                            <input type="text" class="form-control" placeholder="What do you want to learn today?" 
                            value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif" search-type="course">
                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                        </div> -->
                        <div class="ui fluid category search course font-size-20pt">
                            <div class="ui icon input w-100">
                                <input class="prompt pb-16pt" type="text" placeholder="@lang('labels.frontend.home.search_course_placeholder')"
                                value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif">
                                <i class="search icon"></i>
                            </div>
                            <div class="results"></div>
                        </div>
                    </div>

                    @if(count($courses) > 0)

                    <div class="card">

                        <div class="list-group list-group-flush">

                            @foreach($courses as $course)

                            <div class="list-group-item p-3">
                                <div class="align-items-start">
                                    <div class="media">
                                        <div class="media-left mr-12pt">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="avatar avatar-xxl mr-3">
                                                @if(!empty($course->course_image))
                                                <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" 
                                                    alt="{{ $course->title }}" class="avatar-img rounded" >
                                                @else
                                                <img src="{{ asset('/assets/img/no-image.jpg') }}" 
                                                    alt="{{ $course->title }}" class="avatar-img rounded" >
                                                @endif
                                            </a>
                                            <div class="d-flex p-1" style="white-space: nowrap;">
                                                <div class="rating mr-4pt">
                                                    @if($course->reviews->count() > 0)
                                                    @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                                    @else
                                                        <small class="text-50">No rating</small>
                                                    @endif
                                                </div>
                                                @if($course->reviews->count() > 0)
                                                <small class="text-50">{{ number_format($course->reviews->avg('rating'), 2) }}/5</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="media-body media-middle">
                                            <div class="d-flex">
                                                <div class="flex">
                                                    <a href="{{ route('courses.show', $course->slug) }}" class="card-title">{{ $course->title }}</a>
                                                </div>
                                                @if(!empty($course->group_price))
                                                <span class="card-title text-accent mr-16pt">
                                                    {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }} <small class="text-50">(Group)</small>
                                                </span>
                                                @endif

                                                @if(!empty($course->private_price))
                                                <span class="card-title text-primary mr-16pt">
                                                    {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }} <small class="text-50">(Private)</small>
                                                </span>
                                                @endif
                                                
                                                @if(auth()->check())
                                                <a href="{{ route('admin.course.removeFavorite', $course->id) }}" name="remove_favorite" data-toggle="tooltip" data-title="Remove Favorite" data-placement="top" 
                                                    data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite font-color-red @if(!$course->favorited()) d-none @endif"
                                                    data-original-title="" title="">favorite</a>
                                                <a href="{{ route('admin.course.addFavorite', $course->id) }}" name="add_favorite" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" 
                                                    data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite @if($course->favorited()) d-none @endif"
                                                    data-original-title="" title="">favorite_border</a>
                                                @endif
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-70 text-muted mr-8pt"><strong>Session Time: {{ $course->duration() }},</strong></span>
                                                <span class="text-70 text-muted mr-8pt"><strong>Sessions: {{ $course->lessons->count() }},</strong></span>
                                                <span class="text-70 text-muted mr-8pt"><strong>Category: 
                                                    @if(!empty($course->category))
                                                    {{ $course->category->name }},
                                                    @else
                                                    No Category
                                                    @endif
                                                    </strong>
                                                </span>
                                                <span class="text-70 text-muted mr-8pt"><strong>Level: {{ $course->level->name }}</strong></span>
                                            </div>
                                            <div class="page-separator mb-0">
                                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                                            </div>
                                            <div class="">
                                                <p class="text-muted">{{ $course->short_description }}</p>
                                            </div>
                                            <div class="d-flex align-items-end">
                                                <a href="" class="flex">
                                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                                        <div class="avatar avatar-sm mr-8pt">
                                                            @if(!empty($course->teachers[0]->avatar))
                                                            <img src="{{ asset('/storage/avatars/' . $course->teachers[0]->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                                            @else
                                                            <span class="avatar-title rounded-circle">{{ substr($course->teachers[0]->name, 0, 2) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex d-flex flex-column">
                                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $course->teachers[0]->name }}</strong></p>
                                                                    <small class="js-lists-values-email text-50">
                                                                        {{ $course->teachers[0]->headline }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>

                                                <div class="flex">
                                                    <span class="text-70 text-muted mr-8pt">Listed Courses: 12</span>
                                                    <span class="text-70 text-muted mr-8pt">Total Courses Contucted: 102</span>
                                                </div>

                                                @if(!$course->isEnrolled())
                                                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary btn-md">Enroll</a>
                                                @else
                                                <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-success btn-md">Enrolled</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach

                        </div>

                        @if($courses->hasPages())
                        <div class="card-footer p-8pt">
                            {{ $courses->links('layouts.parts.page') }}
                        </div>
                        @endif
                    </div>

                    @else
                    <div class="card card-body">
                        <p class="card-title">No result</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mdk-drawer js-mdk-drawer " id="library-drawer" data-align="end">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-light sidebar-right py-16pt" data-perfect-scrollbar data-perfect-scrollbar-wheel-propagation="true">

                    <div class="d-flex align-items-center mb-24pt  d-lg-none">
                        <form action="" class="search-form search-form--light mx-16pt pr-0 pl-16pt">
                            <input type="text" class="form-control pl-0" placeholder="Search">
                            <button class="btn" type="submit"><i class="material-icons">search</i></button>
                        </form>
                    </div>

                    <div class="sidebar-heading">Category</div>
                    <ul class="sidebar-menu">
                        @foreach($parentCategories as $category)
                        <?php
                            $class = '';
                            if((isset($_GET['_t']) && $_GET['_k'] == $category->id) || (isset($_GET['_q']) && $_GET['_q'] == $category->name)) {
                                $class = 'active open';
                            }

                            if(isset($_GET['_k']) && $class == '') {
                                $category_id = $_GET['_k'];
                                $sub_ids = $category->children->pluck('id')->toArray();
                                if(in_array((int)$category_id, $sub_ids)) {
                                    $class = 'active open';
                                }
                                if($class == '') {
                                    $subs = $category->children;
                                    foreach($subs as $sub) {
                                        $sub1_ids = $sub->children->pluck('id')->toArray();
                                        if(in_array((int)$category_id, $sub1_ids)) {
                                            $class = 'active open';
                                        }
                                        if($class != '') {
                                            break;
                                        }
                                    }
                                }
                            }
                        ?>
                        <li class="sidebar-menu-item {{ $class }}">
                            <a class="sidebar-menu-button js-sidebar-collapse" 
                                data-toggle="collapse" href="#child_{{ $category->id }}">
                                @if(!empty($category->thumb))
                                <div class="avatar avatar-xs mr-3">
                                    <img src="{{ asset('/storage/uploads/' . $category->thumb) }}" alt="" class="avatar-img rounded" style="vertical-align: baseline;">
                                </div>
                                @else
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                @endif
                                <span class="sidebar-menu-text" data-url="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}">
                                    {{ $category->name }}
                                </span>
                                <span class="ml-auto sidebar-menu-toggle-icon"></span>
                            </a>

                            <ul class="sidebar-submenu collapse sm-indent" id="child_{{ $category->id }}" style="">
                                @foreach($category->children as $sub1)
                                <?php
                                    $sub_class = '';
                                    if(isset($_GET['_k']) && $sub_class == '') {
                                        $category_id = $_GET['_k'];
                                        $sub1_ids = $sub1->children->pluck('id')->toArray();
                                        if(in_array((int)$category_id, $sub1_ids)) {
                                            $sub_class = 'open';
                                        }
                                    }
                                ?>
                                <li class="sidebar-menu-item {{ $sub_class }}">
                                    <a class="sidebar-menu-button js-sidebar-collapse" data-toggle="collapse" href="#sub_child_{{ $sub1->id }}">
                                        <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                        <span class="sidebar-menu-text @if($category_id == $sub1->id) active @endif" 
                                            data-url="/search/courses?_q={{ $sub1->name }}&_t=category&_k={{ $sub1->id }}">
                                            {{ $sub1->name }}
                                        </span>
                                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                                    </a>

                                    <ul class="sidebar-submenu collapse sm-last" id="sub_child_{{ $sub1->id }}" style="">
                                        @foreach($sub1->children as $sub2)
                                        <li class="sidebar-menu-item">
                                            <a class="sidebar-menu-button" href="/search/courses?_q={{ $sub2->name }}&_t=category&_k={{ $sub2->id }}">
                                                <span class="sidebar-menu-text @if($category_id == $sub2->id) active @endif">{{ $sub2->name }}</span>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->
@include('layouts.parts.search-script');

@push('after-scripts')

<script>

$(function(e) {

    $('a[name="add_favorite"]').on('click', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        var btn_add_favorite = $(this);

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res) {
                    btn_add_favorite.addClass('d-none');
                    btn_add_favorite.siblings('a[name="remove_favorite"]').removeClass('d-none');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $('a[name="remove_favorite"]').on('click', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        var btn_remove_favorite = $(this);

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res) {
                    btn_remove_favorite.addClass('d-none');
                    btn_remove_favorite.siblings('a[name="add_favorite"]').removeClass('d-none');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $('span.sidebar-menu-text').on('click', function(e){
        if($(this).attr('data-url') != undefined) {
            window.location.href = $(this).attr('data-url');
        } else {
            return true;
        }
    });
});

</script>

@endpush

@endsection