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
                    <h2 class="mb-0">My Live Lessons</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Scheduled Lessons
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">My Scheduled Lessons</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.teacher.getInstructorSessionsByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">{{ $count['all'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">All</strong>
                                <small class="card-subtitle text-50">All Scheduled Lessons</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.teacher.getInstructorSessionsByAjax', 'today') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-today">{{ $count['today'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Today Scheduled</strong>
                                <small class="card-subtitle text-50">Today Scheduled Live Lessons</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_lessons" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='15'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>

                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-time">Date</a>
                            </th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Course title</th>
                            <th>Lesson title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle"></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

    $(function() {
        var route = $('#tbl_selector a[aria-selected="true"]').attr('href');

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            route = $(this).attr('href');
            table.ajax.url( route ).load();
        });

        var table = $('#tbl_lessons').DataTable(
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
                    { data: 'weekday' },
                    { data: 'start_time'},
                    { data: 'end_time'},
                    { data: 'course' },
                    { data: 'lesson' },
                    { data: 'action' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no live lessons"
                }
            }
        );
    });

</script>

@endpush

@endsection