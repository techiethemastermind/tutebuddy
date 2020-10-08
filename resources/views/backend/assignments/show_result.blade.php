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
                    <h2 class="mb-0">Review Assignment Submitted</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Assignment Submitted
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
                    <div class="page-separator__text">Submitted Content</div>
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
                        <form id="frm_a_result" method="POST" action="{{ route('admin.assignments.result_answer') }}" enctype="multipart/form-data">@csrf
                            <div class="form-group">
                                <label for="" class="form-label">Assignment Mark</label>
                                <select name="mark" id="mark" class="form-control">
                                @for($i = 0; $i <= $result->assignment->total_mark; $i++)
                                    @if($result->mark == $i)
                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Summary</label>
                                <textarea name="answer" rows="10" class="form-control">{{ $result->answer }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Attachment</label>
                                <div class="custom-file">
                                    <input type="file" id="file_doc" name="answer_attach" class="custom-file-input">
                                    <label for="file" class="custom-file-label">Choose file</label>
                                </div>
                            </div>

                            <input type="hidden" name="result_id" value="{{ $result->id }}">

                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
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

        $('#frm_a_result').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        swal('Success!', 'Successfully Submitted!', 'success')
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection