<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="robots" content="index,follow">
    <meta name="baidu-site-verification" content="6C3GFn9kf0"/>
    <meta name="google-site-verification" content="BYzPXCFEZxfDtQ5ALFhVv9kzORm-I9IIdX_C1GaIIBA"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','首页') | {{ config('app.name') }}_我们的美妆功课</title>
    <meta name="author" content="Lebus">
    <meta name="description"
          content="有容是一个专注于发现与分享的美妆点评社区。用户可以在这里发现美妆的最新排名，获取化妆品的真实统计信息，还可以与大家分享使用心得，找到适合自己的变美方法。">
    <meta name="keywords" content="有容美妆，化妆品，美妆，排名，点评，cosme，cosme大赏，cosme大奖，护肤 ，彩妆，化妆教程，口红，美白，祛痘，祛斑">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?t=20180813" defer></script>


    <!-- Fonts -->
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    {{--<link rel="icon" type="image/png" sizes="16x16" href="{{Storage::url('icons/logo.jpg')}}!avatar">--}}
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}"/>
    <link rel="bookmark" href="{{asset('favicon.ico')}}"/>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?t=20180813" rel="stylesheet">

</head>
<body>
<div id="app" class="d-flex flex-column">
    <nav class="">
        <app-nav app-name="{{ config('app.name') }}" :is-login="@json(Auth::check())" :user-id="{{Auth::id()??0}}"
                 user-name="{{optional(Auth::user())->name??''}}" init-query="{{request('search')}}"
                 current-route-name="{{Route::currentRouteName()}}" version="Beta 1.0.8"></app-nav>
    </nav>

    {{--原来padding为7rem--}}
    <main class="container p-3 p-md-4">
        <div class="row">
            @yield('content')
        </div>
    </main>
    <div class="position-fixed" style="bottom: 10%; right: 5%;">

        <qrcode qrcode="{{Storage::url('wx-code.jpg')}}!cosme"></qrcode>
        <a href="#app" class="btn back-to-top d-block">
            <i class="fa fa-pc fa-arrow-up fa-2x"></i>
        </a>
    </div>

    <footer class="mt-auto">
        @include('common.footer')
    </footer>
</div>
</body>
</html>
