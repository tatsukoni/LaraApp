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
        <td>{{ $user->name }}</td>
      </tr>
    </table>
    <p><a href="{{ url('/edit', $schedule->scheduleId) }}">この予定を編集する</a></p>
  </div>
  <div>
    <h1>出欠表</h1>
    <table border="1">
      <tr>
        <th>予定</th>
        <th>{{ $user->name }}</th>
      </tr>
      <tr>
        <td>ダミー</td>
        <td>ダミー</td>
      </tr>
    </table>
    <div>
      @foreach ($candidates as $candidate)
        <p>{{ $candidate->candidateName }}</p>
      @endforeach
    </div>
    <div>
      @foreach ($attends as $attend)
        <p>{{ $attend->attendId }}</p>
      @endforeach
    </div>
  </div>
</html>