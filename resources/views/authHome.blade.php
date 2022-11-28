<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Auth</title>
    </head>
    <body>
        <div>
            <h2>Autenticaci&oacute;n 5</h2>
            @if (Auth::user())
            <p> Hello {{Auth::user()->name}}</p>
            <h3><a href="{{ route('logout') }}">Logout</a></h3>
            @else
            <h3><a href="{{ route('login') }}">Login</a></h3>
            @endif
        </div>
    </body>
</html>
