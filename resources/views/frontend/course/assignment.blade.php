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
                    <h2 class="mb-0">Review Assignment</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Assignment
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Assignment</div>
        </div>

        <div class="pb-32pt">
            <h3>{{ $assignment->title }}</h3>
            <div id="assignment_content" class="font-size-16pt text-black-100"></div>
        </div>

        <div class="page-separator">
            <div class="page-separator__text">Submit</div>
        </div>

        <div class="pb-32pt">
            <form id="frm_assignment" method="POST" action="{{ route('assignment.save') }}" enctype="multipart/form-data">@csrf
                <div class="form-group">
                    <label class="form-label">Submit Content</label>
                    <div id="submit_content" style="min-height: 300px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Upload Doc</label>
                    <div class="custom-file">
                        <input type="file" id="file_doc" name="doc_file" class="custom-file-input">
                        <label for="file" class="custom-file-label">Choose file</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
            </form>
        </div>
    </div>

</div>
<div class="d-none">
    <textarea id="a_text">{{ $assignment->content }}</textarea>
    <textarea id="s_text">@if(!empty($assignment->result)){{ $assignment->result->content }}@endif</textarea>
    <div id="a_editor"></div>
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

        // Set Submitted Assignments if it is exist
        var s_quill = new Quill('#submit_content', {
            theme: 'snow',
            placeholder: 'Course description'
        });
        if($('#s_text').val() != '') {
            var json_s_text = JSON.parse($('#s_text').val());
            s_quill.setContents(json_s_text);
        }

        $('#frm_assignment').on('submit', function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
                beforeSubmit: function(formData, formObject, formOptions) {

                    formData.push({
                        name: 'content',
                        type: 'text',
                        value: JSON.stringify(s_quill.getContents().ops)
                    });
                },
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'success');
                    }
                }
            })
        });
        
    });
</script>

@endpush

@endsection