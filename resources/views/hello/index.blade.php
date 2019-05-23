<html>
<head>
  <title>Hello</title>
</head>
<body>
  <h1>Hello World</h1>
  <div>
    @if (Route::has('login'))
      <div class="top-right links">
        @auth
          <a href="{{ url('/home') }}">Home</a>
        @else
          <a href="{{ route('login') }}">ログイン</a>

          @if (Route::has('register'))
            <a href="{{ route('register') }}">登録</a>
          @endif
        @endauth
      </div>
    @endif
  </div>
</body>
</html>