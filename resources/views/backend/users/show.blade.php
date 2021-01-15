@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.user_detail.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">User Management</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Edit Account
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>

            @can('user_create')
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="page-section container page__container">
        <div class="">
            <div class="form-group">
                <div class="media">
                    <a href="" class="media-left mr-32pt">
                        @if($user->avatar)
                        <img src="{{asset('/storage/avatars/'. $user->avatar) }}" alt="people" width="250" class="rounded-circle" />
                        @else
                        <img src="{{asset('/images/no-avatar.jpg')}}" alt="people" width="250" class="rounded-circle" />
                        @endif
                    </a>
                    <div class="media-body">

                        <div class="page-separator">
                            <div class="page-separator__text">Profile Information</div>
                        </div>
                        <div class="card">
                            <div class="card-body p-5 font-size-16pt">

                                <div class="">
                                    <label class="form-label font-size-16pt">Name: </label>
                                    <span>{{ $user->name }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Email Address: </label>
                                    <span>{{ $user->email }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Role: </label>
                                    <span>{{ $user->getRoleNames()->first() }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Phone: </label>
                                    <span>{{ $user->phone }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Address: </label>
                                    <span>{{ $user->address }}, {{ $user->city }}, {{ $user->state }}, {{ $user->country }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Timezone: </label>
                                    <span>{{ $user->timezone }}</span>
                                </div>
                            </div>
                        </div>

                        @if($user->hasRole('Instructor'))
                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">Instrutor Information</div>
                        </div>

                        <div class="form-group">
                            <h4>{{ $user->headline }}</h4>
                            <p class="font-size-16pt">{{ $user->about }}</p>
                        </div>

                        @if(!empty($user->qualifications))

                        <div class="card">
                            <div class="card-body p-5">
                                <h4>Professional Qualifications and Certifications</h4>
                                @foreach(json_decode($user->qualifications) as $qualification)
                                <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $qualification }}</p>
                                @endforeach
                            </div>
                        </div>

                        @endif

                        @if(!empty($user->qualifications))

                        <div class="card">
                            <div class="card-body p-5">
                                <h4>Achievements</h4>
                                @foreach(json_decode($user->achievements) as $achievement)
                                <p class="font-size-16pt mb-1"><strong>{{ $loop->iteration }}. </strong> {{ $achievement }}</p>
                                @endforeach
                            </div>
                        </div>

                        @endif

                        @if(!empty($user->experience))

                        <div class="card">
                            <div class="card-body p-5">
                                <h4>Experience</h4>
                                <p class="font-size-16pt mb-1">{{ $user->experience }}</p>
                            </div>
                        </div>

                        @endif
                        @endif

                        <div class="form-group">
                            <button id="btn_approve" class="btn btn-primary" data-user-id="{{ $user->id }}">Approve</button>
                            <button id="btn_decline" class="btn btn-accent" data-user-id="{{ $user->id }}">Decline</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>

    $(function() {

        $('#btn_approve').on('click', function(e) {
            var user_id = $(this).attr('data-user-id');
            var route = '/account/'+ user_id +'/approve';
            $.ajax({
                url: route,
                method: 'GET',
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'success');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('#btn_decline').on('click', function(e) {
            var user_id = $(this).attr('data-user-id');
            var route = '/account/'+ user_id +'/decline';
            $.ajax({
                url: route,
                method: 'GET',
                success: function(res) {
                    if(res.success) {
                        swal('Warning!', res.message, 'warning');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    });
</script>

@endpush

@endsection