
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'La Panadería')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Estilos personalizados con cache-busting -->
    <link href="{{ asset('css/styles.css') }}?v={{ time() }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('web.partials.nav')
    
    @yield('contenido')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts personalizados -->
    <script src="{{ asset('js/scripts.js') }}?v={{ time() }}"></script>
    @stack('scripts')
</body>
</html>