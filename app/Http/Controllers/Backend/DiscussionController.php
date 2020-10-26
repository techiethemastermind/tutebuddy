<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Discussion;
use App\Models\DiscussionResults;
use App\Models\Course;

class DiscussionController extends Controller
{
    public function index()
    {
        return view('backend.discussions.index');
    }

    public function getTopicsByAjax()
    {
        $discussions = Discussion::where('user_id', auth()->user()->id)->get();
        $data = [];
        foreach($discussions as $item) {
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($item->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $item->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';

            $topics = json_decode($item->topics);
            $temp['topics'] = '';
            foreach($topics as $topic){
                $temp['topics'] .= '<a href=' . route('admin.discussions.show', $item->id) . ' class="chip chip-outline-secondary">
                    ' . $item->topic($topic) . '
                </a>';
            }

            $edit_route = route('admin.discussions.edit', $item->id);
            $delete_route = route('admin.discussions.destroy', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getTopics()
    {
        $discussions = Discussion::paginate(5);
        return view('backend.discussions.topics', compact('discussions'));
    }

    /**
     * Create a new discussion
     */
    public function create()
    {
        $courses = Course::all();
        $topics = DB::table('discussion_topics')->get();
        return view('backend.discussions.create', compact('courses', 'topics'));
    }

    /**
     * Store a new discussion
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $question_data = [
            'user_id' => auth()->user()->id,
            'course_id' => $data['course'],
            'lesson_id' => $data['lesson'],
            'title' => $data['title'],
            'question' => $data['question']
        ];

        // Set Topics
        $topics = $data['topics'];
        foreach($topics as $item) {
            $count = DB::table('discussion_topics')->where('id', $item)->count();
            if($count < 1) {
                $topic_id = DB::table('discussion_topics')->insertGetId(['topic' => $item]);
                if (($key = array_search($item, $data['topics'])) !== false) {
                    unset($data['topics'][$key]);
                }
                array_push($data['topics'], $topic_id);
            }
        }
        $data['topics'] = array_values($data['topics']);

        $question_data['topics'] = json_encode($data['topics']);

        $discusson = Discussion::create($question_data);

        return response()->json([
            'success' => true,
            'discussion_id' => $discusson->id
        ]);
    }

    public function edit($id)
    {
        $courses = Course::all();
        $discussion = Discussion::find($id);
        $topics = DB::table('discussion_topics')->get();
        return view('backend.discussions.edit', compact('discussion', 'courses', 'topics'));
    }

    public function update(Request $request, $id)
    {
        $discussion = Discussion::find($id);
        $data = $request->all();

        $question_data = [
            'user_id' => auth()->user()->id,
            'course_id' => $data['course'],
            'lesson_id' => $data['lesson'],
            'title' => $data['title'],
            'question' => $data['question']
        ];

        // Set Topics
        $topics = $data['topics'];
        foreach($topics as $item) {
            $count = DB::table('discussion_topics')->where('id', $item)->count();
            if($count < 1) {
                $topic_id = DB::table('discussion_topics')->insertGetId(['topic' => $item]);
                if (($key = array_search($item, $data['topics'])) !== false) {
                    unset($data['topics'][$key]);
                }
                array_push($data['topics'], $topic_id);
            }
        }
        $data['topics'] = array_values($data['topics']);
        $question_data['topics'] = json_encode($data['topics']);
        $discussion->update($question_data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Updated'
        ]);
    }

    public function destroy($id) {

        try {
            Discussion::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $discussion = Discussion::find($id);
        $top_discussions = Discussion::withCount('results')
                ->orderBy('results_count', 'desc')->limit(5)->get();
        return view('backend.discussions.show', compact('discussion', 'top_discussions'));
    }

    public function postComment(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        
        $result = DiscussionResults::create($data);

        if(!empty($result->user->avatar)){
            $avatar = '<img src="' . asset('/storage/avatars/' . $result->user->avatar) . '" alt="people" class="avatar-img rounded-circle">';
        } else {
            $avatar = '<span class="avatar-title rounded-circle">' . substr($result->user->avatar, 0, 2) . '</span>';
        }

        $html = '<div class="d-flex mb-3">
                    <a href="" class="avatar avatar-sm mr-12pt">'. $avatar .'</a>
                    <div class="flex">
                        <a href="" class="text-body"><strong>' . $result->user->name . '</strong></a><br>
                        <p class="mt-1 text-70">' . $result->content . '</p>
                        <div class="d-flex align-items-center">
                            <small class="text-50 mr-2">' . Carbon::createFromTimeStamp(strtotime($result->updated_at))->diffForHumans() .'</small>
                            <a href="" class="text-50"><small>Liked</small></a>
                        </div>
                    </div>
                </div>';
                
        return response()->json([
            'success' => true,
            'result' => $html
        ]);
    }
}
