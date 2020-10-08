@extends('layouts.app')

@section('content')

@push('after-styles')
<style>
    [dir=ltr] .course-nav a.active {
        background-color: #5567ff;
        border: 2px solid #fff;
    }
    [dir=ltr] .course-nav a.active .material-icons {
        font-weight: bold;
        color: #fff;
    }
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="navbar navbar-light border-0 navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container flex-column flex-sm-row">
            <nav class="nav navbar-nav">
                <div class="nav-item py-16pt py-sm-0">
                    <div class="media flex-nowrap">
                        <div class="media-left mr-16pt">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}">
                                @if(!empty($lesson->course->course_image))
                                <img src="{{ asset('storage/uploads/thumb/' . $lesson->course->course_image) }}"
                                    width="40" alt="Angular" class="rounded">
                                @else
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">
                                        {{ substr($lesson->course->title, 0, 2) }}
                                    </span>
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="media-body d-flex flex-column">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}" class="card-title">
                                @if(strlen($lesson->course->title) > 60)
                                {{ substr($lesson->course->title, 0, 60) . '...' }}
                                @else
                                {{ $lesson->course->title }}
                                @endif
                            </a>
                            <div class="d-flex">
                                <span class="text-50 small font-weight-bold mr-8pt">
                                    @foreach($lesson->course->teachers as $teacher)
                                    {{ $teacher->name }},
                                    @endforeach
                                </span>
                                <span class="text-50 small">
                                    {{ $lesson->course->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <ul class="nav navbar-nav ml-sm-auto align-items-center align-items-sm-end d-none d-lg-flex">
                <li class="nav-item active ml-sm-16pt">
                    <a href="" class="nav-link">Video</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">Downloads</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">Notes</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">Discussions</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bg-primary pb-lg-64pt py-32pt">
        <div class="container page__container">
            <nav class="course-nav">
                @foreach($lesson->steps as $item)
                <a data-toggle="tooltip" data-placement="bottom" data-title="{{ $item->title }}" class="@if($item->id == $step->id) active @endif"
                    href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, $item->step]) }}">
                    @if($item->id == $step->id)
                    <span class="material-icons">done</span>
                    @else
                    <span class="material-icons">{{ config('stepicons')[$item->type] }}</span>
                    @endif
                </a>
                @endforeach
            </nav>
            @if($step->type == 'video')
            <div class="js-player bg-primary embed-responsive embed-responsive-16by9 mb-32pt">
                <div class="player embed-responsive-item">
                    <div class="player__content">
                        <div class="player__image" style="--player-image: url({{ asset('assets/img/illustration/player.svg') }})">
                        </div>
                        <a href="" class="player__play bg-primary">
                            <span class="material-icons">play_arrow</span>
                        </a>
                    </div>
                    <div class="player__embed d-none">
                        <?php
                            $embed = Embed::make($step->video)->parseUrl();
                            $embed->setAttribute([
                                'id'=>'display_step_video',
                                'class'=>'embed-responsive-item',
                                'allowfullscreen' => ''
                            ]);
                        ?>
                        {!! $embed->getHtml() !!}
                    </div>
                </div>
            </div>
            @endif

            <div class="d-flex flex-wrap align-items-end mb-16pt">
                <h1 class="text-white flex m-0">{{ $step->title }}</h1>
                @if($step->duration)
                <p class="h1 text-white-50 font-weight-light m-0">{{ $step->duration }} min</p>
                @endif
            </div>

            @if($step->type == 'video')

            <p class="hero__lead measure-hero-lead text-white-50 mb-24pt">{{ $lesson->short_text }}</p>

            @elseif($step->type == 'text')

            <div class="card card-body" id="step_content"></div>

            @endif

            @if(!empty($prev))
                <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, $prev->step]) }}" class="btn btn-outline-white">
                    Prev <i class="material-icons icon--right">chevron_left</i>
                </a>
            @endif

            @if(!empty($next))
            <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, $next->step]) }}" class="btn btn-outline-white">
                Next <i class="material-icons icon--right">chevron_right</i>
            </a>
            @else
            <a href="{{ route('lesson.complete', $lesson->id) }}" class="btn btn-outline-white">
                Finish <i class="material-icons icon--right">pause</i>
            </a>
            @endif

            @if(!$step->isCompleted())
            <a href="javascript:void(0)" id="btn_complete" class="btn btn-outline-white">
                Complete <i class="material-icons icon--right">done_outline</i>
            </a>
            @else

            <a href="javascript:void(0)" id="btn_uncomplete" class="btn btn-outline-white">
                Uncomplete <i class="material-icons icon--right">redo</i>
            </a>

            <button disabled="disabled" class="btn btn-white">
                Completed <i class="material-icons icon--right">done</i>
            </button>
            @endif

        </div>
    </div>
    <div class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
        <div class="container page__container">
            <ul class="nav navbar-nav flex align-items-sm-center">
                <li class="nav-item navbar-list__item">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm avatar-online media-left mr-16pt">
                            @if(empty($lesson->course->teachers[0]->avatar))
                            <span class="avatar-title rounded-circle">{{ substr($lesson->course->teachers[0]->name, 0, 2) }}</span>
                            @else
                            <img src="{{ asset('/storage/avatars/' . $lesson->course->teachers[0]->avatar) }}"
                                alt="{{ $lesson->course->teachers[0]->name }}" class="avatar-img rounded-circle">
                            @endif
                        </span>
                        <div class="media-body">
                            <a class="card-title m-0" href="fixed-teacher-profile.html">{{ $lesson->course->teachers[0]->name }}</a>
                            <p class="text-50 lh-1 mb-0">Instructor</p>
                        </div>
                    </div>
                </li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">schedule</i>
                    2h 46m
                </li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">assessment</i>
                    {{ $lesson->course->category->name }}
                </li>
                <li class="nav-item ml-sm-auto text-sm-center flex-column navbar-list__item">
                    <?php
                        $course_rating = 0;
                        $total_ratings = 0;
                        if ($lesson->course->reviews->count() > 0) {
                            $course_rating = $lesson->course->reviews->avg('rating');
                            $total_ratings = $lesson->course->reviews()->where('rating', '!=', "")->get()->count();
                        }
                    ?>
                    <div class="rating rating-24">
                        @for($r = 1; $r <= $course_rating; $r++)
                        <span class="rating__item"><span class="material-icons">star</span></span>
                        @endfor

                        @if($course_rating > ($r-1))
                        <span class="rating__item"><span class="material-icons">star_half</span></span>
                        @else
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endif
                        
                        @for($r_a = $r; $r < 5; $r++)
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endfor
                    </div>
                    <p class="lh-1 mb-0"><small class="text-muted">{{ $total_ratings }} ratings</small></p>
                </li>
            </ul>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">

                <div id="tab_lesson" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="#lesson_info" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h5 mb-0 mr-3 count-all">Description</span>
                        </a>
                    </div>

                    <div class="col-auto border-left">
                        <a href="#assignments" data-toggle="tab" role="tab" aria-selected="false"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h5 mb-0 mr-3 count-all">Assignments</span>
                        </a>
                    </div>

                    <div class="col-auto border-left">
                        <a href="#submissions" data-toggle="tab" role="tab" aria-selected="false"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h5 mb-0 mr-3 count-all">Submissions</span>
                        </a>
                    </div>

                    <div class="col-auto border-left">
                        <a href="#discusson" data-toggle="tab" role="tab" aria-selected="false"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h5 mb-0 mr-3 count-all">Discussion</span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="#messages" data-toggle="tab" role="tab" aria-selected="false"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h5 mb-0 mr-3 count-all">Messages</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body tab-content p-4" style="min-height: 450px;">
                <div id="lesson_info" class="tab-pane fade text-70 active show">
                    <div class="media flex-nowrap pt-2">

                        @if(!empty($lesson->video))

                        <div class="mr-32pt" style="width: 40%;">
                            <div class="js-player bg-primary embed-responsive embed-responsive-16by9 mb-32pt">
                                <div class="player embed-responsive-item">
                                    <div class="player__content">
                                        <div class="player__image" style="--player-image: url({{ asset('storage/uploads/' . $lesson->image) }})">
                                        </div>
                                        <a href="" class="player__play bg-primary">
                                            <span class="material-icons">play_arrow</span>
                                        </a>
                                    </div>
                                    <div class="player__embed d-none">
                                        <?php
                                            $embed = Embed::make($lesson->video)->parseUrl();
                                            $embed->setAttribute([
                                                'id'=>'display_step_video',
                                                'class'=>'embed-responsive-item',
                                                'allowfullscreen' => ''
                                            ]);
                                        ?>
                                        {!! $embed->getHtml() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endif

                        <div class="media-body">
                            <h4>{{ $lesson->title }}</h4>
                            <div class="align-items-center">
                                <p class="font-size-16pt">{{ $lesson->short_text }}</p>
                            </div>
                            <div>
                                <ul>
                                    @foreach($lesson->steps as $item)
                                    <li>
                                        <span class="font-size-16pt">{{ $item->title }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="assignments" class="tab-pane fade text-70">
                    <div class="d-flex align-items-center mb-heading">
                        <h4 class="m-0">Assignments</h4>
                    </div>
                    <div class="border-top">
                        <div class="list-group list-group-flush">
                            @foreach($lesson->assignments as $assignment)
                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col mb-8pt mb-md-0">
                                        <div class="media align-items-center">
                                            <div class="media-left mr-16pt">
                                                <a href="" class="avatar avatar-md">
                                                    <span class="avatar-title rounded-circle">
                                                        {{ substr($assignment->title, 0, 2) }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <p class="mb-8pt">
                                                    <a href="" class="text-body">
                                                        <strong>{{ $assignment->title }}</strong>
                                                    </a>
                                                </p>
                                                <a href="{{ route('lesson.assignment', $assignment->id) }}" target="_blank" class="chip chip-outline-secondary">Review</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="m-0">{{ $assignment->total_mark }}</h5>
                                        <p class="lh-1 mb-0"><small class="text-70">Total Marks</small></p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div id="submissions" class="tab-pane fade text-70">
                    
                </div>

                <div id="discusson" class="tab-pane fade text-70">
                    <div class="d-flex align-items-center mb-heading">
                        <h4 class="m-0">Discussions</h4>
                        <a href="{{ route('admin.discussions.create') }}" class="text-underline ml-auto">Ask a Question</a>
                    </div>

                    <div class="border-top">

                        <div class="list-group list-group-flush">

                            <div class="list-group-item p-3">
                                <div class="row align-items-start">
                                    <div class="col-md-3 mb-8pt mb-md-0">
                                        <div class="media align-items-center">
                                            <div class="media-left mr-12pt">
                                                <a href="" class="avatar avatar-sm">
                                                    <!-- <img src="LB" alt="avatar" class="avatar-img rounded-circle"> -->
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
                                                    <!-- <img src="AC" alt="avatar" class="avatar-img rounded-circle"> -->
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

                    </div>

                    <a href="fixed-discussions.html" class="btn btn-outline-secondary">See all discussions for this lesson</a>
                </div>
            </div>
        </div>

        </div>
    </div>

</div>

<div class="d-none">
    <textarea id="step_text">{{ $step->text }}</textarea>
    <div id="editor"></div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {
    
    var stepType = '{{ $step->type }}';

    if(stepType == 'text') {
        var stepText = JSON.parse($('#step_text').val());
        var quill = new Quill('#editor');
        quill.setContents(stepText);
        var content_html = quill.root.innerHTML;
        $('#step_content').html(content_html);
    }

    $('#btn_complete').on('click', function() {
        sendGet('/ajax/step/{{ $step->id }}/complete/1');
    });

    $('#btn_uncomplete').on('click', function() {
        sendGet('/ajax/step/{{ $step->id }}/complete/0');
    });

    function sendGet(route) {

        $.ajax({
            method: 'get',
            url: route,
            success: function(res) {
                console.log(res.success);
                
                if(res.success) {
                    window.location.reload();
                }
            }
        });
    }
});

</script>

@endpush

@endsection