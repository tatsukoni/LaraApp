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
        $candidate = new Candidate();
        $id = Auth::id();
        $schedule->scheduleId = Uuid::generate()->string;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $id;
        $candidate->candidateName = $request->candidates;
        $candidate->scheduleId = Uuid::generate()->string;
        $schedule->save();
        $candidate->save();

        return redirect('/');
    }

    //記事詳細画面
    public function show($id) {
        $schedule = Schedule::findOrFail($id);
        return view('posts.show')->with('schedule', $schedule);
    }
}
