<li class="message d-inline-flex right">
    <div class="message__aside">
        <a href="javascript:void(0)" class="avatar avatar-sm">
            @if(!empty(auth()->user()->avatar))
                <img src="{{ asset('/storage/avatars/' . auth()->user()->avatar ) }}"
                    alt="people" class="avatar-img rounded-circle">
            @else
                <span
                    class="avatar-title rounded-circle">{{ substr(auth()->user()->avatar, 0, 2) }}</span>
            @endif
        </a>
    </div>
    <div class="message__body card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex mr-3">
                    <a href="javascript:void(0)"
                        class="text-body"><strong>{{ auth()->user()->name }}</strong></a>
                </div>
                <div>
                    <small
                        class="text-50">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A | M d Y') }}</small>
                </div>
            </div>
            <span class="text-70">{{ $message->body }}</span>

        </div>
    </div>
</li>