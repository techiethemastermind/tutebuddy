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
                    <h2 class="mb-0">Create a test</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.tests.index') }}">Tests</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create a test
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.tests.index') }}"
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
                        <div class="page-separator__text">Creat a Test</div>
                    </div>

                {!! Form::open(['method' => 'POST', 'route' => ['admin.tests.store'], 'files' => true, 'id' =>'frm_test']) !!}

                    <label class="form-label">Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.tests.fields.title')" value="">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="test_description" class="form-control" cols="100%" rows="3"
                            placeholder="Short description"></textarea>
                        <small class="form-text text-muted">Shortly describe this test. It will show under title</small>
                    </div>
                {!! Form::close() !!}

                    <div id="questions"></div>

                    <div class="page-separator">
                        <div class="page-separator__text">New Question</div>
                    </div>
                    <div class="card card-body">
                        
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.store'], 'files' => true, 'id' =>'frm_question']) !!}

                        <div class="form-group">
                            <label class="form-label">Question</label>
                            <div style="height: 150px;" class="mb-0" id="quiz_editor"></div>
                            <small class="form-text text-muted">Shortly describe the question.</small>
                            <textarea class="form-control" rows="3" placeholder="Question" style="display: none;"></textarea>
                        </div>
                        
                        <div>
                            <button id="btn_new_quiz" class="btn btn-outline-secondary">Add Question</button>
                        </div>

                    {!! Form::close() !!}


                    </div>

                </div>
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <a href="#" class="btn btn-accent">Save changes</a>
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
                                        {{asset('/storage/uploads')}}/{{ $course->course_image }}
                                        @else 
                                            {{asset('/storage/uploads/no-image.jpg')}}
                                        @endif" @if($loop->iteration == 1) selected="" @endif value="{{$course->id}}">
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

var test = {
    status: 'new',
    id: ''
};

var test_quill;

$(document).ready(function() {   

    test_quill = new Quill('#quiz_editor', {
        theme: 'snow',
        placeholder: 'Quiz'
    });

});


// Add New Question
$('#btn_new_quiz').on('click', function(e) {

    e.preventDefault();

    if(test.id == '') {

        $('#frm_test').ajaxSubmit({
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
                    test.id = res.test.id;
                    createQuestion();
                } else {
                    swal('Warning!', res.message, 'warning');
                }
            }
        });
    } else createQuestion();

});

function createQuestion() {

    $('#frm_question').ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {

            // Append quill data
            formData.push({
                name: 'question',
                type: 'text',
                value: JSON.stringify(test_quill.getContents().ops)
            });
            formData.push({
                name: 'test_id',
                type: 'int',
                value: test.id
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
                    ele_quiz_ul.append($(res.html));
                } else {
                    $('#questions').html(`
                        <div class="page-separator">
                            <div class="page-separator__text">Questions</div>
                        </div>
                        <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                    );
                }

                loadQuestions();
                test_quill.setContents('');
            }
        }
    });
}

function loadQuestions() {
    var ele_quiz_texts = $('.quiz-textarea');
    if(ele_quiz_texts.length > 0) {

        $.each(ele_quiz_texts, function(idx, item) {

            var ele_id = $(item).attr('id');
            var content = $(item).val();
            var test_quill = new Quill('#editor_' + ele_id);
            test_quill.setContents(JSON.parse(content));
            var html = test_quill.root.innerHTML;
            $('#content_' + ele_id).html($(html));
        });
    }
}

</script>
@endpush

@endsection