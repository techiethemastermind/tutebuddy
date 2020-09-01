@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    div.dv {
        width: 30px;
        font-weight: 600;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">My Certifications</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Certifications
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card m-0">
            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_certs" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='10'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                        data-target="#toggle" id="customCheckAlltoggle">
                                    <label class="custom-control-label" for="customCheckAlltoggle"><span
                                            class="text-hide">Toggle all</span></label>
                                </div>
                            </th>
                            <th> Sr No. </th>
                            <th> Course Name </th>
                            <th> Progress </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(document).ready(function() {

    var table = $('#tbl_certs').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            bStateSave: true,
            ajax: "{{ route('admin.table.getCertsByAjax') }}",
            columns: [
                { data: 'index'},
                { data: 'no' },
                { data: 'title'},
                { data: 'progress'},
                { data: 'action' }
            ]
        }
    );

});

</script>

@endpush
