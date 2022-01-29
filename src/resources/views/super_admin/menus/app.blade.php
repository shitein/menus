<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }}</title>

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet"/>

    {{--<script src="{{ asset('js/general_master.js') }}"></script>--}}
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="{{ asset('css/orange_theme.css') }}" rel="stylesheet"/>


    {{-- Multi select dropdown js / css --}}
    <link rel="stylesheet" href="{{ asset('css/chosen.css') }}">
    <script src="{{asset('js/chosen.jquery.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('fonts/font-awesome.min.css') }}">

    {{-- Date picker js/ css--}}
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />

    {{-- accordion js/css--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/accordion.css') }}" />
    <script type="text/javascript" src="{{ asset('js/accordion.js') }}"></script>

    <script>
        var APP_URL = "<?php echo url('/'); ?>";
    </script>

</head>
<script>
    $(document).ready(function(){

        $('.alert-success').fadeIn().delay(10000).fadeOut();//10 sec.

        /*Send OTP AJAX Call*/
        $('#send-otp').on('click', function () {

            var user_id = $("#uer_id").val();

            $.ajax({
                type: 'GET',
                url: APP_URL + '/sendOTP',
                data: {'userID': user_id},
                success: function (data) {
                    console.log(data);
                    if(data['STATUS'] == 0){
                        $("#error_msg").html(data.error_msg)
                        $("#error_msg").css("color", "red");
                    }else if(data['STATUS'] == 1) {
                        /*$('#sendOTP').hide();
                        $('#verifyOTP').show();*/
                        alert(data['success_msg']);
                       /* $('.loading').hide();*/
                        $('#password').attr('name', 'otp');
                        //window.location = APP_URL + '/login';
                        $('#myModal').modal('hide');
                    }
                }
            });
        });

        /* Verify otp and login */
        $('#verify-otp').on('click', function() {
            var user_id = $("#uer_id").val();
            $('#alert').text('');
            $.ajax({
                type    :'GET',
                url     : APP_URL + '/verifyOTP',
                data    : { 'userID' : user_id, 'OTP':  $('#OTP').val() },
                success : function(data){
                    console.log(data);

                    if(data['STATUS'] == 1) {
                        $('#verifyOTP').hide();
                        $('#changePassword').show();
                    } else {
                        if(data['STATUS'] == 0) {
                            $('#alert').text('Oppps!.. OTP is not correct!!');
                        }
                    }
                }
            });
        });
        /* Verify otp and login */
        $('#change_password').on('click', function() {
            var user_id         = $("#uer_id").val();
            var password        = $("#new_password").val();
            var conf_password   = $("#conf_password").val();

            $('#alert').text('');

            if(password == conf_password){
                $.ajax({
                    type    :'GET',
                    url     : APP_URL + '/change_password',
                    data    : { 'userID' : user_id, 'password':  password },
                    success : function(data){
                        console.log(data);

                        alert(data['msg']);
                        window.location = APP_URL + '/login';
                    }
                });
            }else{
                $('#password_msg').text('Oppps!.. Password not match!!');
            }
        });

    });

</script>
<body  class="hold-transition sidebar-mini">

    <div id="app">
        <nav class="navbar sticky-top bg-light navbar-expand-md navbar-light pb-0 pt-0 shadow-sm main-nav">
            <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest

                        @else
                            {!! App\Common\Common::superAdminMenus() !!}
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                {{--<img class="rounded-circle" src="storage/{{ auth()::}}" />--}}
                                <a id="navbarDropdown" class="nav-link navbar-link-color dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (auth()->user()->profile_pic)
                                       {{-- <img src="{{ auth()->user()->profile_pic }}" style="width: 40px; height: 40px; border-radius: 50%;">--}}
                                        <img src="{{ url(auth()->user()->profile_pic) }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                    @endif
                                    {{ Auth::user()->name }}
                                    {{--<span class="caret"></span>--}}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url("user_profile") }}">
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="loading"></div>
            <div style="margin-top: -15px !important;"></div>
            @yield('content')
        </main>
    </div>
</body>
</html>
