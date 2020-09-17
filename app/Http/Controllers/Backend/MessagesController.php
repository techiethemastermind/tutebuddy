<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;

class MessagesController extends Controller
{
    public function index(Request $request) {

        $thread="";
        $teachers = User::role('Instructor')->get()->pluck('name', 'id');
        
        // auth()->user()->load('threads.messages.sender');
        $unreadThreads = [];
        $threads = [];
        foreach(auth()->user()->threads as $item) {
            if($item->unreadMessagesCount > 0) {
                $unreadThreads[] = $item;
            } else {
                $threads[] = $item;
            }
        }
        $threads = Collection::make(array_merge($unreadThreads,$threads));

        if(request()->has('thread') && ($request->thread != null)) {

            if(request('thread')) {
                $thread = auth()->user()->threads()
                    ->where('message_threads.id','=',$request->thread)
                    ->first();

                //Read Thread
                auth()->user()->markThreadAsRead($thread->id);
            }else if($thread == ""){
                abort(404);
            }
        }

        return view('backend.messages.index', [
            'threads' => auth()->user()->threads,
            'threads' => $threads,
            'teachers' => $teachers,
            'thread' => $thread
        ]);
    }
}
