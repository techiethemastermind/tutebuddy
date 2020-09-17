@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

<div class="container page__container">
    <div class="page-section">

        <div class="row">
            <div class="col-md-8">

                <h1 class="h2 measure-lead-max mb-2">{{ $discussion->title }}</h1>
                <p class="text-muted d-flex align-items-center mb-lg-32pt">
                    <a href="{{ route('admin.discussions.index') }}" class="mr-3">Back to Community</a>
                    <a href="{{ route('admin.discussions.edit', $discussion->id) }}" class="text-50" style="text-decoration: underline;">Edit</a>
                </p>

                <div class="card card-body">
                    <div class="d-flex">
                        <a href="" class="avatar avatar-sm avatar-online mr-12pt">
                            @if(!empty($discussion->user->avatar))
                            <img src="{{ asset('/storage/avatars/' . $discussion->user->avatar) }}" alt="people" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ substr($discussion->user->name, 0, 2) }}</span>
                            @endif
                        </a>
                        <div class="flex">
                            <p class="d-flex align-items-center mb-2">
                                <a href="" class="text-body mr-2"><strong>{{ $discussion->user->name }}</strong></a>
                                <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->updated_at))->diffForHumans() }}</small>
                            </p>
                            <p>{{ $discussion->question }}</p>
                            <div class="d-flex align-items-center">
                                <a href="" class="text-50 d-flex align-items-center text-decoration-0">
                                    <i class="material-icons mr-1" style="font-size: inherit;">favorite_border</i> 30</a>
                                <a href="" class="text-50 d-flex align-items-center text-decoration-0 ml-3">
                                    <i class="material-icons mr-1" style="font-size: inherit;">thumb_up</i> 130</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <a href="" class="avatar avatar-sm mr-12pt">
                        @if(!empty(auth()->user()->avatar))
                        <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar) }}" alt="people" class="avatar-img rounded-circle">
                        @else
                        <span class="avatar-title rounded-circle">{{ substr(auth()->user()->name, 0, 2) }}</span>
                        @endif
                    </a>
                    <div class="flex">
                        <form id="frm_commet" method="post" action="{{ route('admin.ajax.postComment') }}">@csrf
                            <div class="form-group">
                                <label class="form-label">Your reply</label>
                                <textarea class="form-control" name="content" rows="8" placeholder="Type here to reply to Matney ..."></textarea>
                            </div>
                            <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                            <input type="hidden" name="post_user_id" value="{{ $discussion->user->id }}">
                            <button type="submit" class="btn btn-outline-secondary">Post comment</button>
                        </form>
                    </div>
                </div>

                <div id="comments" class="pt-3">
                    <h4>{{ $discussion->results->count() }} Comments</h4>

                    @foreach($discussion->results as $result)
                    <div class="d-flex mb-3">
                        <a href="" class="avatar avatar-sm mr-12pt">
                            @if(!empty($result->user->avatar))
                            <img src="{{ asset('/storage/avatars/' . $result->user->avatar) }}" alt="people" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ substr($result->user->avatar, 0, 2) }}</span>
                            @endif
                        </a>
                        <div class="flex">
                            <a href="" class="text-body"><strong>{{ $result->user->name }}</strong></a><br>
                            <p class="mt-1 text-70">{{ $result->content }}</p>
                            <div class="d-flex align-items-center">
                                <small class="text-50 mr-2">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($result->updated_at))->diffForHumans() }}</small>
                                <a href="" class="text-50"><small>Liked</small></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">

                <div class="page-separator">
                    <div class="page-separator__text">Top Contributors</div>
                </div>
                <p class="text-70 mb-24pt">People who started the most discussions on Luma.</p>



                <div class="mb-4">

                    @foreach($top_discussions as $item)
                    <div class="d-flex align-items-center mb-2">
                        <a href="" class="avatar avatar-xs mr-8pt">
                            @if(!empty($item->user->avatar))
                            <img src="{{ asset('/storage/avatars/' . $item->user->avatar) }}" alt="" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ substr($item->user->avatar, 0, 2) }}</span>
                            @endif
                        </a>
                        <a href="" class="flex mr-2 text-body"><strong>{{ $item->user->name }}</strong></a>
                        <span class="text-70 mr-2">{{ $item->results->count() }}</span>
                        <i class="text-muted material-icons font-size-16pt">forum</i>
                    </div>
                    @endforeach
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

        $('#frm_commet').on('submit', function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
                success: function(res) {
                    $(res.result).hide().appendTo($('#comments')).toggle(500);
                }
            });
        });
    });
</script>
@endpush

@endsection