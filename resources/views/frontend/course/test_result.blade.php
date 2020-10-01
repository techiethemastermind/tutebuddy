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

    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    <a href="{{ route('courses.show', $lesson->course->slug) }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> Back to Course
                    </a>
                </div>
                <div class="nav-item navbar-list__item">
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="mr-16pt">
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
                        <div class="flex">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}"
                                class="card-title text-body mb-0">
                                {{ $test->title }}
                            </a>
                            <p class="lh-1 d-flex align-items-center mb-0">
                                <span class="text-50 small font-weight-bold mr-8pt">
                                    {{ $lesson->course->teachers[0]->name }},
                                </span>
                                <span class="text-50 small">{{ $lesson->course->teachers[0]->headline }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="mdk-box bg-primary mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="py-64pt text-center text-sm-left">
                <div class="container d-flex flex-column justify-content-center align-items-center">
                    <h3 class="text-white-70">{{ $step->title }}</h3>
                    <p class="lead text-white-50 measure-lead-max mb-0">Submited on
                        {{ Carbon\Carbon::parse($test_result->updated_at)->diffForHumans() }}</p>
                    <h1 class="text-white mb-24pt">Your Score: {{ $test_result->test_result }}</h1>

                    <div class="flex">
                        
                        @if($test->isCompleted())
                        <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, $step->step]) }}"
                            class="btn btn-outline-white">Restart Test</a>
                        <button disabled="disabled" class="btn btn-white">
                            Completed <i class="material-icons icon--right">done</i>
                        </button>
                        @else
                        <a href="{{ route('quiz.result.complete', $test->id) }}" class="btn btn-outline-white">
                            Complete <i class="material-icons icon--right">done_outline</i>
                        </a>
                        @endif

                        @if(!empty($next))
                        @else
                        <a href="{{ route('lesson.complete', $lesson->id) }}" class="btn btn-outline-white">
                            Finish Lesson <i class="material-icons icon--right">pause</i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar navbar-expand-sm navbar-light navbar-submenu navbar-list p-0 m-0 align-items-center">
        <div class="container page__container">
            <ul class="nav navbar-nav flex align-items-sm-center">
                <li class="nav-item navbar-list__item">{{ $test_result->test_result }}/{{ $questions->count() }} Score</li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">schedule</i>
                    12 minutes
                </li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">assessment</i>
                    Intermediate
                </li>
            </ul>
        </div>
    </div>

    <div class="container page__container">
        <div class="border-left-2 page-section pl-32pt">

            @foreach($questions as $question)

            <div class="d-flex align-items-center page-num-container mb-16pt">
                <div class="page-num">{{ $loop->iteration }}</div>
                <h4>Question {{ $loop->iteration }} of {{ $questions->count() }}</h4>
            </div>

            <p class="text-70 measure-lead mb-32pt mb-lg-48pt" id="question_wrap__{{ $question->id }}"></p>

            <div class="d-none">
                <textarea id="question__{{ $question->id }}">{{ $question->question }}</textarea>
            </div>

            <ul class="list-quiz">
                @foreach($question->options as $option)
                <li class="list-quiz-item">

                    @if($option->correct == 1)
                    <span class="list-quiz-badge bg-primary text-white"><i class="material-icons">check</i></span>
                    @else
                        @if($option->correct == 0 && !empty($test_answers->where('option_id', $option->id)->all()))
                        <span class="list-quiz-badge bg-accent text-white"><i class="material-icons">clear</i></span>
                        @else
                        <span class="list-quiz-badge">{{ $loop->iteration }}</span>
                        @endif
                    @endif

                    <span class="list-quiz-text">{{ $option->option_text }}</span>
                </li>
                @endforeach
            </ul>

            @endforeach

        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<div class="d-none">
    <div id="editor"></div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>
$(document).ready(function() {
    // 
    var questions = $('textarea[id*="question__"]');
    $.each(questions, function(idx, item) {

        var id = $(item).attr('id');
        var question_id = id.substr(10);
        var quill = new Quill('#editor');
        quill.setContents(JSON.parse($(item).val()));
        var content_html = quill.root.innerHTML;
        $('#question_wrap__' + question_id).html(content_html);
    });

});
</script>

@endpush

@endsection