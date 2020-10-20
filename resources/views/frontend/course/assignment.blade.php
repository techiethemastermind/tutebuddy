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
            <div id="assignment_content" class="font-size-16pt text-black-100">{!! $assignment->content !!}</div>
        </div>

        <div class="page-separator">
            <div class="page-separator__text">Submit</div>
        </div>

        <div class="pb-32pt">
            <form id="frm_assignment" method="POST" action="{{ route('assignment.save') }}" enctype="multipart/form-data">@csrf
                <div class="form-group">
                    <label class="form-label">Submit Content</label>
                    <div id="submit_content" style="min-height: 300px;">@if(!empty($assignment->result)){!! $assignment->result->content !!}@endif</div>
                </div>

                @if(!empty($assignment->result->attachment_url))
                <div class="form-group mb-24pt">
                    <label class="form-label">Attached Document:</label>
                    <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                        <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                            @php $ext = pathinfo($assignment->result->attachment_url, PATHINFO_EXTENSION); @endphp
                            @if($ext == 'pdf')
                            <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                            @else
                            <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                            @endif
                        </div>
                        <div class="flex">
                            <a href="{{ asset('/storage/attachments/' . $assignment->result->attachment_url) }}">
                                <div class="form-label mb-4pt">{{ $assignment->result->attachment_url }}</div>
                                <p class="card-subtitle text-black-70">Click to See Attached Document.</p>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Upload Doc</label>
                    <div class="custom-file">
                        <input type="file" id="file_doc" name="doc_file" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                        <label for="file_doc" class="custom-file-label">Choose file</label>
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

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(function() {

        // Set Submitted Assignments if it is exist
        var s_quill = new Quill('#submit_content', {
            theme: 'snow',
            placeholder: 'Course description'
        });

        $('#frm_assignment').on('submit', function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
                beforeSubmit: function(formData, formObject, formOptions) {

                    formData.push({
                        name: 'content',
                        type: 'text',
                        value: s_quill.root.innerHTML
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