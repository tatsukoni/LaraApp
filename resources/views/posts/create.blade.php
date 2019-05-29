<html>
<head>
  <title>予定調整くん</title>
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <form method="post" action="{{ url('/create') }}">
    {{ csrf_field() }}
    <p>予定名</p>
    <p>
      <input type="text" name="scheduleName" placeholder="予定名" value="{{ old('scheduleName') }}">
      @if ($errors->has('scheduleName'))
      <span class="error">{{ $errors->first('scheduleName') }}</span>
      @endif
    </p>
    <p>メモ</p>
    <p>
      <input type="text" name="memo" placeholder="メモ" value="{{ old('memo') }}">
      @if ($errors->has('memo'))
      <span class="error">{{ $errors->first('memo') }}</span>
      @endif
    </p>
    <p>候補日程 (改行して複数入力してください)</p>
    <p>
      <textarea name="candidates" placeholder="日程を入力してください">{{ old('candidates') }}</textarea>
      @if ($errors->has('candidates'))
      <span class="error">{{ $errors->first('candidates') }}</span>
      @endif
    </p>
    <p><input type="submit" value="予定を作る"></p>
  </form>
</body>
</html>