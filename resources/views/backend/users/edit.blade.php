@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Account</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="">Account</a>
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

            {!! Form::model($user, ['method' => 'PATCH', 'files' => true, 'route' => ['admin.users.update', $user->id]]) !!}

            <div class="form-group">
                <label class="form-label">Your photo</label>
                <div class="media align-items-center">
                    <a href="" class="media-left mr-16pt">
                        @if($user->avatar)
                        <img src="{{asset('/storage/avatars')}}/{{$user->avatar}}" id="user_avatar" alt="people" width="56" class="rounded-circle" />
                        @else
                        <img src="{{asset('/storage/avatars/no-avatar.jpg')}}" id="user_avatar" alt="people" width="56" class="rounded-circle" />
                        @endif
                    </a>
                    <div class="media-body">
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file">
                            <label class="custom-file-label" for="avatar_file">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Profile name</label>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>

            {!! Form::close() !!}
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script>
    $('#avatar_file').on('change', function() {
        var target = $('#user_avatar');
        display_image(this, target);
    });
</script>
@endpush