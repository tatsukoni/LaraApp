<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Schedule;
use App\Attend;
use App\Comment;
use Webpatser\Uuid\Uuid;

class AttendController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //出欠の編集画面
    public function attend($id, $user) {
        $schedule = Schedule::findOrFail($id);
        $user = User::findOrFail($user);
        $comment = Schedule::findOrFail($id)->comment;
        $attends = Schedule::findOrFail($id)->attends;
        $attendArray = [];
        foreach($attends as $attend) {
            $candidateId = $attend->candidateId;
            $candidate = Attend::findOrFail($candidateId)->candidate;
            $attendArray[$candidate->candidateName] = $attend->attend;
        }
        return view('posts.attend')->with([
            "schedule" => $schedule,
            "user" => $user,
            "comment" => $comment,
            "attendArray" => $attendArray
        ]);
    }

    //出欠の更新
    public function update(Request $request, $id, $user) {
        $attends = Attend::where('scheduleId', $id)->where('userId', $user)->get();
        $comment = Comment::where('scheduleId', $id)->where('userId', $user)->first();
        $comment->update(['comment' => $request->comment]);
        $count = 1;
        foreach($attends as $attend) {
            $attend->update(['attend' => $request->$count]);
            $count++;
        }
        return redirect('/');
    }
}
