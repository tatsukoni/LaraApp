<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
use Illuminate\Support\Facades\Auth;
use App\Schedule;
use App\Candidate;
use Webpatser\Uuid\Uuid;

const ATTEND_VALUE = '欠席';

class EditController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //予定編集画面
    public function edit($scheduleId) {
        $schedule = Schedule::with(['candidates'])
            ->findorFail($scheduleId);
        return view('posts.edit')->with([
            "schedule" => $schedule,
            "candidates" => $schedule->candidates
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
        if (!empty($candidateArray[0])) {
            foreach($candidateArray as $candidateStr) {
                $candidate = new Candidate();
                $candidate->candidateName = $candidateStr;
                $candidate->scheduleId = $scheduleId;
                $candidate->save();
                //候補日ごとの出欠情報を登録。attendカラムには初期値として欠席を代入
                $candidate->attends()->create([
                    'userId' => $userId,
                    'attend' => ATTEND_VALUE,
                    'scheduleId' => $scheduleId
                ]);
            }
            return redirect()->action(
                'ScheduleController@show', ['scheduleId' => $scheduleId]
            );
        } else {
            return redirect()->action(
                'ScheduleController@show', ['scheduleId' => $scheduleId]
            );
        }
    }

    //予定の削除
    public function destroy($scheduleId) {
        $schedule = Schedule::with([
            'candidates',
            'attends',
            'comments',
        ])
            ->findOrFail($scheduleId);
        
        $schedule->delete();
        foreach($schedule->comments as $comment) {
            $comment->delete();
        }
        foreach($schedule->candidates as $candidate) {
            $candidate->delete();
        }
        foreach($schedule->attends as $attend) {
            $attend->delete();
        }
        return redirect('/');
    }
}
