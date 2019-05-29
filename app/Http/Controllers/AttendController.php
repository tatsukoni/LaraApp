<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendRequest;
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
    public function attend($scheduleId, $userId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $user = User::findOrFail($userId);
        $comment = Schedule::findOrFail($scheduleId)->comment;
        $attends = Schedule::findOrFail($scheduleId)->attends;
        //$attendArray：候補日と出欠情報を紐づける連想配列
        //key：候補日　value：出欠
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
    public function update(AttendRequest $request, $scheduleId, $userId) {
        //コメントの更新
        if (isset($request->comment)) {
            $comment = Comment::where('scheduleId', $scheduleId)->where('userId', $userId)->first();
            $comment->update(['comment' => $request->comment]);
        }
        //出欠情報の更新
        $attends = Attend::where('scheduleId', $scheduleId)->where('userId', $userId)->get();
        $count = 1;
        foreach($attends as $attend) {
            $attend->update(['attend' => $request->$count]);
            $count++;
        }
        return redirect('/');
    }
}
