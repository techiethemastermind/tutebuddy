@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Show Review</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.reviews.index') }}">Reviews</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Show Review
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Review Detail</div>
        </div>

        <div class="col-md-7 p-0">
            <div class="form-group">
                <div class="media align-items-center">
                    <a href="" class="media-left mr-16pt">
                        @if(!empty($review->user->avatar))
                        <img src="{{asset('/storage/avatars/' . $review->user->avatar)}}" alt="people" width="80" class="rounded-circle" />
                        @else
                        <img src="{{asset('/storage/avatars/no-avatar.jpg')}}" alt="people" width="80" class="rounded-circle" />
                        @endif
                    </a>
                    <div class="media-body">
                        <div class="form-group">
                            <label class="form-label">Customer name</label>
                            <p>{{ $review->user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Course: </label>
                <a href="{{ route('courses.show', $review->course->slug) }}">
                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                        <div class="avatar avatar-sm mr-8pt">
                            <span class="avatar-title rounded bg-primary text-white">
                                {{ substr($review->course->title, 0, 2) }}
                            </span>
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column">
                                <small class="js-lists-values-project">
                                    <strong>{{ $review->course->title }}</strong></small>
                                <small class="js-lists-values-location text-50"> {{ $review->course->category->name }}</small>
                            </div>
                        </div>
                    </div>
                </a>
                
            </div>
            <div class="form-group">
                <label for="" class="form-label">Provided Rating: {{ $review->rating }}</label>
                <div class="rating rating-24">
                    @for($r = 1; $r <= $review->rating; $r++)
                    <span class="rating__item">
                        <span class="material-icons">star</span>
                    </span>
                    @endfor

                    @if($review->rating > ($r-1))
                    <span class="rating__item"><span class="material-icons">star_half</span></span>
                    @else
                    <span class="rating__item"><span class="material-icons">star_border</span></span>
                    @endif

                    @for($r_a = $r; $r < 5; $r++)
                    <span class="rating__item">
                        <span class="material-icons">star_border</span>
                    </span>
                    @endfor
                </div>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Content</label>
                <p>{{ $review->content }}</p>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Status</label>
                @if($review->published == 1)
                <div class="d-flex flex-column">
                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                    <span class="indicator-line rounded bg-primary"></span>
                </div>
                @else
                <div class="d-flex flex-column">
                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                    <span class="indicator-line rounded bg-warning"></span>
                </div>
                @endif
            </div>
            <div class="form-group">
                @if($review->published == 0)
                <button id="btn_publish" class="btn btn-primary">Publish</button>
                @else
                <button id="btn_publish" class="btn btn-accent">UnPublish</button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>

    $('#btn_publish').on('click', function(e) {
        
        $.ajax({
            method: 'get',
            url: '{{ route("admin.publishByAjax", $review->id) }}',
            success: function(res) {
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                    } else {
                        swal("Success!", 'Unpublished successfully', "success");
                    }
                }
            }
        });
    })
</script>

@endpush

@endsection