@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">Create a Quiz</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.quizs.index') }}">Quizs</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create a Quiz
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

            {!! Form::open(['method' => 'POST', 'route' => ['admin.quizs.store'], 'files' => true, 'id' =>'frm_quiz']) !!}

            <div class="row align-items-start">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">Creat a Quiz</div>
                    </div>

                    <label class="form-label">Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="Quiz title" value="">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <!-- Quiz Description -->
                    <label class="form-label">Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="3"
                            placeholder="Short description"></textarea>
                        <small class="form-text text-muted">Shortly describe this quiz. It will show under title</small>
                    </div>

                    <div id="questions"></div>

                    <button type="button" id="btn_new_section" class="btn btn-block btn-outline-primary">Add Section</button>

                </div>
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
                            
                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">Course</label>
                                <div class="form-group mb-0">
                                    <select name="course_id" class="form-control custom-select @error('course') is-invalid @enderror">
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}"> {{ $course->title }} </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                    <div class="invalid-feedback">Course is required.</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Select a course.</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group">
                                <label class="form-label">Lessons</label>
                                <select name="lesson_id" class="form-control"></select>
                                <small class="form-text text-muted">Select a lesson.</small>
                            </div>

                            <!-- Duration -->
                            <div class="form-group">
                                <label class="form-label">Duration (Mins)</label>
                                <input type="number" name="duration" class="form-control" min="1" placeholder="Mins" value="">
                            </div>

                            <!-- Total Marks -->
                            <div class="form-group">
                                <label class="form-label">Total Marks</label>
                                <input type="number" name="score" class="form-control" placeholder="Total Marks" min="1" value="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Modal for New Section -->
<div id="mdl_section" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.addsection'], 'files' => true, 'id' =>'frm_section']) !!}

            <div class="modal-header">
                <h5 class="modal-title">New Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">Title *</label>
                    <input type="text" name="section_title" class="form-control" placeholder="Section Title" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Section Marks</label>
                    <input type="number" name="section_marks" class="form-control" placeholder="Marks for Section">
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

            {!! Form::close() !!}
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
                    <label class="form-label">Question Type</label>
                    <select name="type" class="form-control custom-select">
                        <option value="0">Single Answer</option>
                        <option value="1">Multiple Answer</option>
                        <option value="2">Fill in Blanks</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Question</label>
                    <textarea class="form-control" name="question" rows="3" placeholder="Question"></textarea>
                </div>

                <div id="options" class="options form-group">
                    <div class="wrap wrap-signle-answer border-1 p-3">
                        <div class="form-inline mb-16pt d-flex">
                            <div class="flex">
                                <label class="form-label option-label">Add Options: </label>
                            </div>
                            <button id="btn_addOptions" class="btn btn-md btn-outline-secondary" type="button">+</button>
                        </div>
                        <hr>
                        <div class="options-wrap">
                            <div class="row mb-8pt">
                                <div class="col-10 form-inline">
                                    <div class="custom-control custom-radio">
                                        <input id="option_s0" name="option_single" type="radio" class="custom-control-input" checked="" value="0">
                                        <label for="option_s0" class="custom-control-label">&nbsp;</label>
                                    </div>
                                    <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="Option Text">
                                </div>
                                <div class="col-2 text-right">
                                    <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                </div>
                            </div>
                        </div>
                    </div>
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

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- jQuery Form -->
<script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>

<script>

$(function() {

    var status = 'new';
    var q_status = 'new';
    var quiz_id = -1;
    var group_id;
    var current_option_type = 0;
    var str_ids = ['option_s', 'option_m', 'option_f'];
    var str_names = ['option_single', 'option_multi[]', 'option_fill[]'];

    var template = [

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-radio">
                    <input id="option_s" name="option_single" type="radio" class="custom-control-input" value="0">
                    <label for="option_s" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="Single Option Text">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`),

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-checkbox">
                    <input id="option_m" name="option_multi[]" type="checkbox" class="custom-control-input" value="0">
                    <label for="option_m" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="Multi Option Text">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`),

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-checkbox">
                    <input id="option_f" name="option_fill[]" type="checkbox" class="custom-control-input" value="0">
                    <label for="option_f" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="Text for Blank">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`)

    ];

    $('select[name="course_id"]').select2();
    $('select[name="lesson_id"]').select2();

    //=== Load Lesson by Course
    loadLessons($('select[name="course_id"]').val());
    $('select[name="course_id"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    //=== Add new section
    $('#btn_new_section').on('click', function(e) {
        e.preventDefault();

        if(status == 'new') {

            // Store Quiz
            $('#frm_quiz').ajaxSubmit({
                success: function(res) {
                    
                    if(res.success) {
                        if(status == 'new') {
                            status = 'update';
                            quiz_id = res.quiz.id;
                        }
                        
                        $('#mdl_section').modal('toggle');
                    }
                }
            });
        } else {
            $('#mdl_section').modal('toggle');
        }
    });

    $('#frm_section').on('submit', function(e){
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
                });

                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {
                var page_section = $('#questions').find('div.page-section');
                if(page_section.length < 1) {
                    $('#questions').html($('<div class="border-left-2 page-section pl-32pt"></div>'));
                }
                $(res.html).hide().appendTo($('#questions .page-section')).toggle(500);
                $('#mdl_section').modal('toggle');

                // init Modal
                $('#frm_section input[name="section_title"]').val('');
                $('#frm_section input[name="section_marks"]').val('');
            }
        });
    });

    //=== Add new question to group
    $('#questions').on('click', '.btn-question', function(e) {
        e.preventDefault();
        q_status = 'new';
        group_id = $(this).attr('data-id');

        // Init Modal
        $('#mdl_question').find('select[name="type"]').val(0);
        $('#mdl_question').find('textarea[name="question"]').val('');
        $('#mdl_question').find('input[name="score"]').val(1);

        $('#mdl_question').find('div.options-wrap').empty();
        $('#mdl_question').find('div.options-wrap').html(template[current_option_type].clone());

        $('#mdl_question').modal('toggle');
    });

    $('#frm_question').submit(function(e) {

        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
                });

                formData.push({
                    name: 'group_id',
                    type: 'int',
                    value: group_id
                });

                formData.push({
                    name: 'model_type',
                    type: 'text',
                    value: 'quiz'
                });

                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {

                if(res.success) {

                    if(q_status == 'new') {
                        var ele_group_ul = $('#questions').find('div[group-id="' + group_id + '"] ul');
                        if(ele_group_ul.length > 0) {
                            $(res.html).hide().appendTo(ele_group_ul).toggle(500);
                        } else {
                            $('#questions').find('div[group-id="' + group_id + '"]').append(`
                                <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                            );
                        }
                    }

                    if(q_status == 'edit') {
                        var ele_li = $('#questions').find('li[data-id="'+ res.question.id +'"]');
                        ele_li.replaceWith($(res.html));
                    }

                    $('#mdl_question').modal('toggle');
                }
            }
        });
    });

    // ==== Edit Question ==== //
    $('#questions').on('click', 'a.question-edit', function(e) {
        e.preventDefault();
        q_status = 'edit';
        var route = $(this).attr('href');
        var update_route = $(this).attr('data-update');
        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                $('#frm_question').attr('action', update_route);
                $('#frm_question').prepend('<input name="_method" type="hidden" value="PATCH">');
                $('#frm_question').find('select[name="type"]').val(res.question.type).change();
                $('#frm_question').find('textarea[name="question"]').val(res.question.question);
                $('#frm_question').find('input[name="score"]').val(res.question.score);
                $('#options').find('div.options-wrap').html($(res.html));
                $('#mdl_question').modal('toggle');
            },
            error: function(err) {
                console.log(res);
            }
        });
        
    });

    $('#mdl_question').on('change', 'select[name="type"]', function(e) {
        current_option_type = $(this).val();
        if(current_option_type == 2) {
            $('#options').find('.option-label').text('Add text (Check for blank)');
        } else {
            $('#options').find('.option-label').text('Add Options');
        }
        $('#mdl_question').find('div.options-wrap').html(template[current_option_type]);
    });

    $('#btn_addOptions').click(function () {
        var option_num = $('.options-wrap').find('.row').last().find('input[name="'+ str_names[current_option_type] +'"]').val();
        if(option_num == undefined) {
            option_num = 0;
        }
        var new_val = parseInt(option_num) + 1;
        var new_id = str_ids[current_option_type] + new_val;
        var new_ele = template[current_option_type].clone();
        new_ele.find('input[name="' + str_names[current_option_type] + '"]').attr('id', new_id);
        new_ele.find('label').attr('for', new_id);
        new_ele.find('input[name="' + str_names[current_option_type] + '"]').val(new_val);
        new_ele.appendTo("#options .options-wrap");
    });

    // Delete option from question modal
    $('#options').on('click', '.options-wrap .remove', function(e) {
        $(this).closest('.row').remove();
        adjustOrder('option');
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

    // ==== Save quiz ==== //
    $('#btn_quiz_save').on('click', function() {

        $('#frm_quiz').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
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

    // === Load Lesson
    function loadLessons(course_id, lesson_id = 0) { // Course ID and selected Lesson ID

        // Get Lessons by selected Course
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.lessons.getLessonsByCourse') }}",
            data: {
                course_id: course_id,
                lesson_id: lesson_id
            },
            success: function(res) {
                if (res.success) {
                    lesson_added = (res.lesson_id != null) ? true : false;
                    $('select[name="lesson_id"]').html(res.options);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    }

});


// Add New Question
$('#btn_new_quiz').on('click', function(e) {

    e.preventDefault();

    if(quiz.id == '') {

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
                    quiz.id = res.quiz.id;
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
                value: JSON.stringify(quiz_quill.getContents().ops)
            });
            formData.push({
                name: 'quiz_id',
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
                quiz_quill.setContents('');
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
            var quiz_quill = new Quill('#editor_' + ele_id);
            quiz_quill.setContents(JSON.parse(content));
            var html = quiz_quill.root.innerHTML;
            $('#content_' + ele_id).html($(html));
        });
    }
}

</script>
@endpush

@endsection