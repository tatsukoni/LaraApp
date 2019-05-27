<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Schedule;
use App\Candidate;
use Webpatser\Uuid\Uuid;

class EditController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //予定編集画面
    public function edit($id) {
        $schedule = Schedule::findOrFail($id);
        $candidates = Schedule::findOrFail($id)->candidates;
        return view('posts.edit')->with([
            "schedule" => $schedule,
            "candidates" => $candidates
        ]);
    }

    //予定の更新
    public function update(Request $request, $id) {
        $schedule = Schedule::findOrFail($id);
        $userId = Auth::id();
        $array = explode("\r\n", $request->candidates);
        $schedule->scheduleId = $id;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $userId;
        $schedule->save();
        if ($array[0] !== "") {
            foreach($array as $data) {
                $candidate = new Candidate();
                $candidate->candidateName = $data;
                $candidate->scheduleId = $id;
                $candidate->save();
                $candidate->attend()->create([
                    'userId' => $userId,
                    'attendId' => 0,
                    'scheduleId' => $id
                ]);
            }
            return redirect('/');
        } else {
            return redirect('/');
        }
    }

    //予定の削除
    public function destroy($id) {
        $schedule = Schedule::findOrFail($id);
        $candidates = Schedule::findOrFail($id)->candidates;
        $schedule->delete();
        foreach($candidates as $candidate) {
            $candidate->delete();
        }
        return redirect('/');
    }
}
