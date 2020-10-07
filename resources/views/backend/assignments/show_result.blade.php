@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="py-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Review Assignment Submited</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Assignment Submited
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row">
            <div class="col-lg-8">
                <div class="page-separator">
                    <div class="page-separator__text">Assignment</div>
                </div>

                <div class="pb-32pt">
                    <h3>{{ $result->assignment->title }}</h3>
                    <div id="assignment_content" class="font-size-16pt text-black-100"></div>
                </div>

                <div class="page-separator">
                    <div class="page-separator__text">Submited Content</div>
                </div>

                <div class="pb-32pt">
                    <div id="submited_content" class="font-size-16pt text-black-100"></div>
                    @if($result->attachment_url)
                    <a href="{{ asset('/storage/uploads/' . $result->attachment_url) }}" target="_blank">
                        <img src="{{ asset('/storage/uploads/' . $result->attachment_url) }}" class="img-fluid rounded" alt="Uploads">
                    </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="page-separator">
                    <div class="page-separator__text">Information</div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="" class="form-label">Assignment Mark</label>
                            <select name="mark" id="mark" class="form-control">
                            @for($i = 0; $i <= $result->assignment->total_mark; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div class="d-none">
    <textarea id="a_text">{{ $result->assignment->content }}</textarea>
    <textarea id="s_text">{{ $result->content }}</textarea>
    <div id="a_editor"></div>
    <div id="s_editor"></div>
</div>


@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(function() {

        // Set Assignment
        var json_a_text = JSON.parse($('#a_text').val());
        var a_quill = new Quill('#a_editor');
        a_quill.setContents(json_a_text);
        var a_html = a_quill.root.innerHTML;

        $('#assignment_content').html(a_html);

        var json_s_text = JSON.parse($('#s_text').val());
        var s_quill = new Quill('#s_editor')
        s_quill.setContents(json_s_text);
        var s_html = s_quill.root.innerHTML;

        $('#submited_content').html(s_html);
        
    });
</script>

@endpush

@endsection