<html>
<head>
  <title>予定調整くん</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <h1>予定の編集</h1>
  <form method="post" action="{{ url('/edit', $schedule->scheduleId) }}">
    {{ csrf_field() }}
    {{ method_field('patch') }}
    <p>予定名</p>
    <p><input type="text" name="scheduleName" value="{{ $schedule->scheduleName }}"></p>
    <p>メモ</p>
    <p><input type="text" name="memo" value="{{ $schedule->memo }}"></p>
    <p>既存の日程候補</p>
    @foreach ($candidates as $candidate)
      <p>・{{ $candidate->candidateName }}</p>
    @endforeach
    <p>候補日程の追加 (改行して複数入力してください)</p>
    <p><textarea name="candidates" placeholder="日程を入力してください"></textarea></p>
    <p><input type="submit" value="以上の内容で予定を編集する"></p>
  </form>
  <h1>危険な変更</h1>
  <form method="post" action="{{ url('/delete', $schedule->scheduleId) }}">
    {{ csrf_field() }}
    {{ method_field('delete') }}
    <p><input type="submit" value="この予定を削除する"></p>
  </form>
</body>
</html>