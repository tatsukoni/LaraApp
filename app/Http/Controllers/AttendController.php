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
        if ($userId == Auth::id()) {
            $schedule = Schedule::findOrFail($scheduleId);
            $user = User::findOrFail($userId);
            $comment = Comment::where('scheduleId', $scheduleId)->where('userId', $userId)->first();
            $attends = Attend::where('scheduleId', $scheduleId)->where('userId', $userId)->get();
            //$attendArray：候補日と出欠情報を紐づける連想配列
            //key：候補日　value：出欠
            $attendArray = [];
            foreach($attends as $attend) {
                $candidateId = $attend->candidateId;
                $candidate = $attend->candidate;
                $attendArray[$candidate->candidateName] = $attend->attend;
            }   
            return view('posts.attend')->with([
                "schedule" => $schedule,
                "user" => $user,
                "comment" => $comment,
                "attendArray" => $attendArray
            ]);
        } else {
            return redirect('/');
        }
    }

    //出欠の新規登録画面
    public function attendCreate($scheduleId, $userId) {
        if ($userId == Auth::id()) {
            $schedule = Schedule::findOrFail($scheduleId);
            $user = User::findOrFail($userId);
            $candidates = $schedule->candidates;
            return view('posts.attendCreate')->with([
                "schedule" => $schedule,
                "user" => $user,
                "candidates" => $candidates
            ]);
        } else {
            return redirect('/');
        }
    }

    //出欠の更新
    public function update(AttendRequest $request, $scheduleId) {
        $userId = Auth::id();
        //コメントの更新
        if (isset($request->comment)) {
            Comment::where('userId', $userId)
                    ->where('scheduleId', $scheduleId)
                    ->update(['comment' => $request->comment]);
        }
        //出欠情報の更新
        $count = 1;
        $attends = Attend::where('userId', $userId)
                ->where('scheduleId', $scheduleId)
                ->get();
        foreach($attends as $attend) {
            $attend->update(['attend' => $request->$count]);
            $count += 1;
        }
        return redirect()->action(
            'ScheduleController@show', ['scheduleId' => $scheduleId]
        );
    }

    //出欠の新規登録
    public function create(AttendRequest $request, $scheduleId) {
        $userId = Auth::id();
        $schedule = Schedule::findOrFail($scheduleId);
        $candidates = $schedule->candidates;
        //コメントの登録
        $comment = new Comment();
        $comment->scheduleId = $scheduleId;
        $comment->userId = $userId;
        if (isset($request->comment)) {
            $comment->comment = $request->comment;
        } else {
            $comment->comment = 'コメントはありません';
        }
        $comment->save();
        //出欠情報の新規登録
        $count = 1;
        foreach($candidates as $candidate) {
            $attend = new Attend();
            $attend->candidateId = $candidate->candidateId;
            $attend->userId = $userId;
            $attend->attend = $request->$count;
            $attend->scheduleId = $scheduleId;
            $attend->save();
            $count++;
        }
        return redirect()->action(
            'ScheduleController@show', ['scheduleId' => $scheduleId]
        );
    }
}
