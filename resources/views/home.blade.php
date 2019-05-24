@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">予定調整くん</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        予定調整くんは、GitHubで認証でき、予定を作って出欠が取れるサービスです
                </div>
            </div>
        </div>
    </div>
    <div>
        <p><a href="{{ url('/create') }}">予定を作る</a></p>
    </div>
    <div>
        <h1>あなたの作った予定一覧</h1>
        <table border="1">
            <tr>
                <th>予定名</th>
                <th>更新日時</th>
            </tr>
            @foreach ($schedules as $schedule)
            <tr>
                <th><a href="{{ url('/schedules', $schedule->scheduleId) }}">{{ $schedule->scheduleName }}</a></th>
                <th>{{ $schedule->updated_at }}</th>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
