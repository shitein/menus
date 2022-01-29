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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet" />

    {{--<script src="{{ asset('js/general_master.js') }}"></script>--}}
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/orange_theme.css') }}" rel="stylesheet" />


    {{-- Multi select dropdown js / css --}}
    <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
    <script src="{{asset('js/chosen.jquery.js')}}"></script>
    <link href="{{ asset('fonts/font-awesome.min.css') }}" rel="stylesheet">

    {{-- Date picker js/ css--}}
    <script src="{{ asset('js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/daterangepicker.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />

    {{-- accordion js/css--}}
    <link type="text/css" href="{{ asset('css/accordion.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/accordion.js') }}" type="text/javascript"></script>
    <script>
        var APP_URL = '{{url('/')}}'
    </script>
    <script src="{{ asset('js/tinymce.min.js') }}" referrerpolicy="origin"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" ></script> --}}

    {{-- Location --}}
    {{-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyBLo2emU_Wmv90fXMRdlAmoi500Mk0Ke-4&libraries=places" type="text/javascript"></script> --}}

    {{-- Login & Register css--}}
    <!-- <link href="asset('/public-ui/css/custom.css') }}" rel="stylesheet"> -->
</head>
<script>
        /*-- Set Menu Name in local Storage --*/
        $('.navbar-link-color').on('click', function() {
            localStorage.setItem('Menu', $(this).data('menu'));
            localStorage.setItem('Sub-Menu', '');
        });
        $('.dropdown-item, .add-submenu-item').on('click', function() {
            localStorage.setItem('Sub-Menu', $(this).data('submenu'));
        })

</script>

<body class="hold-transition sidebar-mini">

    <div id="app">

        <main class="py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 p-0">
                        @if(Session::has('failed'))
                        <div class="alert alert-danger">
                            {{Session::get('failed')}}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-12 p-0">
                        @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="loading"></div>
            <div style="margin-top: -15px !important;"></div>
            @yield('content')
        </main>
    </div>
    <script>
        $(document).ready(function() {
            $("#allSearch").click(function() {
                $("#search-box").val('');
            });

            $(".navbar-link-color").click(function() {
                $(this).prev(".br-right").addClass("active");
            })
        });
    </script>

    {{-- unused code for menus module --}}

    {{-- <script type="text/javascript">
        $(document).ready(function() {
            $("#lat_area").addClass("d-none");
            $("#long_area").addClass("d-none");
        });

        google.maps.event.addDomListener(window, 'load', initialize);
        initialize();

        function initialize() {
            var options = {
                componentRestrictions: {
                    country: "IN"
                }
            };
            geocoder = new google.maps.Geocoder();
            console.log(options);
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
            });
        }

        function errorFunction() {
            alert("Geocoder failed");
        }
    </script> --}}
</body>

</html>
