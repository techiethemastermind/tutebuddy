@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">Quizs</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                        Quizs
                        </li>

                    </ol>

                </div>
            </div>

            @can('quiz_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.quizs.create') }}" class="btn btn-outline-secondary">Add Quiz</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row">
            <div class="col-lg-3">

                <div class="page-separator">
                    <div class="page-separator__text">Search</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Course:</label>
                    <select name="courses" id="courses" class="form-control custom-select" data-toggle="select">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card p-relative o-hidden mb-lg-32pt">
                    <div class="table-responsive" data-toggle="lists">
                        <table id="tbl_quizs" class="table mb-0 thead-border-top-0 table-nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 18px;" class="pr-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#clients" id="customCheckAll_clients">
                                            <label class="custom-control-label" for="customCheckAll_clients"><span class="text-hide">Toggle all</span></label>
                                        </div>
                                    </th>
                                    <th style="width: 40px;">No.</th>
                                    <th>Title</th>
                                    <th>Questions</th>
                                    <th>Assinged</th>
                                    <th style="width: 100px;">Actions</th>
                                    <th style="width: 24px;"></th>
                                </tr>
                            </thead>
                            <tbody class="list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- Datatables -->
<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

var table, route;

$(document).ready(function() {

    route = '/dashboard/ajax/quizs/list/' + $('#courses').val();
    
    table = $('#tbl_quizs').DataTable(
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
                }
            },
            columns: [
                { data: 'index'},
                { data: 'no'},
                { data: 'title' },
                { data: 'questions'},
                { data: 'assigned'},
                { data: 'action'},
                { data: 'more' }
            ]
        }
    );
});

$('#courses').on('change', function() {
    route = '/dashboard/ajax/quizs/list/' + $('#courses').val();
    table.ajax.url(route).load();
});

$(document).on('submit', 'form[name="delete_item"]', function(e) {

    e.preventDefault();

    $(this).ajaxSubmit({
        success: function(res) {
            if(res.success) {
                table.ajax.reload();
            } else {
                swal("Warning!", res.message, "warning");
            }
        }
    });
});


</script>

@endpush

@endsection