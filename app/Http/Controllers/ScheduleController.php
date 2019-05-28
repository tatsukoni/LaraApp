<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Schedule;
use App\Candidate;
use App\Attend;
use App\Comment;
use Webpatser\Uuid\Uuid;

class ScheduleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //予定作成
    public function create() {
        return view('posts.create');
    }

    //予定保存
    public function store(Request $request) {
        $schedule = new Schedule();
        $comment = new Comment();
        $id = Auth::id();
        $scheduleId = Uuid::generate()->string;
        $array = explode("\r\n", $request->candidates);
        $schedule->scheduleId = $scheduleId;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $id;
        $schedule->save();
        $comment->scheduleId = $scheduleId;
        $comment->userId = $id;
        $comment->comment = 'コメントはありません';
        $comment->save();
        foreach($array as $data) {
            $candidate = new Candidate();
            $candidate->candidateName = $data;
            $candidate->scheduleId = $scheduleId;
            $candidate->save();
            $candidate->attend()->create([
                'userId' => $id,
                'attend' => '欠席',
                'scheduleId' => $scheduleId
            ]);
        }
        return redirect('/');
    }
    //予定詳細画面
    public function show($id) {
        $schedule = Schedule::findOrFail($id);
        $makeUser = Schedule::findOrFail($id)->user;
        $loginUser = User::findOrFail(Auth::id());
        $comment = Schedule::findOrFail($id)->comment;
        $attends = Schedule::findOrFail($id)->attends;
        $attendArray = [];
        foreach($attends as $attend) {
            $candidateId = $attend->candidateId;
            $candidate = Attend::findOrFail($candidateId)->candidate;
            $attendArray[$candidate->candidateName] = $attend->attend;
        }
        return view('posts.show')->with([
            "schedule" => $schedule,
            "makeUser" => $makeUser,
            "loginUser" => $loginUser,
            "comment" => $comment,
            "attendArray" => $attendArray
        ]);
    }
}
