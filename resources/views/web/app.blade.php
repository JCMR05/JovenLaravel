<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="title" content="Shop | ArtCode.com" />
        <meta name="author" content="ArtCode" />
        <meta name="description" content="Shop | ArtCode.com"/>
        <meta name="keywords" content="Shop, ArtCode"/>
        <title>@yield('titulo', 'Shop - El Parche de Pan')</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <!-- Core theme CSS --> 
        <link href="{{asset('css/styles.css')}}?v={{ time() }}" rel="stylesheet" />
        <!-- Custom styles -->
        <link href="{{asset('css/lapanaderia.css')}}?v={{ time() }}" rel="stylesheet" />
        @stack('estilos')
    </head>
    <body>
        <!-- Navigation-->
        @include('web.partials.nav')
        <!-- Header-->
        @if(View::hasSection('header'))
            @include('web.partials.header')
        @endif
        <!-- Content -->
        @yield('contenido')
        <!-- Footer-->
        @include('web.partials.footer')
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{asset('js/scripts.js')}}?v={{ time() }}"></script>
        @stack('scripts')
    </body>
</html>
