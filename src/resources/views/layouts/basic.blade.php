<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Evolution36') }} - @yield('title')</title>

    <!-- Styles -->
    <link href="/vendor/whoops-tracker/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>

@include('layouts.menu')
@include('errors.display')

<main>
    @yield('sections')
</main>

<footer>
    @include('layouts.backend.footer')
</footer>

<!-- Scripts -->
<script src="/vendor/whoops-tracker/css/app.js"></script>
@stack('js')
</body>
</html>
