@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">@lang('labels.backend.bundles.create')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.bundles.index') }}">@lang('labels.backend.bundles.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.bundles.create')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.bundles.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.bundles.store'], 'files' => true, 'id' => 'frm_bundle']) !!}

            <div class="row">
                <div class="col-md-8">
                    <div class="page-separator">
                        <div class="page-separator__text">Bundle Information</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.bundles.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title" class="form-control form-control-lg"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="" tute-no-empty>
                    </div>

                    <label class="form-label">@lang('labels.backend.bundles.fields.description')</label>
                    <div class="form-group mb-48pt">
                        <textarea name="description" class="form-control" cols="100%" rows="5"
                            placeholder="description"></textarea>
                        <small class="form-text text-muted">Shortly describe this course. It will show under title</small>
                    </div>

                    <label class="form-label">Courses</label>
                    <div class="form-group">
                        <select id="select_courses" name="courses[]" multiple="multiple"
                            class="form-control form-label" tute-no-empty>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('courses')
                        <div class="invalid-feedback">At least 1 course needed.</div>
                        @enderror
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Meta Information</div>
                    </div>

                    <label class="form-label">Meta title</label>
                    <div class="form-group">
                        <input type="text" name="meta_title" class="form-control" placeholder="Meta Title">
                    </div>

                    <label class="form-label">Meta Description</label>
                    <div class="form-group mb-24pt">
                        <textarea name="meta_description" class="form-control" cols="100%" rows="5"
                            placeholder="Meta description"></textarea>
                        <small class="form-text text-muted">Meta description for SEO purpose.</small>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="button" id="btn_save_bundle" class="btn btn-accent">Save Draft</button>
                            <button type="button" id="btn_publish_bundle" class="btn btn-primary">Publish</button>
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
                                <select id="select_category" name="category" class="form-control custom-select" data-toggle="select">
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

                            <!-- Set Tags -->
                            <div class="form-group mb-0">
                                <label class="form-label">Tags</label>
                                <select id="select_tags" name="tags[]" multiple="multiple" class="form-control">
                                    @foreach($tags as $tag)
                                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
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
                        <div class="card-body">

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
                                        <input type="number" name="min" class="form-control" min="1" value="3"
                                            placeholder="5">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <label class="form-label">Max Students:</label>
                                        <input type="number" name="max" class="form-control" min="1" value="30"
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
                                    <input type="number" name="group_price" class="form-control" placeholder="5.00" value="" tute-no-empty>
                                </div>
                                <small class="form-text text-muted">Price for Group course.</small>
                            </div>

                            <div class="page-separator"></div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_private" type="checkbox" checked="" class="custom-control-input">
                                    <label for="chk_private" class="custom-control-label form-label">Private Course</label>
                                </div>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group" for="chk_private">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">Price($)</span></span>
                                    <input type="number" name="private_price" class="form-control @error('private_price') is-invalid @enderror"
                                            value="" placeholder="24.00">
                                </div>
                                <small class="form-text text-muted">Price for Private course.</small>
                                
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Thumbnail</div>
                    </div>

                    <div class="card">
                        <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_bundle_image" alt="" width="100%">
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="bundle_image" id="bundle_image" class="custom-file-input"
                                    data-preview="#img_bundle_image">
                                <label for="bundle_image" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Introduction Video</div>
                    </div>

                    <div class="card">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_bundle_video" src="" allowfullscreen=""></iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">URL</label>
                            <input type="text" class="form-control" name="bundle_video" id="bundle_video_url"
                                    data-video-preview="#iframe_bundle_video" value="" placeholder="Enter Video URL">
                            <small class="form-text text-muted">Enter a valid video URL.</small>
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

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

$(function() {

    // Multiselect for Courses
    $('#select_courses').select2({ tags: true });

    // Single Select for category
    $('#select_category').select2();

    // Multiselect for Tags
    $('#select_tags').select2({ tags: true });

    $('#btn_save_bundle').on('click', function() {
        save_bundle('draft');
    });

    $('#btn_publish_bundle').on('click', function() {
        save_bundle('publish');
    });

    function save_bundle(action){

        if(!checkValidForm($('#frm_bundle'))){
            return false;
        }

        $('#frm_bundle').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });
            },
            success: function(res) {
                console.log(res);
                if(res.success) {
                    swal({
                        title: "Successfully Stored",
                        text: "It will redirected to Editor",
                        type: 'success',
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            var url = '/dashboard/bundles/' + res.bundle_id + '/edit';
                            window.location.href = url;
                        }
                    });
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                swal('Error!', errMsg, 'error');
            }
        })
    }
});
</script>

@endpush

@endsection