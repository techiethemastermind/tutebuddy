@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
    [dir=ltr] .list-group-flush>.list-group-item {
        border-width: 0 0 2px;
    }
    [dir=ltr] .chip {
        margin-bottom: .5rem;
    }
    [dir=ltr] .chip+.chip {
        margin-right: .5rem;
        margin-left: 0;
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
                    <h2 class="mb-0">Browse Instructors</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Browse Instructors
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <div class="form-group pb-16pt" style="position: relative;">
                <div class="search-form input-group-lg">
                    <input type="text" class="form-control" placeholder="Enter name or Subject" 
                    value="@if(isset($_GET['_q'])) {{ $_GET['_q'] }} @endif">
                    <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                </div>
            </div>

            <div class="row card-group-row">

                @foreach($teachers as $teacher)

                <div class="col-md-6 col-xl-4 card-group-row__col">
                    <div class="card card-group-row__card">
                        <div class="card-header d-flex align-items-center">
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="card-title flex mr-12pt">{{ $teacher->name }}</a>
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-light btn-sm" data-toggle="tooltip" data-title="follow" data-placement="bottom">Follow</a>
                        </div>
                        <div class="card-body flex text-center d-flex flex-column align-items-center justify-content-center">
                            <a href="{{ route('profile.show', $teacher->uuid) }}" class="avatar avatar-xxl overlay js-overlay overlay--primary rounded-circle p-relative o-hidden mb-16pt">
                                @if(!empty($teacher->avatar))
                                <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" alt="teacher" class="avatar-img">
                                @else
                                <img src="{{ asset('/storage/avatars/no-avatar.jpg') }}" alt="teacher" class="avatar-img">
                                @endif
                                <span class="overlay__content"><i class="overlay__action material-icons icon-40pt">face</i></span>
                            </a>
                            <div class="flex">
                                <div class="d-inline-flex align-items-center mb-8pt">
                                    <div class="rating mr-8pt">
                                    @if($teacher->reviews->count() > 0)
                                        @include('layouts.parts.rating', ['rating' => $teacher->reviews->avg('rating')])
                                    @endif
                                    </div>
                                    @if($teacher->reviews->count() > 0)
                                    <small class="text-50">{{ number_format($teacher->reviews->avg('rating'), 2) }}/5</small>
                                    @endif
                                </div>
                                <p class="h5">{{ $teacher->headline }}</p>
                                <p class="text-70 measure-paragraph">{{ $teacher->about }}</p>
                            </div>
                        </div>
                        @if(!empty($teacher->profession))
                        <div class="card-body flex-0">
                            <div class="d-flex align-items-center" style="display: block !important;">
                            @php $pros = json_decode($teacher->profession); @endphp
                            
                                @foreach($pros as $pro)
                                <?php
                                    $category = App\Models\Category::find($pro);
                                    $name = !empty($category) ? $category->name : $pro;
                                ?>
                                <a href="javascript:void()" class="chip chip-outline-secondary">{{ $name }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @endforeach

                @if($teachers->hasPages())
                <div class="card-footer p-8pt">
                    {{ $teachers->links('layouts.parts.page') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>

$(function() {

    var search_ele;

    $('.search-form input[type="text"]').on('keyup', function(e) {
        search_ele = $(this).closest('.search-form');
        var key = $(this).val();
        if(e.which == 13) {
            location.href = '{{ config("app.url") }}' + 'search/instructors?_q=' + key;
        } else {
            if(key.length > 1) {
            send_ajax(key);
            } else {
                $(document).find('#search___result').remove();
            }
        }
        
    });

    $(document).on('click', '#search___result li', function() {
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var name = $(this).text();

        $('#search_homepage').val(name);
        $(document).find('#search___result').remove();

        location.href = '{{ config("app.url") }}' + 'search/instructors?_q=' + name + '&_t=' + type + '&_k=' + id;
    });

    function send_ajax(key) {

        var route = '/ajax/search/users/' + key;

        $.ajax({
            method: 'get',
            url: route,
            success: function(res) {
                if(res.success) {
                    var rlt = $(document).find('#search___result');
                    if(rlt.length > 0) {
                        rlt.remove();
                    }

                    $(res.html).insertAfter(search_ele);
                    
                }
            }
        });
    }
});

</script>

@endpush


@endsection