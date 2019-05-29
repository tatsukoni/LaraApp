<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
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
    public function store(ScheduleRequest $request) {
        $userId = Auth::id();
        $scheduleId = Uuid::generate()->string;
        $candidateArray = explode("\r\n", $request->candidates);
        //スケジュールテーブルにデータを保存
        $schedule = new Schedule();
        $schedule->scheduleId = $scheduleId;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $userId;
        $schedule->save();
        //コメントテーブルにデータを保存
        $comment = new Comment();
        $comment->scheduleId = $scheduleId;
        $comment->userId = $userId;
        $comment->comment = 'コメントはありません';
        $comment->save();
        //複数の候補日をcandidatesテーブルに保存
        foreach($candidateArray as $candidateStr) {
            $candidate = new Candidate();
            $candidate->candidateName = $candidateStr;
            $candidate->scheduleId = $scheduleId;
            $candidate->save();
            //候補日ごとの出欠情報を登録。attendカラムには初期値として欠席を代入
            $candidate->attend()->create([
                'userId' => $userId,
                'attend' => '欠席',
                'scheduleId' => $scheduleId
            ]);
        }
        return redirect()->action(
            'ScheduleController@show', ['scheduleId' => $scheduleId]
        );
    }
    //予定詳細画面
    public function show($scheduleId) {
        $userId = Auth::id();
        $schedule = Schedule::findOrFail($scheduleId);
        $makeUser = Schedule::findOrFail($scheduleId)->user;
        $loginUser = User::findOrFail($userId);
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
        return view('posts.show')->with([
            "schedule" => $schedule,
            "makeUser" => $makeUser,
            "loginUser" => $loginUser,
            "comment" => $comment,
            "attendArray" => $attendArray
        ]);
    }
}
