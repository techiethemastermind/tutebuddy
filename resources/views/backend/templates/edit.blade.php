@extends('layouts.app')

@section('content')

@push('after-styles')

    <!-- Quill Theme -->
    <link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Tempalte</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.mailedits.index') }}">Email Templates</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Template
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.mailedits.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                    <button id="btn_save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-separator">
                        <div class="page-separator__text">Edit Form</div>
                    </div>

                    <div class="card m-0">
                        <div class="card-body">
                            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.mailedits.update', $template->id], 'files' => true, 'id'
                            => 'frm_template']) !!}

                            <div class="form-group">
                                <label class="form-label">Template Name: </label>

                                <select id="custom-select" name="name" id="name" class="form-control custom-select" required>
                                @foreach(config('mail.email_types') as $key => $name)
                                    @if($key == $template->name)
                                    <option value="{{ $key }}" selected>{{ $name }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $name }}</option>
                                    @endif
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Logo: </label>
                                <div class="custom-file">
                                    <input type="file" id="logo" name="logo" class="custom-file-input" data-preview="#preview_logo">
                                    <label for="logo" class="custom-file-label">Choose file</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Title: </label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Email Title" for="#preview_title">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Sub Title: </label>
                                <input type="text" name="sub_title" id="sub_title" class="form-control"
                                    placeholder="Sub Title" for="#preview_sub_title">
                            </div>

                            <!-- Information Section -->
                            <div class="form-group">
                                <div class="form-inline mb-16pt d-flex">
                                    <div class="flex">
                                        <label class="form-label">Add Email Information: </label>
                                    </div>
                                    <button id="btn_addInfo" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                                
                                <div id="info_group" class="form-group border-1 p-3">
                                    <div class="row">
                                        <div class="col-5">
                                            <label class="form-label"><strong>Name: </strong></label>
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label"><strong>Value: </strong></label>
                                        </div>
                                        <div class="col-2">
                                            <label class="form-label"><strong>Action: </strong></label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="wrap"></div>
                                    <div class="wrap-footer d-none">
                                        <hr>
                                        <div class="text-right">
                                            <button id="btn_appendInfo" class="btn btn-md btn-outline-secondary" type="button">Append</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Content Title: </label>
                                <input type="text" name="content_title" id="content_title" class="form-control"
                                    placeholder="Content Title" for="#preview_content_title">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <!-- quill editor -->
                                <div id="editor" class="mb-0" style="min-height: 30vh;"></div>
                                <small class="form-text text-muted">Edit Content</small>
                                <textarea id="content_editor" name="editor" class="d-none">{{ $template->editor }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Footer Text: </label>
                                <input type="text" name="footer_text" id="footer_text" class="form-control"
                                    placeholder="Footer Text" for="#preview_footer">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Footer Link: </label>
                                <input type="text" name="footer_link" id="footer_link" class="form-control"
                                    placeholder="Footer link" for="#preview_footer">
                            </div>

                            <textarea name="html_content" id="html_content" class="d-none">{{ $template->content }}</textarea>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="page-separator">
                        <div class="page-separator__text">Preview</div>
                    </div>

                    <div class="prev_wrap" id="prev_wrap"></div>

                    <div class="page-separator">
                        <div class="page-separator__text">Email Test</div>
                    </div>

                    <div class="form-group">
                        <div class="card card-body">
                            <div class="d-flex">
                                <input type="email" name="test_email" id="test_email" class="form-control flex mr-3" placeholder="Email Address">
                                <button id="btn_test" class="btn btn-accent">Send Test Email</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

    <!-- Quill -->
    <script src="{{ asset('assets/js/quill.min.js') }}"></script>
    <script src="{{ asset('assets/js/quill.js') }}"></script>

    <script>
        $(function () {

            // Init Quill Editor
            var editor = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Email Content'
            });

            // Init page

            var template_editor = JSON.parse($('#content_editor').val());
            editor.setContents(template_editor);

            var template_content = $($('#html_content').val());
            $('#prev_wrap').html(template_content);

            // Title
            $('#title').val($('#preview_title').text());
            $('#sub_title').val($('#preview_sub_title').text());
            $('#content_title').val($('#preview_content_title').text());


            $('#frm_template').on('focusout', 'input[type="text"]', function(e) {
                if($(this).val() !== '') {
                    $($(this).attr('for')).text($(this).val());

                    if($(this).attr('id') == 'footer_link') {
                        $('#preview_footer').attr('href', $(this).val());
                    }
                }
            });

            $('#editor').on('focusout', function(e) {
                var content_html = editor.root.innerHTML;
                $('#preview_content').html(content_html);
            });

            $('#btn_save').on('click', function(e) {
                var template_html = $('#prev_wrap').html();
                $('#html_content').val(template_html);
                $('#frm_template').submit();
            });

            // Logo
            var logo = '{{ $template->logo }}';
            if(logo != '') {
                $('#preview_logo').attr('src', '{{ asset("/storage/uploads") }}' + '/' + logo);
            }

            $('#frm_template').on('submit', function(e) {
                e.preventDefault();

                $(this).ajaxSubmit({
                    success: function(res) {
                        if(res.success) {
                            swal('Success!', 'successfully Updataed', 'success');
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            var counter = 0;

            $("#btn_addInfo").click(function () {

                var newBlock = $(`<div class="row form-inline mb-8pt">
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="Customer Email Address:">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="{email}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>`);
                
                newBlock.appendTo("#info_group .wrap");

                if($('#info_group .wrap-footer').hasClass('d-none')) {
                    $('#info_group .wrap-footer').removeClass('d-none');
                };
            });

            $('#info_group').on('click', '.wrap .remove', function(e) {
                $(this).closest('.row').remove();

                if($('#info_group .wrap').find('.remove').length < 1) {
                    $('#info_group .wrap-footer').addClass('d-none');
                }
            });

            $('#btn_appendInfo').on('click', function(e) {

                var info_ele = '';

                $.each($('#info_group .wrap').find('.row'), function(idx, ele) {
                    console.log(ele);
                    var name = $(ele).find('input.name').val();
                    var value = $(ele).find('input.value').val();
                    var new_tr = `<tr>
                                <td align="left"
                                    style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                    <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;"
                                        align="left">
                                        <span
                                            class="label"
                                            style="font-weight: 700;">`+ name +`</span>
                                        <span
                                            style="color:#25B9D7;font-weight:600; text-decoration: underline;">`+ value +`</span>
                                    </div>
                                </td>
                            </tr>`;
                    info_ele += new_tr;
                });

                $('#preview_info').html($(info_ele));
            });

            $('#btn_test').on('click', function() {

                $.ajax({
                    method: 'GET',
                    url: '{{ route("admin.ajax.sendTestEmail") }}',
                    data: {
                        id: '{{ $template->id }}',
                        email: $('#test_email').val()
                    },
                    success: function(res) {
                        console.log(res);
                        if(res.success) {
                            swal('Success!', 'Test Email Sent!', 'success');
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });
        });

    </script>

@endpush

@endsection
