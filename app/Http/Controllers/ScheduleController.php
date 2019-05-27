<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Schedule;
use App\Candidate;
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
        $id = Auth::id();
        $scheduleId = Uuid::generate()->string;
        $array = explode("\r\n", $request->candidates);
        $schedule->scheduleId = $scheduleId;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $id;
        $schedule->save();
        foreach($array as $data) {
            $candidate = new Candidate();
            $candidate->candidateName = $data;
            $candidate->scheduleId = $scheduleId;
            $candidate->save();
            $candidate->attend()->create([
                'userId' => $id,
                'attendId' => 0,
                'scheduleId' => $scheduleId
            ]);
        }
        return redirect('/');
    }
    //予定詳細画面
    public function show($id) {
        $schedule = Schedule::findOrFail($id);
        $user = Schedule::findOrFail($id)->user;
        $candidates = Schedule::findOrFail($id)->candidates;
        $attends = Schedule::findOrFail($id)->attends;
        return view('posts.show')->with([
            "schedule" => $schedule,
            "user" => $user,
            "candidates" => $candidates,
            "attends" => $attends
        ]);
    }
}
