@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">User Account Preview</h2>

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

            @can('user_delete')
            <div class="row" role="tablist">
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE','route' => ['admin.users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-outline-secondary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="page-section container page__container">
        <div class="page-separator">
            <div class="page-separator__text">Profile &amp; Privacy</div>
        </div>
        <div class="col-md-7 p-0">
            <div class="form-group">
                <div class="media align-items-center">
                    <a href="" class="media-left mr-16pt">
                        @if($user->avatar)
                        <img src="{{asset('/storage/avatars')}}/{{$user->avatar}}" alt="people" width="120" class="rounded-circle" />
                        @else
                        <img src="{{asset('/storage/avatars/no-avatar.jpg')}}" alt="people" width="120" class="rounded-circle" />
                        @endif
                    </a>
                    <div class="media-body">
                        <div class="form-group">
                            <label class="form-label">Profile name</label>
                            <p>{{ $user->name }}</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection