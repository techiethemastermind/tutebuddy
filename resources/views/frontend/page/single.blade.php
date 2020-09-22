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
                        <div class="page-separator__text">Other Pages</div>
                    </div>

                    <ul class="footer-menu">
                        <li class="footer-menu-item">
                            <a href="#" >About Us</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Support</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >FAQs</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Contact Us</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="/page/how-it-works" >How It Works</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Teach on TuteBuddy</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Solutions for Business</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Solutions for Institutions</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="/page/terms-and-conditions" >Terms of Service</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="/page/privacy-policy" >Privacy Policy</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Cookies</a>
                        </li>
                        <li class="footer-menu-item">
                            <a href="#" >Student Safety</a>
                        </li>
                    </ul>
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