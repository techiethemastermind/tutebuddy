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
                                <span class="text-50 small">{{ $lesson->course->teachers[0]->about }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="bg-primary pb-lg-64pt py-32pt">
        <div class="container page__container">
            <nav class="course-nav">
                @foreach($lesson->steps as $item)
                <a data-toggle="tooltip" data-placement="bottom" data-title="{{ $item->title }}"
                    class="@if($item->id == $step->id) active @endif"
                    href="{{ route('admin.lessons.show', $lesson->id) }}?step={{ $item->id }}">
                    @if($item->id == $step->id)
                    <span class="material-icons">done</span>
                    @else
                    <span class="material-icons">{{ config('stepicons')[$item->type] }}</span>
                    @endif
                </a>
                @endforeach
            </nav>


            <div class="d-flex flex-wrap align-items-end mb-16pt">
                <h1 class="text-white flex m-0">Question <span id="t_step">{{ $testStep + 1 }}</span> of {{ $test->questions->count() }}</h1>
                <p id="time" class="h1 text-white-50 font-weight-light m-0"></p>
            </div>

            <p id="question" class="hero__lead measure-hero-lead text-white-50"></p>
        </div>
    </div>

    <div class="navbar navbar-expand-md navbar-list navbar-light bg-white border-bottom-2 "
        style="white-space: nowrap;">
        <div class="container page__container">
            <ul class="nav navbar-nav flex navbar-list__item">
                <li class="nav-item">
                    <i class="material-icons text-50 mr-8pt">tune</i>
                    Choose the correct answer below:
                </li>
            </ul>
            <div class="nav navbar-nav ml-sm-auto navbar-list__item">
                <div class="nav-item d-flex flex-column flex-sm-row ml-sm-16pt">
                    <a href="javascript:void(0)" id="btn_skip"
                        class="btn justify-content-center btn-outline-secondary w-100 w-sm-auto mb-16pt mb-sm-0">Skip
                        Question</a>
                    <a href="javascript:void(0)" id="btn_next"
                        class="btn justify-content-center btn-accent w-100 w-sm-auto mb-16pt mb-sm-0 ml-sm-16pt">Next
                        Question <i class="material-icons icon--right">keyboard_arrow_right</i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">
            <div class="page-separator">
                <div class="page-separator__text">Your Answer</div>
            </div>
            <div id="answer"></div>
            <p class="text-50 mb-0">Note: There can be multiple correct answers to this question.</p>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<div class="d-none">
    <textarea id="question_text"></textarea>
    <div id="editor"></div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

var t_step = parseInt('{{ $testStep }}');
var t_count = parseInt('{{ $test->questions->count() }}');
var time = 180; // Min
var timer, time, question_id;

// Ajax Header for Ajax Call
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

$(document).ready(function() {

    load_question(t_step);
});

$('#btn_next').on('click', function() {

    // Get correct options
    var opts = $('input[id*=opt__]');
    var ids = [];
    $.each(opts, function(idx, item) {
        
        if($(item).prop('checked')) {
            var opt_id = $(item).attr('id').substr(5);
            ids.push(opt_id);
        }
    });

    $.ajax({
        method: 'post',
        url: '/test/questions/' + question_id,
        data: {
            answers: ids
        },
        success: function(res) {
            if(res.success) {

                // Load Next Question
                load_question(t_step);
            } else {
                swal('Error!', 'Not selected Answer', 'error');
            }
        }
    });
});

$('#btn_skip').on('click', function() {
    load_question(t_step);
});

function load_question(index) {

    // Stop timer
    clearInterval(timer);
    clearInterval(timer);

    if(t_count == t_step) {
        window.location.href = '/test-result/{{ $test->id }}';
    }

    $.ajax({
        method: 'get',
        url: '/test/{{ $test->id }}/' + index,
        success: function(res) {

            if(res.success) {
                question_id = res.data.id;
                $('#question_text').text(res.data.question);
                var json_question = JSON.parse($('#question_text').val());

                var quill = new Quill('#editor');
                quill.setContents(json_question);
                var question_html = quill.root.innerHTML;

                $('#question').html(question_html);
                $('#answer').html(res.html);
                t_step++;
                $('#t_step').text(t_step);

                // Start Timer
                getTimer(true);
            }
        }
    });
}

function getTimer(status = true) {

    var x = time;

    timer = setInterval(function() {
        x--;
        var minutes = Math.floor( x / 60 );
        var seconds = Math.floor( x % 60 );

        if (minutes < 10) {minutes = "0" + minutes;}
        if (seconds < 10) {seconds = "0" + seconds;}

        $('#time').html(minutes + ':' + seconds);

        if(x == 0) {
            clearInterval(timer);

            swal({
                title: "Time is up!",
                text: "Next Question will load",
                type: 'warning',
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Confirm',
                dangerMode: false,
            }, function (val) {
                if(val) {
                    load_question(t_step);
                }
            });
        }

    }, 1000);
}
</script>

@endpush

@endsection