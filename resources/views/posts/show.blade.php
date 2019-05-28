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
    <p><a href="{{ url('/edit', $schedule->scheduleId) }}">この予定を編集する</a></p>
  </div>
  <div>
    <h1>出欠表</h1>
    <table border="1">
      <tr>
        <th>予定</th>
        <th>{{ $loginUser->name }}</th>
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
    <p><a href="/attend/{{ $schedule->scheduleId }}/user/{{ $loginUser->id }}">出欠を更新する</a></p>
  </div>
  <p><a href="{{ url('/') }}">一覧に戻る</a></p>
</html>