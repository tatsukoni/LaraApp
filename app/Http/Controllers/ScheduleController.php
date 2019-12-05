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
        // 候補日の日程を、改行区切りで配列にする
        $candidates = explode("\r\n", $request->candidates);

        DB::transaction(function () use ($request, $userId, $scheduleId, $candidates) {
            //スケジュールテーブルにデータを保存
            $schedule = new Schedule();
            $schedule->scheduleId = $scheduleId;
            $schedule->scheduleName = $request->scheduleName;
            $schedule->memo = $request->memo;
            $schedule->createdBy = $userId;
            $schedule->save();

            //コメントテーブルにデータを保存
            //commentカラムには初期値として「コメントはありません」と代入
            $comment = new Comment();
            $comment->scheduleId = $scheduleId;
            $comment->userId = $userId;
            $comment->comment = 'コメントはありません';
            $comment->save();

            //複数の候補日をcandidatesテーブルに保存
            foreach($candidates as $candidate) {
                $candidate = new Candidate();
                $candidate->candidateName = $candidate;
                $candidate->scheduleId = $scheduleId;
                $candidate->save();

                //候補日ごとの出欠情報を登録。attendカラムには初期値として欠席を代入
                $candidate->attends()->create([
                    'userId' => $userId,
                    'attend' => '欠席',
                    'scheduleId' => $scheduleId
                ]);
            }
        });
        return redirect()->action(
            'ScheduleController@show', ['scheduleId' => $scheduleId]
        );
    }
    
    //予定詳細画面
    public function show($scheduleId) {
        $schedule = Schedule::with([
            'user',
            'candidates',
            'attends',
        ])
            ->findOrFail($scheduleId);
        
        //$attendArray = [ユーザー名, 出欠情報]
        //$candidateArray = [候補日, $attendArray]
        foreach($schedule->candidates as $candidate) {
            foreach($candidate->attends as $attend) {
                $userName = User::find($attend->userId)->name;
                $attends[$userName] = $attend->attend;
            }
            $candidates[$candidate->candidateName] = $attends;
        }

        //ユーザーが出欠登録しているかを判断する変数$valueSet
        $valueSet = 'false';

        return view('posts.show')->with([
            "schedule" => $schedule,
            "makeUser" => $schedule->user,
            "loginUser" => Auth::user(),
            "comments" => $schedule->comments,
            "attends" => $schedule->attends,
            "candidates" => $candidates,
            "valueSet" => $valueSet
        ]);
    }
}
