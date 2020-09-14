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
                    <h2 class="mb-0">Create Assignment</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.assignments.index') }}">Assignments</a>
                        </li>

                        <li class="breadcrumb-item active">
                            New Assignemnt
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.assignments.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.assignments.store'], 'files' => true, 'id' => 'frm_assignments']) !!}

            <div class="row">
                <div class="col-md-8">
                    <div class="page-separator">
                        <div class="page-separator__text">Bundle Information</div>
                    </div>

                    <label class="form-label">Assignment Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="title" value="">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Content</label>
                    <div class="form-group mb-48pt">
                        <!-- quill editor -->
                        <div id="assignment_editor" class="mb-0" style="min-height: 300px;"></div>
                        <small class="form-text text-muted">Edit Assignment</small>
                    </div>
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

                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">Course</label>
                                <div class="form-group">
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
                            <div class="form-group">
                                <label class="form-label">Lessons</label>
                                <select name="lesson_id" class="form-control form-label"></select>
                            </div>

                            <!-- Total Mark -->
                            <div class="form-group">
                                <label class="form-label">Total Marks</label>
                                <input type="number" name="total_mark" class="form-control" placeholder="5" value="5">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Attachment File</label>
                                <div class="custom-file">
                                    <input type="file" name="attachment" class="custom-file-input">
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

    // Init Quill Editor for Assignment Content
    var assignment_editor = new Quill('#assignment_editor', {
        theme: 'snow',
        placeholder: 'Assignment Content'
    });

    $('select[name="course"]').select2({ tags: true });
    $('select[name="lesson"]').select2({ tags: true });

    loadLessons($('select[name="course"]').val());

    $('select[name="course"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    // When add title, Hide Error msg
    $('#frm_assignments').on('keyup', 'input[name="title"], input[name="lesson"]', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('div.invalid-feedback').remove();
    });

    $('#frm_assignments').on('submit', function(e) {
        e.preventDefault();

        $('#frm_assignments').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = JSON.stringify(assignment_editor.getContents().ops);

                // Append Course ID
                formData.push({
                    name: 'content',
                    type: 'text',
                    value: content
                });
            },
            success: function(res) {
                if(res.success) {
                    var url = '/dashboard/assignments/' + res.assignment_id + '/edit';
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
            url: "{{ route('admin.assignment.getLessonsByCourse') }}",
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