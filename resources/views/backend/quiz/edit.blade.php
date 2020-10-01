@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<style>
.modal .modal-body {
    max-height: 80vh;
    overflow: auto;
}
.accordion .btn-actions {
    margin: 0 10px;
}
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit quiz</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.quizs.index') }}">quizs</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit quiz
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.quizs.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row align-items-start">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">Edit a quiz</div>
                    </div>

                    {!! Form::open(['method' => 'PATCH', 'route' => ['admin.quizs.update', $quiz->id], 'files' => true, 'id' =>'frm_quiz']) !!}

                    <label class="form-label">Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="{{ $quiz->title }}">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="3"
                            placeholder="Short description">{{ $quiz->description }}</textarea>
                        <small class="form-text text-muted">Shortly describe this quiz. It will show under title</small>
                    </div>
                    
                    {!! Form::close() !!}

                    <div id="questions">
                    
                    @if($quiz->questions->count() > 0)
                        <div class="page-separator">
                            <div class="page-separator__text">Questions</div>
                        </div>

                        <ul class="list-group stack mb-40pt questions">

                        @foreach($quiz->questions as $question)

                        @if(!$question->trashed())

                        <li class="list-group-item d-flex quiz-item">
                            <div class="flex d-flex flex-column">
                                <div class="card-title mb-16pt">Question {{ $loop->iteration }}</div>
                                <div class="card-subtitle text-70 paragraph-max" id="content_quiz_{{ $question->id }}"></div>

                                <div class="work-area d-none">
                                    <div id="editor_quiz_{{ $question->id }}"></div>
                                    <textarea id="quiz_{{ $question->id }}" class="quiz-textarea">{{ $question->question }}</textarea>
                                </div>
                                
                                <div class="text-right">
                                    <div class="chip chip-outline-secondary">
                                        @if($question->type == 1)
                                            Single Answer
                                        @else
                                            Multi Answer
                                        @endif
                                    </div>
                                    <div class="chip chip-outline-secondary">Score: {{ $question->score }}</div>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                        class="material-icons">more_horiz</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <?php
                                        $edit_route = route('admin.questions.edit', $question->id);
                                        $delete_route = route('admin.questions.delete', $question->id);
                                    ?>
                                    <a href="{{ $edit_route }}" class="dropdown-item" target="_blank">Edit Question</a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ $delete_route }}" class="dropdown-item text-danger question-delete">Delete Question</a>
                                </div>
                            </div>
                        </li>

                        @endif

                        @endforeach

                        </ul>
                    @endif

                    </div>

                    <button id="btn_new_question" class="btn btn-block btn-outline-secondary">Add Quesion</button>

                </div>

                <!-- Right Side -->
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <a href="javascript:void(0);" class="btn btn-accent" id="btn_quiz_save">Save changes</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="#"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            <div class="list-group-item">
                                <a href="#" class="text-danger"><strong>Delete Quiz</strong></a>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Courses</div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <label class="form-label">Add to course</label>
                                <select name="course" id="course" data-toggle="select" data-tags="true"
                                    data-multiple="true" multiple="multiple" data-minimum-results-for-search="-1"
                                    class="form-control" data-placeholder="Select course ...">
                                    @foreach($courses as $course)
                                    <option data-avatar-src="@if(!empty($course->course_image)) 
                                        {{ asset('/storage/uploads' . $course->course_image) }}
                                        @else 
                                            {{asset('/assets/img/no-image.jpg')}}
                                        @endif" @if($quiz->course_id == $course->id) selected="" @endif value="{{$course->id}}">
                                        {{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add new question -->
<div id="mdl_question" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.store'], 'files' => true, 'id' =>'frm_question']) !!}

            <div class="modal-header">
                <h5 class="modal-title">New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Question</label>
                    <div style="height: 150px;" class="mb-0" id="quiz_editor"></div>
                    <small class="form-text text-muted">Shortly describe the question.</small>
                    <textarea class="form-control" rows="3" placeholder="Question" style="display: none;"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Question Type</label>
                    <select name="type" class="form-control custom-select">
                        <option value="0">Multiple Answer</option>
                        <option value="1">Single Answer</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Completion Points</label>
                    <input name="score" type="text" class="form-control" value="1">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- jQuery Form -->
<script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>

<script>

var quiz = {
    id: '{{ $quiz->id }}'
};

var quiz_quill;

$(document).ready(function() {

    // Load questions
    loadQuestions();

    // New question quill
    quiz_quill = new Quill('#quiz_editor', {
        theme: 'snow',
        placeholder: 'Quiz'
    });
});

// ==== Update quiz ==== //
$('#btn_quiz_save').on('click', function() {

    $('#frm_quiz').ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {

            var title = formObject.find('input[name="title"]');
            if (title.val() == '') { // If title is empty then display Error msg
                title.addClass('is-invalid');
                var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
                err_msg.insertAfter(title);
                title.focus();
                return false;
            }

            // Add course Id;
            formData.push({
                name: 'course_id',
                type: 'int',
                value: $('#course').val()
            });
            formData.push({
                name: 'send_type',
                type: 'text',
                value: 'ajax'
            });
        },
        success: function(res) {
            if(res.success) {
                swal("Success!", "Successfully updated", "success");
            } else {
                swal('Warning!', res.message, 'warning');
            }
        }
    });
});

// Add New Question
$('#btn_new_question').on('click', function() {
    $('#mdl_question').modal('toggle');
});

$('#frm_question').submit(function(e) {

    e.preventDefault();

    $(this).ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {

            // Append quill data
            formData.push({
                name: 'question',
                type: 'text',
                value: JSON.stringify(quiz_quill.getContents().ops)
            });
            formData.push({
                name: 'test_id',
                type: 'int',
                value: quiz.id
            });
            formData.push({
                name: 'send_type',
                type: 'text',
                value: 'ajax'
            });
        },
        success: function(res) {

            if(res.success) {

                var ele_quiz_ul = $('#questions').find('ul');
                if(ele_quiz_ul.length > 0) {
                    $(res.html).hide().appendTo(ele_quiz_ul).toggle(500);
                } else {
                    $('#questions').html(`
                        <div class="page-separator">
                            <div class="page-separator__text">Questions</div>
                        </div>
                        <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                    );
                }

                $('#mdl_question').modal('toggle');

                loadQuestions();
                quiz_quill.setContents('');
            }
        }
    });
});

// ==== Delete Question ====/

$('#questions').on('click', 'a.question-delete', function(e) {

    e.preventDefault();
    var route = $(this).attr('href');
    var question_item = $(this).closest('li');

    swal({
        title: "Are you sure?",
        text: "This Question will removed from this quiz",
        type: 'warning',
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        dangerMode: false,

    }, function(val) {
        if (val) {
            $.ajax({
                method: 'GET',
                url: route,
                success: function(res) {
                    if (res.success) {
                        question_item.toggle( function() { 
                            $(this).remove();
                            adjustOrder();
                        });
                    }
                }
            });
        }
    });
});


// Load questions

function loadQuestions() {
    var ele_quiz_texts = $('.quiz-textarea');
    if(ele_quiz_texts.length > 0) {

        $.each(ele_quiz_texts, function(idx, item) {

            var ele_id = $(item).attr('id');
            var content = $(item).val();
            var quiz_quill = new Quill('#editor_' + ele_id);
            quiz_quill.setContents(JSON.parse(content));
            var html = quiz_quill.root.innerHTML;
            $('#content_' + ele_id).html($(html));
        });
    }
}


// Adjust option order

function adjustOrder() {

    var ele_lis = $('#options').find('li');

    $.each(ele_lis, function(idx, item) {
        $(item).find('.card-title').text('Question ' + (idx + 1));
    });
}

</script>
@endpush

@endsection