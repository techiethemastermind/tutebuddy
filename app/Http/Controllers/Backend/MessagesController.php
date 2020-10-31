<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

use App\User;

use App\Models\Course;
use DB;

class MessagesController extends Controller
{
    public function index(Request $request) {

        $userId = auth()->user()->id;
        $threads = Thread::where('subject', 'like', '%' . $userId . '%')->latest('updated_at')->get();
        $partners = [];

        foreach($threads as $thread) {
            $grouped_participants = $thread->participants->where('user_id', '!=', $userId)->groupBy(function($item) {
                return $item->user_id;
            });
            
            foreach($grouped_participants as $participants) {
                $participant = $participants[0];

                $item = [
                    'partner_id' => $participant->user_id,
                    'thread' => $thread
                ];
                array_push($partners, $item);
            }
        }

        return view('backend.messages.index', compact('threads', 'partners'));
    }

    public function reply(Request $request)
    {

        $this->validate($request,[
            'message' => 'required'
        ],[
            'message.required' => 'Please input your message'
        ]);

        $userId = auth()->user()->id;

        $thread = auth()->user()->threads()
            ->where('message_threads.id', '=', $request->thread_id)
            ->first();

        // Replay to Thread
        if(!empty($thread)) {

            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);

            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'last_read' => new Carbon,
            ]);

            $view = view('backend.messages.parts.ele-right', ['message' => $message])->render();

            return response()->json([
                'success' => true,
                'action' => 'reply',
                'html' => $view
            ]);

        } else { // Create New Thread

            $subject = $userId . '_' . $request->recipients;

            $thread = Thread::create([
                'subject' => $subject,
            ]);
    
            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'body' => $request->message,
            ]);
    
            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => $userId,
                'last_read' => new Carbon,
            ]);
    
            // Recipients
            if ($request->has('recipients')) {
                $thread->addParticipant($request->recipients);
            }

            $view = view('backend.messages.parts.ele-right', ['message' => $message])->render();
    
            return response()->json([
                'success' => true,
                'action' => 'send',
                'thread_id' => $thread->id,
                'html' => $view
            ]);
        }
    }

    public function getMessages(Request $request)
    {
        $partner = User::find($request->partner);
        $thread = Thread::find($request->thread);

        if(!empty($thread)) {
            $thread->markAsRead(auth()->user()->id);
        }
        
        $view = view('backend.messages.parts.msg', ['partner' => $partner, 'thread' => $thread])->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ]);
    }

    public function getUsers($key)
    {
        if($key == '') {
            return response()->json([
                'success' => false
            ]);
        }
        $user_id = auth()->user()->id;

        if(auth()->user()->hasRole('Instructor')) {
            $course_ids = DB::table('course_user')->where('user_id', $user_id)->pluck('course_id');
            $student_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id');
            $users = User::whereIn('id', $student_ids)->where('name', 'like', '%' . $key . '%')->get();
        }

        if(auth()->user()->hasRole('Student')) {
            $course_ids = DB::table('course_student')->where('user_id', $user_id)->pluck('course_id');
            $teacher_ids = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
            $users = User::whereIn('id', $teacher_ids)->where('name', 'like', '%' . $key . '%')->get();
        }

        if(auth()->user()->hasRole('Administrator')) {
            $users = User::where('name', 'like', '%' . $key . '%')->get();
        }

        // Thread
        $threads = Thread::forUser($user_id)->latest('updated_at')->get();
        $partners = [];
        foreach($threads as $thread) {
            $partner = $thread->participants->where('user_id', '!=', $user_id)->first();
            if(isset($partner)){
                $partners += [$partner->user_id => $thread->id];
            }
        }

        $li = '';

        foreach($users as $user) {
            if($user->id == auth()->user()->id) continue;
            if(!empty($user->avatar)) {
                $avatar = '<img src="' . asset('/storage/avatars/' . $user->avatar ) . '" alt="people" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">' . substr(auth()->user()->avatar, 0, 2) .'</span>';
            }

            // Get Thread ID for User
            $thread_id = (isset($partners[$user->id])) ? $partners[$user->id] : '';
            
            $li .= '<li class="list-group-item px-3 py-12pt bg-light" data-id="' . $user->id . '" data-thread="'. $thread_id .'">
                        <a href="javascript:void(0)" class="d-flex align-items-center position-relative">
                            <span class="avatar avatar-xs avatar-online mr-3 flex-shrink-0">'. $avatar .'</span>
                            <span class="flex d-flex flex-column" style="max-width: 175px;">
                                <strong class="text-body">'. $user->name .'</strong>
                                <span class="text-muted text-ellipsis">'. $user->headline .'</span>
                            </span>
                        </a>
                    </li>';
        }

        if($li == '') {
            $li = '<li class="list-group-item px-3 py-12pt bg-light">Not found</li>';
        }

        return response()->json([
            'success' => true,
            'html' => $li
        ]);
    }

    public function lastMessages(Request $request) {

        $userId = auth()->user()->id;
        $partner = User::find($request->partner);
        $thread = Thread::find($request->thread);

        try {
            $participant = $thread->getParticipantFromUser($userId);
        } catch (ModelNotFoundException $e) {
            return collect();
        }

        $messages = $thread->messages()->where('user_id', '!=', $userId)->get();
        $thread->markAsRead($userId);

        $view = '';

        foreach($messages as $message) {
            if($message->updated_at->gt($participant->last_read->toDateTimeString())) {
                $view .= view('backend.messages.parts.ele-left', ['partner' => $partner, 'message' => $message]);
            }
        }

        return response()->json([
            'success' => true,
            'action' => 'read',
            'html' => $view
        ]);
    }

    function getUnreadMessagesCount($thread) {
        $userId = auth()->user()->id;
        $messages = $thread->messages()->where('user_id', '!=', $userId)->get();
        $participant = $thread->getParticipantFromUser($userId);
        $count = 0;
        foreach($messages as $message) {
            if($message->updated_at->gt($participant->last_read->toDateTimeString())) {
                $count++;
            }
        }
        return $count;
    }
}
