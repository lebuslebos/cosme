<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','首页') | {{ config('app.name') }}_我们的美妆功课</title>
    <meta name="description" content="化妆品点评 排行榜 cosme">
    <meta name="keywords" content="化妆品,美妆,排名,点评,cosme">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    {{--<link rel="icon" type="image/png" sizes="16x16" href="{{Storage::url('icons/logo.jpg')}}!avatar">--}}
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}"/>
    <link rel="bookmark" href="{{asset('favicon.ico')}}"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="d-flex flex-column">
    <nav class="">

        <app-nav app-name="{{ config('app.name') }}" :is-login="@json(Auth::check())" :user-id="{{Auth::id()??0}}"
                 user-name="{{optional(Auth::user())->name??''}}" init-query="{{request('search')}}"
                 current-route-name="{{Route::currentRouteName()}}"></app-nav>
    </nav>

    {{--    @include('common.message')--}}
    {{--    @includeWhen(session()->has('message'),'common.message')--}}

    {{--原来padding为7rem--}}
    <main class="container p-3 p-md-4">
        <div class="row">
            @yield('content')
        </div>
    </main>
    <a href="#app" class="btn position-fixed back-to-top rounded" style="bottom: 10%; right: 5%;">
        <i class="fa fa-pc fa-arrow-up fa-2x"></i>
    </a>

    <footer class="mt-auto">
        @include('common.footer')
    </footer>


</div>
</body>
</html>
