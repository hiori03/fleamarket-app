<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
    @yield('css')
</head>
<body>
    <header>
        <div class="header">
            <a href="/">
                <img class="header_logo" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </a>
            <form class="header_form" action="">
                @csrf
                <input class="header_input" type="text" placeholder="なにをお探しですか？">
            </form>
            <a class="header_logout" href="/login">ログイン</a>
            <a class="header_mypage" href="">マイページ</a>
            <a class="header_listing" href="">出品</a>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>