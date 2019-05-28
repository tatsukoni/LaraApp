<html>
<head>
  <title>予定調整くん</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <h1>出欠情報の更新</h1>
  <form method="post" action="/attend/{{ $schedule->scheduleId }}/user/{{ $user->id }}">
    {{ csrf_field() }}
    {{ method_field('patch') }}
    <table border="1">
      <tr>
        <th>予定</th>
        <th>{{ $user->name }}</th>
      </tr>
      @php
        $count = 1;
      @endphp
      @foreach ($attendArray as $key => $value)
        <tr>
          <td>{{ $key }}</td>
          <td>
            <p>現在のステータス：{{ $value }}</p>
            <select name="{{ $count }}">
              <option value="欠席">欠席</option>
              <option value="出席">出席</option>
              <option value="未定">未定</option>
            </select>
          </td>
        </tr>
        @php
          $count++;
        @endphp
      @endforeach
      <tr>
        <td>コメント</td>
        <td><textarea name="comment">{{ $comment->comment }}</textarea></td>
      </tr>
    </table>
    <p><input type="submit" value="出欠を更新する"></p>
  </form>
</body>
</html>