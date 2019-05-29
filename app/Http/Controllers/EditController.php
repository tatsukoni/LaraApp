<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
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
    public function edit($scheduleId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $candidates = Schedule::findOrFail($scheduleId)->candidates;
        return view('posts.edit')->with([
            "schedule" => $schedule,
            "candidates" => $candidates
        ]);
    }

    //予定の更新
    public function update(EditRequest $request, $scheduleId) {
        $userId = Auth::id();
        $candidateArray = explode("\r\n", $request->candidates);
        //スケジュールテーブルを更新
        $schedule = Schedule::findOrFail($scheduleId);
        $schedule->scheduleId = $scheduleId;
        $schedule->scheduleName = $request->scheduleName;
        $schedule->memo = $request->memo;
        $schedule->createdBy = $userId;
        $schedule->save();
        //複数の候補日をcandidatesテーブルに保存
        if ($candidateArray[0] !== "") {
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
            return redirect('/');
        } else {
            return redirect('/');
        }
    }

    //予定の削除
    public function destroy($scheduleId) {
        $schedule = Schedule::findOrFail($scheduleId);
        $candidates = Schedule::findOrFail($scheduleId)->candidates;
        $attends = Schedule::findOrFail($scheduleId)->attends;
        $comment = Schedule::findOrFail($scheduleId)->comment;
        $schedule->delete();
        $comment->delete();
        foreach($candidates as $candidate) {
            $candidate->delete();
        }
        foreach($attends as $attend) {
            $attend->delete();
        }
        return redirect('/');
    }
}
