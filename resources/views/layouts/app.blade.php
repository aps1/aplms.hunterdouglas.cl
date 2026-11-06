<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @stack('prestyles')
    <link href="{{ asset('css/all.css') }}?v=1.1.3" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}?v=1.1.3" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/kt-2.5.1/r-2.2.3/sc-2.0.1/sl-1.3.1/datatables.min.css"/>
    @stack('styles')
    <style>
        [v-cloak] {
          display: none;
        }
    </style>
    
</head>
<body>
    <div id="wrapper">
        @if (optional(Auth::user())->isAdminLevel())
            @include('layouts._navbar')
        @elseif(optional(Auth::user())->isSeller())
            @include('layouts._sellers_navbar')
        @elseif(optional(Auth::user())->isExecutiveLevel())
            @include('layouts._owners_navbar')
        @endif
           
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                @include('layouts._topbar')
            </div>
            @yield('heading')
            <div class="wrapper wrapper-content animated fadeInRight">
                @yield('content')
            </div>
            <div class="footer">
                {{-- <div class="pull-right">
                    10GB of <strong>250GB</strong> Free.
                </div> --}}
                <div>
                    <strong>Copyright</strong> HunterDouglas &copy; 2019-2020
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/all.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> --}}
    {{-- datatables --}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-html5-1.6.1/b-print-1.6.1/kt-2.5.1/sl-1.3.1/datatables.min.js"></script>

    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/options.js') }}"></script>
    @if (session('notification'))
        <script>
            toastr["{!! session('notification.type') !!}"]
            ("{!! session('notification.message') !!}", "{!! session('notification.title') !!}", toastr_options);
        </script>
    @endif
    <script>
        //Imprime la pagina
        function printPage() {
            window.print();
        }
        
        // Formatea el monto a millones
        function formatMillions(value) {
            if(value == null || value == 0){
                return 0 
            }
            number = value / 1000000;
            number = new Intl.NumberFormat(
                'de-DE', 
                {  
                    maximumFractionDigits: 2
                }
            ).format(number);
            return number;
        }

        //Previene el doble submit
        $(document).ready(function () {
            $('form').submit(function() {
                $(this).find("button[type='submit']").prop('disabled',true).html(' Guardando <i class="fa fa-spinner fa-spin"></i>');
            });
        });

        
    </script>
    @stack('scripts')
</body>
</html>
