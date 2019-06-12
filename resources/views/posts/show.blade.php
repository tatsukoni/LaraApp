<html>
<head>
  <title>予定詳細</title>
</head>
<body>
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
    <p><a href="/edit/{{ $schedule->scheduleId }}">この予定を編集する</a></p>
    @endif
  </div>
  <div>
    <h1>出欠表</h1>
    <table border="1">
      <tr>
        <th>予定</th>
        @foreach ($candidates as $candidateName => $attendArray)
          @foreach ($attendArray as $userName => $attend)
            <th>{{ $userName }}</th>
          @endforeach
          @php
            break;
          @endphp
        @endforeach
      </tr>
      @foreach ($candidates as $candidateName => $attendArray)
        <tr>
          <td>{{ $candidateName }}</td>
          @foreach ($attendArray as $userName => $attend)
            <td>{{ $attend }}</td>
          @endforeach
        </tr>
      @endforeach
      <tr>
        <td>コメント</td>
        @foreach ($comments as $comment)
          <td>{{ $comment->comment }}</td>
        @endforeach
      </tr>
    </table>
    @foreach ($attends as $attend)
      @if ($loginUser->id == $attend->userId)
        @php
          $valueSet = 'true';
          break;
        @endphp
      @endif
    @endforeach
    @if ($valueSet === 'true')
      <p><a href="/attend/{{ $schedule->scheduleId }}/user/{{ $loginUser->id }}">出欠を更新する</a></p>
    @else
      <p><a href="/attendCreate/{{ $schedule->scheduleId }}/user/{{ $loginUser->id }}">出欠を入力する</a></p>
    @endif
  </div>
  <p><a href="{{ url('/') }}">一覧に戻る</a></p>
</body>
</html>
