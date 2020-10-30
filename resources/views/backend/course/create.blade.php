@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">@lang('labels.backend.courses.create')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">@lang('labels.backend.courses.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.create')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.courses.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true, 'id' => 'frm_course']) !!}
            <div class="row">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.courses.create')</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="" required>
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.description')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description"></textarea>
                        <small class="form-text text-muted">Shortly describe this course. It will show under title</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">About Course</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0"></div>
                        <small class="form-text text-muted">Describe about this course. What you will teach?</small>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Lessons</div>
                    </div>

                    <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent"></div>
                    <button type="button" id="btn_add_lesson" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ Add Lesson</button>
                </div>

                <!-- Side bar for information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_save_course" class="btn btn-accent">Save Draft</button>
                            <button type="submit" id="btn_publish_course" class="btn btn-primary">Publish Course</button>
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

                            <!-- Set Category -->
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control custom-select" data-toggle="select">
                                    <option value="">No Category</option>
                                    @foreach ($parentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @if ($category->children()->count() > 0 )
                                    <?php $space = ''; ?>
                                    @include('backend.category.sub.option', ['category' => $category, 'space' =>
                                    $space, 'selected' => 0])
                                    @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select a category.</small>
                            </div>

                            <!-- Set Level -->
                            <div class="form-group">
                                <label class="form-label">Level</label>
                                <select name="level" class="form-control">
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select a level.</small>
                            </div>

                            <!-- Set Tags -->
                            <div class="form-group mb-0">
                                <label class="form-label">Tags</label>
                                <select name="tags[]" id="course_tags" multiple="multiple" class="form-control">
                                    @foreach($tags as $tag)
                                    @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : []; @endphp
                                    <option @if(in_array($tag->name, $course_tags)) selected @endif>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select one or more tags.</small>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Options</div>
                    </div>

                    <div class="card">
                        <div class="card-body options">

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_group" type="checkbox" checked="" class="custom-control-input">
                                    <label for="chk_group" class="custom-control-label form-label">Group Course</label>
                                </div>
                            </div>

                            <!-- Set Max number in case of group course -->
                            <div class="form-group" for="chk_group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <label class="form-label">Min Students:</label>
                                        <input type="number" name="min" class="form-control" min="1" value=""
                                            placeholder="5" required>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <label class="form-label">Max Students:</label>
                                        <input type="number" name="max" class="form-control" min="1" value=""
                                            placeholder="30">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Number of Students for Group</small>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group" for="chk_group">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">Price($)</span></span>
                                    <input type="text" name="group_price" class="form-control" placeholder="5.00"
                                        value="" required>
                                </div>
                                <small class="form-text text-muted">Price for Group course.</small>
                            </div>

                            <div class="page-separator"></div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_private" type="checkbox" class="custom-control-input">
                                    <label for="chk_private" class="custom-control-label form-label">Private Course</label>
                                </div>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group d-none" for="chk_private">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">Price($)</span></span>
                                    <input type="text" name="private_price" class="form-control" value="" placeholder="24.00" required>
                                </div>
                                <small class="form-text text-muted">Price for Private course.</small>
                            </div>

                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Time Setting</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Date -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Start Date:</label>
                                            <input name="start_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">End Date:</label>
                                            <input name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Course will start and end date</small>
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">Your Timezone</label>
                                <select name="timezone" class="form-control" disabled></select>
                                <small class="form-text text-muted">Select timezone</small>
                            </div>

                            <!-- Repeat -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_repeat" type="checkbox" class="custom-control-input" checked>
                                    <label for="chk_repeat" class="custom-control-label form-label">Repeat</label>
                                    <input type="hidden" name="repeat" value="1">
                                </div>
                            </div>

                            <div class="form-group" for="chk_repeat">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <input type="number" name="repeat_value" value="1" class="form-control" min="1">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <select id="custom-select" name="repeat_type" class="form-control custom-select">
                                            <option value="week">Weeks</option>
                                            <option value="month">Months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Thumbnail</div>
                    </div>

                    <div class="card">
                        <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_course_image" alt="" width="100%">
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="course_image" id="course_image" class="custom-file-input">
                                <label for="course_image" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Introduction Video</div>
                    </div>

                    <div class="card">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_course_video" src="" allowfullscreen=""></iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url" value="" placeholder="Enter Video URL">
                            <small class="form-text text-muted">Enter a valid video URL.</small>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<!-- Add Lesson Modal -->
<div class="modal fade" id="modal_lesson" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true, 'id' =>'frm_lesson']) !!}

                    <div class="row">
                        <div class="col-12 col-md-8 mb-3">
                            <div class="form-group">
                                <label class="form-label">Title:</label>
                                <input type="text" name="lesson_title"
                                    class="form-control form-control-lg @error('lesson_title') is-invalid @enderror"
                                    placeholder="@lang('labels.backend.courses.fields.title')" value="" required>
                                @error('lesson_title')
                                <div class="invalid-feedback">Title is required field.</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Short Description:</label>
                                <textarea class="form-control" name="lesson_short_description" rows="3"></textarea>
                            </div>
                            
                            <div class="form-group" id="lesson_contents"></div>

                            <div class="form-group">
                                <div class="flex" style="max-width: 100%">
                                    <div class="btn-group" id="lesson_add_step" style="width: 100%;">
                                        <button type="button" class="btn btn-block btn-outline-secondary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">+ Add Step </button>
                                        <div class="dropdown-menu" style="width: 100%;">
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="video">Video Section</a>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="text">Full Text Section</a>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="quiz">Quiz Section</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="test">Test for this lesson</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-md-4">

                            <div class="form-group">
                                <label class="form-label">Options</label>
                                <div class="card">
                                    <div class="card-body">

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input id="chk_liveLesson" type="checkbox" name="chk_live_lesson" value="0" class="custom-control-input">
                                                <label for="chk_liveLesson" class="custom-control-label">Check this for Live
                                                    Session</label>
                                            </div>
                                        </div>
                                        <div class="form-group" for="dv_liveLesson" style="display: none;">
                                            <span class="text-muted"></span>
                                            <input type="hidden" class="form-control" name="live_lesson" value="0">
                                            <p class="mt-2">
                                                <a href="#" class="btn btn-primary btn-md">Go To Room</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Thumbnail</label>
                                <div class="card">
                                    <img src="{{asset('/assets/img/no-image.jpg')}}" id="display_lesson_image" width="100%" id="img_lesson_image" alt="">
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_image" name="lesson_file_image" class="custom-file-input">
                                            <label for="file" class="custom-file-label">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Introduce Video</label>
                                <div class="card">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item no-video lesson-video" id="iframe_lesson_intro_video" src="" allowfullscreen=""></iframe>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control" id="lesson_intro_video" name="lesson_intro_video" value=""
                                            placeholder="Enter Video URL">
                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Download File</label>
                                <div class="card">                                    
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_download" name="lesson_file_download" class="custom-file-input">
                                            <label for="file" class="custom-file-label">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Max file size is 5MB.</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_lesson" >Save</button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
$(document).ready(function() {

    var course_id = '';
    var lesson_step = 1;
    var lesson_modal = 'new';
    var lesson_current = '';
    var $lesson_contents = $('#lesson_contents');
    var lesson_id = '';

    // Init Quill Editor for Course description
    var course_quill = new Quill('#course_editor', {
        theme: 'snow',
        placeholder: 'Course description'
    });

    // Multiselect for Tags
    $('#course_tags').select2({ tags: true });

    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();

    // Single Select for category
    $('select[name="category"]').select2();
    $('select[name="category"]').on('change', function(e) {
        
        $.ajax({
            method: 'GET',
            url: '/dashboard/get/levels/' + $(this).val(),
            success: function(res) {
                if(res.success) {
                    $('select[name="level"]').html($(res.levels)).select2();
                } else {
                    console.log(res.message);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    });

    // Prices
    $('.options').on('change', 'input[type="checkbox"]', function(e) {
        if($(this).prop('checked')) {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').removeClass('d-none');
            $('.options').find('input').attr('required', 'required');
        } else {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').addClass('d-none');
            $('.options').find('input').removeAttr('required');
        }
    });

    // Blob image for Course image
    $('#course_image').on('change', function(e) {
        var target = $('#img_course_image');
        display_image(this, target);
    });

    $('#lesson_file_image').on('change', function(e) {
        var target = $('#display_lesson_image');
        display_image(this, target);
    });

    // Display Video
    $('#course_video_url').on('change', function(e) {
        target = $('#iframe_course_video');
        display_iframe($(this).val(), target);
    });

    $('#lesson_intro_video').on('change', function() {
        target = $('#iframe_lesson_intro_video');
        display_iframe($(this).val(), target);
    });

    $('#lesson_contents').on('change', 'input.step-video', function(e) {
        target = $(this).closest('.card-body').find('iframe.lesson-video');
        display_iframe($(this).val(), target);
    });

    // When click add Lesson button course should be saved draft first
    $('#btn_add_lesson').on('click', function(e) {

        e.preventDefault();

        if(course_id == '') {

            // Save draft by ajax
            $('#frm_course').ajaxSubmit({
                beforeSerialize: function($form, options) {
                    // Before form Serialized
                },
                beforeSubmit: function(formData, formObject, formOptions) {

                    var title = formObject.find('input[name="title"]');
                    if (title.val() == '') { // If title is empty then display Error msg
                        title.addClass('is-invalid');
                        var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
                        err_msg.insertAfter(title);
                        title.focus();
                        return false;
                    }

                    // Append quill data
                    formData.push({
                        name: 'course_description',
                        type: 'text',
                        value: JSON.stringify(course_quill.getContents().ops)
                    });
                    formData.push({
                        name: 'send_type',
                        type: 'text',
                        value: 'ajax'
                    });

                },
                success: function(res) {
                    if(res.success) {
                        course_id = res.course_id;
                        $('#save_status .draft').text('check');
                        $('#modal_lesson').modal('toggle');
                    } else {
                        swal('Warning!', res.message, 'warning');
                    }
                }
            });
        } else {
            if(lesson_current != 'new') {
                init_lesson_modal();
            }
            lesson_modal = 'new';
            $('#modal_lesson').modal('toggle');
        }
    });

    // Lesson Edit
    $('#parent').on('click', 'a.btn-edit', function(e){

        e.preventDefault();
        var url = $(this).attr('href');
        lesson_modal = 'edit';
        lesson_id = $(this).closest('.accordion__item').attr('lesson-id');

        // Current Lesson
        if(lesson_current == lesson_id) {
            $('#modal_lesson').modal('toggle');
            return false;
        }

        init_lesson_modal();

        // Get new lesson information
        $.ajax({
            method: 'GET',
            url: url,
            success: function(res) {

                if (res.success) {

                    // Set Lesson Modal Contents
                    $('#frm_lesson').find('input[name="lesson_title"]').val(res.lesson.title);
                    $('#frm_lesson').find('textarea[name="lesson_short_description"]').val(res.lesson.short_text);

                    if (res.lesson.image != '')
                        $('#display_lesson_image').attr('src',
                            'http://localhost:8000/storage/uploads/' + res.lesson.image);
                    else
                        $('#display_lesson_image').attr('src',
                            "{{asset('/assets/img/no-image.jpg')}}");

                            if (res.lesson.video != null) {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val(res.lesson.video).change();
                    } else {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').addClass('no-video');
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val('');
                        $('#frm_lesson').find('iframe.lesson-video').attr('src', '');
                    }

                    if(res.steps.length > 0) {

                        var lesson_contents = $('#lesson_contents');

                        $.each(res.steps, function(idx, item) {

                            var ele_sep = `<div class="page-separator">
                                                <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                                            </div>`;

                            if(item.type == 'text') {
                                var ele = `<div class="form-group step" section-type="text">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Text:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Content:</label>
                                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0"></div>
                                                        <textarea name="lesson_description__` + lesson_step + `" style="display:none;">`+ item.text +`</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'video') {

                                var ifrm_video = '<iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>';

                                var ele = `<div class="form-group step" section-type="video">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Video:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for video step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Video:</label>
                                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                                            ` + ifrm_video + `
                                                        </div>
                                                        <label class="form-label">URL</label>
                                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" value="" placeholder="Enter Video URL">
                                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'test') {
                                var ele = `<div class="form-group step" section-type="test">
                                        `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Test:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Title:</label>
                                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for test step">
                                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            lesson_contents.append($(ele));
                            lesson_step++;
                        });

                        var editors = lesson_contents.find('div[id*="lesson_editor__"]');
                        $.each(editors, function(idx, item) {
                            var id = $(item).attr('id');
                            var step = id.slice(id.indexOf('__'));
                            var quill_editor = new Quill('#' + id, {
                                theme: 'snow',
                                placeholder: 'Lesson description'
                            });
                            var lesson_description = lesson_contents.find('input[name="lesson_description'+ step +'"]').val();
                            var json_lesson_description = JSON.parse(lesson_description);
                            quill_editor.setContents(json_lesson_description);
                        });
                    }

                    lesson_step = lesson_step;
                    lesson_current = res.lesson.id;
                    $('#modal_lesson').modal('toggle');
                }
            }
        });
    });

    // When add title, Hide Error msg
    $('#frm_course').on('keyup', 'input[name="title"]', function() {
        $(this).removeClass('is-invalid');
        $('#frm_lesson').find('div.invalid-feedback').remove();
    });

    // Event when click save course button id="btn_save_course"
    $('#frm_course').submit(function(e) {
        var title = $(this).find('input[name="title"]');
        if (title.val() == '') { // If title is empty then display Error msg
            title.addClass('is-invalid');
            var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
            err_msg.insertAfter(title);
            title.focus();
            return false;
        }

        var course_description = JSON.stringify(course_quill.getContents().ops);
        var input_description = $("<input>").attr("type", "hidden")
               .attr("name", "course_description").val(course_description);
        $(this).append(input_description);
        var input_type = $("<input>").attr("type", "hidden")
               .attr("name", "send_type").val('submit');
        $(this).append(input_type);
        course.send_type = 'submit';
    });

    // Add steps
    $('#lesson_add_step').on('click', 'a.dropdown-item', function(e) {

        var ele_sep = `<div class="page-separator">
                            <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                        </div>`;

        var ele_text = `<div class="form-group step" section-type="text">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Text:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                            value="" placeholder="title for step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Content:</label>
                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0"></div>
                                        <textarea name="lesson_description__` + lesson_step + `" style="display: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_video = `<div class="form-group step" section-type="video">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Video:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                            value="" placeholder="title for video step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Video:</label>
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>
                                        </div>
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" value="" placeholder="Enter Video URL">
                                        <small class="form-text text-muted">Enter a valid video URL.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_test = `<div class="form-group step" section-type="test">
                        `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Test:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Title:</label>
                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                            value="" placeholder="title for test step">
                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                </div>
                            </div>
                        </div>`;

        var type = $(this).attr('section-type');

        switch(type) {
            case 'text':
                $lesson_contents.append($(ele_text));
                var lesson_quill = new Quill('#lesson_editor__' + lesson_step, {
                    theme: 'snow',
                    placeholder: 'Lesson description'
                });
                break;
            case 'video':
                $lesson_contents.append($(ele_video));
                break;
            case 'quiz':
                $lesson_contents.append($(ele_quiz));
                break;
            case 'test':
                $lesson_contents.append($(ele_test));
                break;
        }

        lesson_step++;
    });

    $('#lesson_contents').on('click', 'button.close', function(e) {
        $(this).closest('.form-group').remove();

        // Adjust Steps:
        var steps = $('#lesson_contents').find('div.step');
        $.each(steps, function(idx, item) {
            idx++;
            $(item).find('.page-separator__text').text('Step: ' + idx);
            status.lesson_step = idx;
        });
    });

    // Adding New Lesson
    $('#btn_save_lesson').on('click', function(e) {

        e.preventDefault();

        $('#frm_lesson').ajaxSubmit({
            beforeSerialize: function($form, options) {

                var editors = $form.find('div[id*="lesson_editor__"]');
                $.each(editors, function(idx, item) {
                    var id = $(item).attr('id');
                    var step = id.slice(id.indexOf('__'));
                    var quill_editor = new Quill('#' + id);
                    var lesson_description = JSON.stringify(quill_editor.getContents().ops);
                    $form.find('textarea[name="lesson_description'+ step +'"]').val(lesson_description);
                });
            },
            beforeSubmit: function(formData, formObject, formOptions) {
                var title = formObject.find('input[name="lesson_title"]');
                if (title.val() == '') {
                    title.addClass('is-invalid');
                    var err_msg = $(
                        '<div class="invalid-feedback">Title is required field.</div>');
                    err_msg.insertAfter(title);
                    return false;
                }

                // Append Course ID
                formData.push({
                    name: 'course_id',
                    type: 'int',
                    value: course_id
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: lesson_modal
                });

                if(lesson_modal == 'edit') {
                    formData.push({
                        name: 'lesson_id',
                        type: 'int',
                        value: status.lesson_id
                    });
                }
            },
            beforeSend: function() {
                // console.log('Before Send');
            },
            uploadProgress: function(event, position, total, percentComplete) {
                // console.log(percentComplete);
            },
            success: function(res) {

                if(res.success) {
                    if(res.action == 'new') {
                        var lesson_html = `
                            <div class="accordion__item" lesson-id="`+ res.lesson.id +`">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#lesson-toc-` + res.lesson.id + `" data-parent="#parent">
                                    <span class="flex">` + res.lesson.position + `. ` + res.lesson.title + `</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="lesson-toc-` + res.lesson.id + `">
                                    <div class="accordion__menu-link">
                                        <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                        <a class="flex" href="#">` + res.lesson.short_text.slice(0, 60) + `</a>
                                        <span class="text-muted">Just Now</span>
                                        <span class="btn-actions">
                                            <a href="/dashboard/lessons/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-preview">
                                                <i class="material-icons">remove_red_eye</i>
                                            </a>
                                            <a href="/dashboard/lessons/lesson/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="/dashboard/lessons/delete/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-delete">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#parent').append($(lesson_html));
                        lesson_current = res.lesson.id;
                        localStorage.setItem('steps__' + res.lesson_id, lesson_step);
                    }
                    
                    $('#modal_lesson').modal('toggle');

                } else {
                    swal('Warning!', res.message, 'warning');
                }
            }
        });
    });

    $('#chk_liveLesson').on('change', function(e) {
        if($(this).prop('checked')) {
            $('input[name="live_lesson"]').val('1');
        } else {
            $('input[name="live_lesson"]').val('0');
        }
    });

    function init_lesson_modal() {
        lesson_step = 0;
        lesson_current = 'new';
        $('#frm_lesson').find('input[name="lesson_title"]').val('');
        $('#frm_lesson').find('textarea').val('');
        $('#frm_lesson').find('select').val('').change();
        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
        $('#lesson_contents').html('');
    }
});
</script>

@endpush

@endsection