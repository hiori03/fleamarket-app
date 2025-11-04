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
            <div class="header_logo-div">
                <a href="/search" class="header_logo-link">
                    <img class="header_logo" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                </a>
            </div>
            <form class="header_form" action="/" method="GET">
                <input class="header_input" type="text" name="search" placeholder="なにをお探しですか？" value="{{ $search ?? '' }}">
            </form>
            <a class="header_login" href="/login">ログイン</a>
            <a class="header_mypage" href="{{ url('/mypage?page=sell') }}">マイページ</a>
            <a class="header_listing" href="/sell">出品</a>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>