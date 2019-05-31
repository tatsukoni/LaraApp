<html>
<head>
  <title>予定詳細</title>
</head>
<body>

</body>
  <div>
    <h1>予定詳細</h1>
    <table border="1">
      <tr>
        <th>項目</th>
        <th>内容</th>
      </tr>
      <tr>
        <td>予定名</td>
        <td>{{ $schedule->scheduleName }}</td>
      </tr>
      <tr>
        <td>メモ</td>
        <td>{{ $schedule->memo }}</td>
      </tr>
      <tr>
        <td>作成者</td>
        <td>{{ $makeUser->name }}</td>
      </tr>
    </table>
    @if ($makeUser->id == $loginUser->id)
    <p><a href="/edit/{{ $schedule->scheduleId }}/user/{{ $makeUser->id }}">この予定を編集する</a></p>
    @endif
  </div>
  <div>
    <h1>出欠表</h1>
    <table border="1">
      <tr>
        <th>予定</th>
        <th>{{ $makeUser->name }}</th>
      </tr>
      @foreach ($attendArray as $candidateName => $attendValue)
      <tr>
        <td>{{ $candidateName }}</td>
        <td>{{ $attendValue }}</td>
      </tr>
      @endforeach
      <tr>
        <td>コメント</td>
        <td>{{ $comment->comment }}</td>
      </tr>
    </table>
    @if ($makeUser->id == $loginUser->id)
    <p><a href="/attend/{{ $schedule->scheduleId }}/user/{{ $makeUser->id }}">出欠を更新する</a></p>
    @endif
  </div>
  <p><a href="{{ url('/') }}">一覧に戻る</a></p>
</html>