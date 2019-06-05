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
            $candidate->attends()->create([
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
        $makeUser = $schedule->user;
        $loginUser = User::findOrFail($userId);
        $comments = Comment::where('scheduleId', $scheduleId)->get();
        $candidates = $schedule->candidates;
        //$candidateArray[]
        //key => 候補日の日程, value => $attendArray[]
        //$attendArray[]
        //key => ユーザー名, value => ユーザーごとの出欠情報
        $candidateArray = [];
        foreach($candidates as $candidate) {
            $attends = $candidate->attends;
            $attendArray = [];
            foreach($attends as $attend) {
                $attendUser = User::findOrFail($attend->userId)->name;
                $attendArray[$attendUser] = $attend->attend;
            }
            $candidateArray[$candidate->candidateName] = $attendArray;
        }
        //ユーザーが出欠登録しているかを判断する変数$valueSet
        $valueSet = 'false';
        return view('posts.show')->with([
            "schedule" => $schedule,
            "makeUser" => $makeUser,
            "loginUser" => $loginUser,
            "comments" => $comments,
            "attends" => $attends,
            "candidateArray" => $candidateArray,
            "valueSet" => $valueSet
        ]);
    }
}
