<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>

    <h2>Haku</h2>

    <form method="POST" action="search/">
        <input type="text" name="search" id="search" placeholder="Hakusana">
        <input type="submit" value="Etsi">
        @csrf
    </form>

    </body>
</html>
