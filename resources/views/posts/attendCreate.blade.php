<html>
<head>
  <title>予定調整くん</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <h1>出欠情報の入力</h1>
  <form method="post" action="/attendCreate/{{ $schedule->scheduleId }}">
    {{ csrf_field() }}
    <table border="1">
      <tr>
        <th>予定</th>
        <th>{{ $user->name }}</th>
      </tr>
      @php
        $count = 1;
      @endphp
      @foreach ($candidates as $candidate)
        <tr>
          <td>{{ $candidate->candidateName }}</td>
          <td>
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
        <td>
          <textarea name="comment">コメントはありません</textarea>
          @if ($errors->has('comment'))
          <span class="error">{{ $errors->first('comment') }}</span>
          @endif
        </td>
      </tr>
    </table>
    <input type="hidden" name="count" value="{{ $count }}">
    <p><input type="submit" value="出欠を入力する"></p>
  </form>
</body>
</html>