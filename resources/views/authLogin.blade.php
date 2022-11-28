<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel Auth</title>
    </head>
    <body>
        <div>
            <h2>Autenticaci&oacute;n 2</h2>
            @if (Auth::user())
            <p> Ya est&aacute; autenticado/a, {{Auth::user()->name}}</p>
            <h3><a href="{{ route('logout') }}">Logout</a></h3>
            @else
            <h2><a href="{{ route('google_redirect') }}">Identificarse con Google</a></h2>            
            @endif
        </div>
    </body>
</html>
