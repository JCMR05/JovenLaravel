<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condicional</title>
</head>
<body>
    <h1>Su nota es: {{ $nota }}</h1>

    <p>
        <h3>Condicion del Estudiante:</h3>

        @if ($nota >= 2.96)
            <h1>Aprobado</h1>
        
        @else
            <h1>Reprobado</h1>

        @endif

    </p>
</body>
</html>