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

    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>