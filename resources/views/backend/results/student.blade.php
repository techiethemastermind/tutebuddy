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
                    <h2 class="mb-0">Courses Performance</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Courses Performance
                        </li>

                    </ol>

                </div>
            </div>

        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Courses</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date"
                    data-lists-sort-desc="true"
                    data-lists-values='["js-lists-values-no"]'>

                <table id="tbl_courses" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='10'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>

                            <th style="width: 40px;">
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-no">No.</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">Title</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">Owner</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                data-sort="js-lists-values-lead">
                                    Category</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Status</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort desc" data-sort="js-lists-values-date">Actions</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="list" id="projects"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(function() {

    var table = $('#tbl_courses').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: "{{ route('admin.results.getTableDataByAjax') }}",
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
});

</script>
@endpush

@endsection