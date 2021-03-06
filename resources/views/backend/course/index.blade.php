@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.courses.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.title')
                        </li>

                    </ol>

                </div>
            </div>

            @can('course_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-secondary">@lang('labels.backend.courses.add_course')</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.courses.title')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.getCoursesByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">{{ $count['all'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.all')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.all')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'draft') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-draft">{{ $count['draft'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.draft')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.draft')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'pending') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-pending">{{ $count['pending'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.pending')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.pending')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'published') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-published">{{ $count['published'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.published')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.published')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'deleted') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">{{ $count['deleted'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.achieved')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.achieved')</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table" data-toggle="lists" data-lists-sort-by="js-lists-values-date"
                data-lists-sort-desc="true"
                data-lists-values='["js-lists-values-no"]'>

                <table id="tbl_courses" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>

                            <th style="width: 40px;">
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-no">@lang('labels.backend.table.no')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">@lang('labels.backend.table.title')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">@lang('labels.backend.table.owner')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                data-sort="js-lists-values-lead">
                                @lang('labels.backend.table.category')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">@lang('labels.backend.table.status')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort desc" data-sort="js-lists-values-date">@lang('labels.backend.table.actions')</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="list" id="projects"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(document).ready(function() {

        var route = $('#tbl_selector a[aria-selected="true"]').attr('href');

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            route = $(this).attr('href');
            table.ajax.url( route ).load();
        });

        var table = $('#tbl_courses').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route,
                    complete: function(res) {
                        $.each(res.responseJSON.count, function(key, count){
                            $('#tbl_selector').find('span.count-' + key).text(count);
                        });

                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: [
                    { data: 'index'},
                    { data: 'no'},
                    { data: 'title' },
                    { data: 'name'},
                    { data: 'category'},
                    { data: 'status'},
                    { data: 'action' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no Courses"
                }
            }
        );

        $(document).on('submit', 'form[name="delete_item"]', function(e) {

            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                        $(document).find('.tooltip.show').remove();
                    } else {
                        swal("Warning!", res.message, "warning");
                    }
                }
            });
        });

        $('#tbl_courses').on('click', 'a[data-action="restore"], a[data-action="delete"]', function(e) {

            e.preventDefault();
            var url = $(this).attr('href');

            var swal_text = "@lang('labels.backend.swal.paths.description.delete')";

            if($(this).attr('data-action') == 'restore') {
                swal_text = "@lang('labels.backend.swal.paths.description.restore')";
            }

            swal({
                title: "Are you sure?",
                text: swal_text,
                type: 'info',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                dangerMode: false,
            }, function (val) {
                if(val) {
                    $.ajax({
                        method: 'GET',
                        url: url,
                        success: function(res) {
                            if(res.success) {
                                table.ajax.reload();
                                $(document).find('.tooltip.show').remove();
                            }
                        }
                    });
                }
            });
        });

        $('#tbl_courses').on('click', 'a[data-action="publish"]', function(e) {

            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                method: 'get',
                url: url,
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        if(res.published == 1) {
                            swal("Success!", 'Published successfully', "success");
                        } else {
                            swal("Success!", 'Unpublished successfully', "success");
                        }
                        
                        table.ajax.reload();
                        $(document).find('.tooltip.show').remove();
                    }
                }
            });
        });

    });

</script>

@endpush


@endsection