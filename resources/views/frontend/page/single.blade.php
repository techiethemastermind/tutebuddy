@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="page-section bg-alt border-bottom-2">
        <div class="container page__container">

            <div class="d-flex flex-column flex-lg-row align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start flex mb-16pt mb-lg-0 text-center text-md-left">

                    <div class="flex">
                        <h1 class="h2 measure-lead-max mb-16pt">{{ $page->title }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row">
                <div class="col-lg-8">
                    @if(!empty($page->image))
                    <div class="d-flex flex-column flex-md-row align-items-md-center mb-32pt">
                        <div class="mb-16pt mb-md-0 mr-md-16pt">
                            <div class="rounded p-relative o-hidden overlay overlay--primary">
                                <img class="img-fluid rounded" src="{{ asset('/storage/uploads/' . $page->image) }}" alt="image">
                                <div class="overlay__content"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="content mb-64pt">
                        <!-- quill editor -->
                        <div id="page-wrap" class="mb-0"></div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="page-separator">
                        <div class="page-separator__text">Recommended</div>
                    </div>

                    @foreach($recents as $recent)
                    <div class="mb-8pt d-flex align-items-center">
                        <a href="{{ route('page.show', $recent->slug) }}" class="avatar avatar-lg overlay overlay--primary mr-12pt">
                            @if(!empty($recent->image))
                            <img src="{{ asset('/storage/uploads/' . $recent->image) }}" alt="{{ asset('/storage/uploads/' . $recent->image) }}" class="avatar-img rounded">
                            @else
                            <span class="avatar-title rounded bg-primary text-white">{{ substr($recent->title, 0, 2) }}</span>
                            @endif
                            <span class="overlay__content"></span>
                        </a>
                        <div class="flex">
                            <a class="card-title mb-4pt" href="fixed-blog-post.html">{{ $recent->title }}</a>
                            <div class="d-flex align-items-center">
                                <small class="text-muted">{{ \Carbon\Carbon::parse($recent->created_at)->format('h:i A | M d Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    <div>

</div>
<textarea id="page_content" class="d-none">{{ $page->content }}</textarea>
<div id="page_editor" class="d-none"></div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {

    var json_content = JSON.parse($('#page_content').val());
    var page_quill = new Quill('#page_editor');
    page_quill.setContents(json_content);
    var content_html = page_quill.root.innerHTML;
    $('#page-wrap').html(content_html);

});

</script>

@endpush

@endsection