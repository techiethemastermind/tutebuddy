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
                    <h2 class="mb-0">Perfermence Detail</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Detail
                        </li>

                    </ol>

                </div>
            </div>

        </div>
    </div>

    <div class="container page__container page-section">

        <div class="form-group mb-32pt">
            <p class="font-size-16pt mb-8pt"><strong>Course:</strong> {{ $course->title }}</p>
            <p class="font-size-16pt"><strong>Student:</strong> {{ auth()->user()->name }}</p>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_result" class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Grade</th>
                            <th>Badge</th>
                            <th>Result</th>
                        </tr>
                    </thead>

                    <tbody class="list">

                        <!-- Assignments -->
                    
                        @if(count($assignments) > 0)

                            @foreach($assignments as $assignment)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-accent text-white">AS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $assignment->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $assignment->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $assignment->due_date }}</strong></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($assignment->result)
                                                    <strong>{{ $assignment->total_mark }} / {{ (int)$assignment->result->mark }}</strong>
                                                    @else
                                                    <strong>{{ $assignment->total_mark }} / (Not Take)</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Total Marks / Marks Scored
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($assignment->result)
                                        {{ round($assignment->result->mark / $assignment->total_mark * 100) }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td><i class="material-icons fa fa-medal" style="color: #e4a93c;"></i></td>
                                <td><strong>PASS</strong></td>
                            </tr>
                            
                            @endforeach

                        @endif

                        <!-- Tests -->

                        @if(count($tests) > 0)

                            @foreach($tests as $test)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-info text-white">TE</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $test->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $test->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $test->start_date }}</strong></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($test->result)
                                                    <strong>{{ $test->score }} / {{ (int)$test->result->mark }}</strong>
                                                    @else
                                                    <strong>{{ $test->score }} / (Not Take)</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Total Marks / Marks Scored
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($test->result)
                                        {{ round($test->result->mark / $test->score * 100) }}%
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td><i class="material-icons fa fa-medal" style="color: #e4a93c;"></i></td>
                                <td><strong>PASS</strong></td>
                            </tr>
                            
                            @endforeach

                        @endif

                        <!-- Quiz -->

                        @if(count($quizs) > 0)

                            @foreach($quizs as $quiz)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">QU</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $quiz->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $quiz->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $quiz->start_date }}</strong></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($quiz->result)
                                                    <strong>{{ $quiz->score }} / {{ (int)$quiz->result->quiz_result }}</strong>
                                                    @else
                                                    <strong>{{ $quiz->score }} / (Not Take)</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Total Marks / Marks Scored
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($quiz->result)
                                        {{ round($quiz->result->quiz_result / $quiz->score * 100) }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td><i class="material-icons fa fa-medal" style="color: #e4a93c;"></i></td>
                                <td><strong>PASS</strong></td>
                            </tr>
                            
                            @endforeach

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(function() {

    // 
});

</script>
@endpush

@endsection