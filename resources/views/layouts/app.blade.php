<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield('title','首页')-我们的美妆笔记</title>
    <base target="_blank"/>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    {{--<link rel="dns-prefetch" href="https://fonts.gstatic.com">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">--}}
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/icons/default.JPG')}}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" >
    <nav class="bg-light-brown">
        {{--<div class="container d-flex align-items-center justify-content-around px-0 px-md-3 py-2 py-md-3">

            <a class="text-main" style="font-size: 1.5rem" href="{{ url('/') }}" v-if="!hide" @hideLogo="hide=true" @showLogo="hide=false">
                {{ config('app.name', '有容') }}
            </a>--}}

            <app-nav app-name="{{ config('app.name', '有容') }}"
                     :is-login="@json(Auth::check())"
                     :user-id="{{Auth::id()??0}}"
                     user-name="{{optional(Auth::user())->name??''}}"
                     init-query="{{request('search')}}"
                     current-route-name="{{Route::currentRouteName()}}"
            ></app-nav>

            {{--<ul class="list-inline mb-0" v-if="!hide" @hideLogo="hide=true" @showLogo="hide=false">
                @guest
                    --}}{{--登录--}}{{--
                    <li class="list-inline-item">
                        <a class="text-brown" data-toggle="modal" id="login"
                           :href="$store.state.device.isMobile?'#loginModalMobile':'#loginModal'">登录</a>
                    </li>
                    <login-form-mobile v-if="$store.state.device.isMobile"></login-form-mobile>
                    <login-form v-else></login-form>

                @else
                    --}}{{--用户昵称--}}{{--
                    <li class="list-inline-item">
                        <a class="text-main text-big"
                           href="{{route('users.show',[Auth::user()])}}">{{ Auth::user()->name }}</a>
                    </li>
                    --}}{{--<li class="list-inline-item d-none d-md-inline-block">
                        <a class="text-secondary text-tiny" href="{{ route('logout') }}">退出</a>
                    </li>--}}{{--
                @endguest
            </ul>
        </div>--}}
    </nav>

    {{--    @include('common.message')--}}
    {{--    @includeWhen(session()->has('message'),'common.message')--}}

    {{--原来padding为7rem--}}
    <main class="container p-3 p-md-4">
        <div class="row">
            @yield('content')
        </div>
    </main>

    <footer>
        @include('common.footer')
    </footer>


</div>
</body>
</html>
