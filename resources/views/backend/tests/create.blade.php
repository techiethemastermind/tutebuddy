@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create Test</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.tests.index') }}">Test</a>
                        </li>

                        <li class="breadcrumb-item active">
                            New Test
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

            {!! Form::open(['method' => 'POST', 'route' => ['admin.tests.store'], 'files' => true, 'id' => 'frm_test']) !!}

            <div class="row">
                <div class="col-md-8">

                    <label class="form-label">Test Title*</label>

                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="title" value="">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Description</label>
                    <div class="form-group mb-48pt">
                        <textarea class="mb-0 form-control" name="description" rows="4" placeholder="Question Description"></textarea>
                    </div>

                    <div class="tests-wrap" id="questions"></div>

                    <button type="button" id="btn_new_question" class="btn btn-block btn-outline-secondary">Add Quesion</button>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_save_bundle" class="btn btn-accent">Save Draft</button>
                            <button type="submit" id="btn_publish_bundle" class="btn btn-primary">Publish</button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted draft">clear</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Publish</strong></a>
                                <i class="material-icons text-muted publish">clear</i>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Information</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <label class="form-label">Test Type</label>
                                <div class="custom-controls-stacked form-inline">
                                    <div class="custom-control custom-radio">
                                        <input id="test_lesson" name="type" type="radio" class="custom-control-input" value="lesson" checked="">
                                        <label for="test_lesson" class="custom-control-label">For Lesson</label>
                                    </div>
                                    <div class="custom-control custom-radio ml-8pt">
                                        <input id="test_course" name="type" type="radio" class="custom-control-input" value="course">
                                        <label for="test_course" class="custom-control-label">For Course</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">Course</label>
                                <div class="form-group mb-0">
                                    <select name="course" class="form-control custom-select @error('course') is-invalid @enderror">
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}"> {{ $course->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                    <div class="invalid-feedback">Course is required.</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Select a course.</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group" for="lesson">
                                <label class="form-label">Lessons</label>
                                <select name="lesson_id" class="form-control form-label"></select>
                                <small class="form-text text-muted">Select a lesson.</small>
                            </div>

                            <hr>

                            <!-- Set Evaluation -->
                            <div class="form-group" for="evaluation">
                                <label class="form-label">Evaluation:</label>
                                <div class="custom-controls-stacked">
                                    <div class="custom-control custom-radio py-1">
                                        <input id="score_by_mark" name="score_type" type="radio" class="custom-control-input" value="1" checked="">
                                        <label for="score_by_mark" class="custom-control-label">Score by Mark</label>
                                    </div>
                                    <div class="custom-control custom-radio py-1">
                                        <input id="score_by_grade" name="score_type" type="radio" class="custom-control-input" value="2">
                                        <label for="score_by_grade" class="custom-control-label">Score by Grade</label>
                                    </div>
                                    <div class="custom-control custom-radio py-1">
                                        <input id="no_score" name="score_type" type="radio" class="custom-control-input" value="0">
                                        <label for="no_score" class="custom-control-label">No Scoring</label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="test_id" id="test_id" value="">
                        </div>
                    </div>
                </div>
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
                    <label class="form-label">Question Image:</label>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input">
                        <label for="file" class="custom-file-label">Choose image</label>
                    </div>
                    <small class="form-text text-muted">Max file size is 5MB.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Question*</label>
                    <textarea class="form-control" name="question" rows="4" placeholder="Type Question here"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Marks (Optional)</label>
                    <input type="number" class="form-control" name="score" placeholder="Marks (Optional)">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

            <input type="hidden" name="model_type" value="test">

            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {

    var status = 'create';

    $('select[name="course"]').select2({ tags: true });
    $('select[name="lesson"]').select2({ tags: true });

    loadLessons($('select[name="course"]').val());

    $('select[name="course"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    // When add title, Hide Error msg
    $('#frm_test').on('keyup', 'input[name="title"], input[name="lesson"]', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('div.invalid-feedback').remove();
    });

    // Test Type setting
    $('input[name="type"]').on('change', function(e) {
        
        if($(this).val() == 'course') {
            $('div[for="lesson"]').addClass('d-none');
        }

        if($(this).val() == 'lesson') {
            $('div[for="lesson"]').removeClass('d-none');
        }
    });

    // Add New Question
    $('#btn_new_question').on('click', function() {

        if($('input[name="title"]').val() == '') {
            $('input[name="title"]').val('Untitled');
        }

        if(status == 'create') {
            // Store Test first
            $('#frm_test').ajaxSubmit({
                success: function(res) {
                    
                    if(res.success) {
                        status = 'update';
                        $('#test_id').val(res.test_id);
                        $('#mdl_question').modal('toggle');
                    }
                }
            });
        }

        if(status == 'update') {
            $('#mdl_question').modal('toggle');
        }
    });

    $('#frm_question').submit(function(e) {

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: $('#test_id').val()
                });
                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {
                console.log(res);
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

                    // init Modal
                    $('#frm_question input[name="image"]').val('');
                    $('#frm_question textarea[name="question"]').val('');
                    $('#frm_question input[name="score"]').val('');
                }
            }
        });
    });

    $('#frm_test').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    var url = '/dashboard/tests/' + res.test_id + '/edit';
                    window.location.href = url;
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    function loadLessons(course) {

        // Get Lessons by selected Course
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.test.getLessonsByCourse') }}",
            data: {course_id: course},
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
</script>

@endpush

@endsection