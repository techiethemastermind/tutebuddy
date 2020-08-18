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
                    <h2 class="mb-0">Reviews</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                        <li class="breadcrumb-item active">
                            Reviews
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-page-length='10' 
                    data-lists-values='["js-lists-values-name", "js-lists-values-rating"]'>
                <table id="tbl_reviews" class="table mb-0 thead-border-top-0 table-nowrap">

                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#clients" id="customCheckAll_clients">
                                    <label class="custom-control-label" for="customCheckAll_clients"><span class="text-hide">Toggle all</span></label>
                                </div>
                            </th>
                            <th style="width: 40px;">No.</th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Customer</a>
                            </th>
                            <th>Course</th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-rate">Rate</a>
                            </th>
                            <th>Content</th>
                            <th>Rate Time</th>
                            <th>Actions</th>
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
    var table;

    $(document).ready(function() {

        table = $('#tbl_reviews').DataTable({
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: {
                url: '{{ route("admin.getReviewsByAjax") }}',
                complete: function(res) {
                    $.each(res.responseJSON.count, function(key, count){
                        $('#tbl_selector').find('span.count-' + key).text(count);
                    });
                }
            },
            columns: [
                { data: 'index'},
                { data: 'no'},
                { data: 'name' },
                { data: 'course'},
                { data: 'rate'},
                { data: 'content'},
                { data: 'time' },
                { data: 'action' }
            ]
        });
    });

    $('#tbl_reviews').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                    } else {
                        swal("Success!", 'Unpublished successfully', "success");
                    }
                    
                    table.ajax.reload();
                }
            }
        });
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
