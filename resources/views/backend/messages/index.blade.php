@extends('layouts.app')

@section('content')

@push('after-styles')
<style>
[dir=ltr] #messages_content .message:nth-child(2n) .message__body {
    margin-left: inherit;
}
[dir=ltr] #messages_content .message:nth-child(2n) .message__aside {
    order: 0;
    margin-left: 0;
    margin-right: 1rem;
}
[dir=ltr] #messages_content .message.right .message__aside {
    order: 1;
    margin-right: 0;
    margin-left: 1rem;
}
[dir=ltr] #messages_content .message.right .message__body {
    margin-left: auto;
}
</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div data-push data-responsive-width="768px" data-has-scrollable-region data-fullbleed
        class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content" data-perfect-scrollbar>

            <div class="app-messages__container d-flex flex-column h-100 pb-4" style="min-height: 600px;">
                
                <div class="flex pt-4" style="position: relative;" data-perfect-scrollbar>
                    <div class="container page__container page__container" id="messages_content">
                        <h1 class="text-shadow text-center">Welcome {{ auth()->user()->name }}</h1>
                        <h2 class="text-center">Click Partner</h2>
                    </div>
                </div>
                <div class="container page__container page__container">
                    <form method="post" action="{{route('admin.messages.reply')}}" id="message_reply">@csrf
                        <div class="input-group input-group-merge">
                            <input type="text" name="message" class="form-control form-control-appended" autofocus="" required=""
                                placeholder="Type message">
                            <div class="input-group-append">
                                <div class="input-group-text pr-2">
                                    <button class="btn btn-flush" type="button"><i
                                            class="material-icons">tag_faces</i></button>
                                </div>
                                <div class="input-group-text pl-0">
                                    <div class="custom-file custom-file-naked d-flex"
                                        style="width: 24px; overflow: hidden;">
                                        <input type="file" class="custom-file-input" id="customFile">
                                        <label class="custom-file-label" style="color: inherit;" for="customFile">
                                            <i class="material-icons">attach_file</i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="">
                        <button id="stop" class="btn btn-primary">Stop</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mdk-drawer js-mdk-drawer" data-align="end" id="messages-drawer">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-right sidebar-light bg-white o-hidden">
                    <div class="d-flex flex-column h-100">

                        <div class="d-flex px-3 pt-4 pb-3 border-bottom-1">
                            <div class="mr-3">
                                <div class="avatar avatar-online avatar-sm">
                                    @if(!empty(auth()->user()->avatar))
                                    <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar ) }}" alt="people"
                                        class="avatar-img rounded-circle">
                                    @else
                                    <span class="avatar-title rounded-circle">{{ substr(auth()->user()->avatar, 0, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column" style="max-width: 150px; font-size: 15px">
                                <strong class="text-body">{{ auth()->user()->name }}</strong>
                                <span class="text-50 text-ellipsis">{{ auth()->user()->headline }}</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column justify-content-center navbar-height">
                            <div class="px-3 form-group mb-0">
                                <div class="input-group input-group-merge input-group-rounded flex-nowrap">
                                    <input type="text" id="filter" class="form-control form-control-prepended"
                                        placeholder="Filter members">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="material-icons">filter_list</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex" data-perfect-scrollbar>

                            <ul id="filter_result" class="list-group list-group-flush mb-3"></ul>

                            <div class="sidebar-heading">Recent Chats</div>
                            <ul id="recent_chats" class="list-group list-group-flush mb-3">

                            @if($threads->count() > 0)
                                @foreach($partners as $key=>$item)

                                @php $contact_user = Auth::user()->where('id', $key)->first(); @endphp

                                <li class="list-group-item px-3 py-12pt bg-light" data-id="{{ $key }}" data-thread="{{ $item->id }}">
                                    <a href="javascript:void(0)" class="d-flex align-items-center position-relative">
                                        <span class="avatar avatar-xs avatar-online mr-3 flex-shrink-0">
                                            
                                            @if(!empty($contact_user->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $contact_user->avatar) }}" alt="" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ substr($contact_user->avatar, 0, 2) }}</span>
                                            @endif

                                        </span>
                                        <span class="flex d-flex flex-column" style="max-width: 175px;">
                                            <strong class="text-body">{{ $contact_user->name }}</strong>
                                            <span class="text-muted text-ellipsis">
                                                {{ $contact_user->headline }}
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                @endforeach
                            @endif

                            </ul>
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

    var partner_id = '';
    var thread_id = '';

    $('#message_reply').on('submit', function(e) {

        e.preventDefault();
        
        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'thread_id',
                    type: 'text',
                    value: thread_id
                });

                formData.push({
                    name: 'recipients',
                    type: 'text',
                    value: partner_id
                });
            },
            success: function(res) {
                if(res.success) {
                    if(res.action == 'send') {
                        thread_id = res.thread_id;
                    }
                    console.log(res);
                    $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                    $('input[name="message"]').val('');
                }
            }
        });
    });

    $('#filter').on('keypress', function(e) {

        if(e.which == 13 && $(this).val() !== '') {

            $.ajax({
                method: 'GET',
                url: "/dashboard/messages/users/" + $(this).val(),
                success: function(res) {

                    if (res.success) {
                        $('#filter_result').html('');
                        $(res.html).hide().appendTo('#filter_result').toggle(500);
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        }
    });

    $('.sidebar').on('click', '#filter_result li, #recent_chats li', function(e) {

        var old_partner = partner_id;

        partner_id = $(this).attr('data-id');
        thread_id = $(this).attr('data-thread');

        $('#filter_result li, #recent_chats li').removeClass('bg-primary-light');
        $(this).removeClass('bg-light');
        $(this).addClass('bg-primary-light');

        // Load Message
        if(partner_id != '' && partner_id != old_partner ) {
            loadMessage(partner_id, thread_id);
        }
    });

    function loadMessage(partner, thread) {

        $.ajax({
            method: 'GET',
            url: "/dashboard/messages/get?partner=" + partner + "&thread=" + thread,
            success: function(res) {

                if (res.success) {
                    $('#messages_content').html('');
                    $(res.html).hide().appendTo('#messages_content').toggle(500);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    }

    $('#stop').on('click', function(e) {
        clearInterval(x);
    });

    var x = setInterval(function() {

        if(partner_id != '' && thread_id != '') {
            
            $.ajax({
                method: 'GET',
                url: "/dashboard/messages/last?partner=" + partner_id + "&thread=" + thread_id,
                success: function(res) {

                    if (res.success) {
                        $(res.html).hide().appendTo('#messages_content').toggle(500);
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        }

    }, 3000);


    // setTimeout(() => {

    //     if(partner_id != '' && thread_id != '') {
            
    //         $.ajax({
    //             method: 'GET',
    //             url: "/dashboard/messages/last?partner=" + partner_id + "&thread=" + thread_id,
    //             success: function(res) {

    //                 if (res.success) {
    //                     $(res.html).hide().appendTo('#messages_content').toggle(500);
    //                 }
    //             },
    //             error: function(err) {
    //                 var errMsg = getErrorMessage(err);
    //                 console.log(errMsg);
    //             }
    //         });
    //     }

    // }, 3000);

});
</script>

@endpush

@endsection