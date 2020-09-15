@extends('layouts.app')

@section('content')

@push('after-styles')

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="page-section bg-primary">
        <div class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">
            @if(!empty($teacher->avatar))
            <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @else
            <img src="{{ asset('/storage/avatars/no-avatar.jpg') }}" width="154" class="mr-md-32pt mb-32pt mb-md-0" alt="instructor">
            @endif
            <div class="flex mb-32pt mb-md-0">
                <h2 class="text-white mb-0">{{ $teacher->name }}</h2>
                <p class="lead text-white-50 d-flex align-items-center">{{ $teacher->headline }} <span class="ml-16pt d-flex align-items-center">
                </p>
            </div>
            <a href="" class="btn btn-outline-white">Follow</a>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>Tutor ID verified</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <span class="flex form-label"><strong>Tutor Profile verified</strong></span>
                                <i class="material-icons text-primary">check</i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Courses offered by {{ $teacher->name }}</label>
                                <div class="mt-8pt">
                                    @foreach($teacher->courses as $course)
                                    <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $course->title }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Professions</label>
                                @if(!empty($teacher->profession))
                                <div class="mt-8pt">
                                    @php $pros = json_decode($teacher->profession); @endphp
                            
                                    @foreach($pros as $pro)
                                    <?php
                                        $category = App\Models\Category::find($pro);
                                        $name = !empty($category) ? $category->name : $pro;
                                    ?>
                                    <span class="btn btn-light btn-sm p-2 mb-8pt mr-8pt">{{ $name }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-5">
                            <h4>{{ $teacher->headline }}</h4>
                            <p class="font-size-16pt">{{ $teacher->about }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Courses by {{ $teacher->name }}</div>
            </div>

            <div class="row card-group-row mb-8pt">

                @foreach($teacher->courses as $course)

                <div class="col-sm-6 card-group-row__col">
                    <div class="card card-sm card-group-row__card">
                        <div class="card-body d-flex align-items-center">
                            <a href="{{ route('courses.show', $course->slug) }}" class="avatar avatar-4by3 overlay overlay--primary mr-12pt">
                                @if(!empty($course->course_image))
                                <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" alt="{{ $course->title }}" class="avatar-img rounded">
                                @else
                                <img src="{{ asset('/images/no-image.jpg') }}" alt="{{ $course->title }}" class="avatar-img rounded">
                                @endif
                                <span class="overlay__content"></span>
                            </a>
                            <div class="flex">
                                <a class="card-title mb-4pt" href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                <div class="d-flex align-items-center">
                                    <div class="rating mr-8pt">
                                        <?php
                                            $course_rating = 0;
                                            if ($course->reviews->count() > 0) {
                                                $course_rating = $course->reviews->avg('rating');
                                            }
                                        ?>
                                        @include('layouts.parts.rating', ['rating' => $course_rating])

                                    </div>
                                    <small class="text-muted">{{ $course_rating }}/5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection