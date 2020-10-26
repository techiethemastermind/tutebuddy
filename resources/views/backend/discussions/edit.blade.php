@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="container page__container">
        {!! Form::model($discussion, ['method' => 'PATCH', 'route' => ['admin.discussions.update', $discussion->id], 'id' =>'frm_discussions']) !!}
            <div class="row">
                <div class="col-lg-9">
                    <div class="page-section">
                        <h4>Ask a question</h4>
                        <div class="card--connect pb-32pt">
                            <div class="card o-hidden mb-0">
                                <div class="card-body table--elevated">
                                    <div class="form-group m-0" role="group" aria-labelledby="title">
                                        <div class="form-row align-items-center">
                                            <label id="title" for="title"
                                                class="col-md-3 col-form-label form-label">Question title</label>
                                            <div class="col-md-9">
                                                {!! Form::text('title', null, array('placeholder' => 'Your question ...','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="list-group">
                            <div class="list-group-item">
                                <div role="group" aria-labelledby="label-question" class="m-0 form-group">
                                    <div class="form-row">
                                        <label id="label-question" for="question"
                                            class="col-md-3 col-form-label form-label">Question details</label>
                                        <div class="col-md-9">
                                            {!! Form::textarea('question', null, array('placeholder' => 'Describe your question in detail ...', 'class' => 'form-control', 'rows' => '8')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-group m-0" role="group" aria-labelledby="label-topic">
                                    <div class="form-row align-items-center">
                                        <label class="col-md-3 col-form-label form-label">Course</label>
                                        <div class="col-md-9">
                                            <select id="course" name="course" class="form-control custom-select">
                                                @foreach($courses as $course)
                                                <option value="{{ $course->id }}" @if($course->id == $discussion->course_id) selected @endif>{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="form-group m-0" role="group" aria-labelledby="label-topic">
                                    <div class="form-row align-items-center">
                                        <label class="col-md-3 col-form-label form-label">Lesson</label>
                                        <div class="col-md-9">
                                            <select id="lesson" name="lesson" class="form-control custom-select"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item">
                                <div class="form-group m-0" role="group" aria-labelledby="label-topic">
                                    <div class="form-row align-items-center">
                                        <label class="col-md-3 col-form-label form-label">Tags:</label>
                                        <div class="col-md-9">
                                            <select id="topics" name="topics[]" multiple="multiple" class="form-control custom-select">
                                                <?php
                                                    $d_topics = json_decode($discussion->topics);
                                                ?>
                                                @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}" @if(in_array($topic->id, $d_topics)) selected @endif>{{ $topic->topic }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input id="notify" type="checkbox" class="custom-control-input" checked="">
                                    <label for="notify" class="custom-control-label">Notify me on email when someone
                                        replies to my question</label>
                                </div>
                                <small id="description-notify" class="form-text text-muted">If unchecked, you'll still
                                    recieve notifications on our website.</small>
                            </div>
                            <div class="list-group-item">
                                <button type="submit" class="btn btn-accent">Update Question</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 page-nav">
                    <div data-perfect-scrollbar data-perfect-scrollbar-wheel-propagation="true">
                        <div class="page-section pt-lg-112pt">
                            <div class="nav page-nav__menu">
                                <a href="javascript:void(0)" class="nav-link active">Before you post</a>
                            </div>
                            <div class="page-nav__content">
                                <p class="text-70">There may be other students who have asked the same question before.
                                </p>
                                <p class="text-70">You should do a quick search first to make sure your question is
                                    unique.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>

</div>
<!-- // END Header Layout Content -->


@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<script>
    $(function() {
        var lesson_id = '{{ $discussion->lesson_id }}'
        $('#topics').select2({
            tags: true
        });

        $('select[name="course"]').select2();
        $('select[name="lesson"]').select2();

        loadLessons($('select[name="course"]').val());

        $('select[name="course"]').on('change', function(e) {
            loadLessons($(this).val());
        });

        $('#frm_discussions').on('submit', function(e) {
            e.preventDefault();
            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        swal('Success', 'Successfully Updated', 'success');
                    }
                }
            });
        });

        function loadLessons(course) {

            // Get Lessons by selected Course
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.lessons.getLessonsByCourse') }}",
                data: {
                    course_id: course,
                    lesson_id: lesson_id
                },
                success: function(res) {
                    if (res.success) {
                        lesson_added = (res.lesson_id != null) ? true : false;
                        $('select[name="lesson"]').html(res.options);
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