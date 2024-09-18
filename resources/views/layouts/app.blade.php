<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.87.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Weather</title>

    <!-- Bootstrap core CSS -->
    <link href="{!! url('assets/third-party/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="{!! url('assets/custom/css/fonts.css') !!}" rel="stylesheet">
    <link href="{!! url('assets/custom/css/app.css') !!}" rel="stylesheet">
</head>
<body class="bg-black">
    {{-- <main class="container"> --}}
    <div id='loader'></div>
    @yield('content')
    {{-- </main> --}}
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="{!! url('assets/third-party/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
    @yield('scripts')
  </body>
</html>