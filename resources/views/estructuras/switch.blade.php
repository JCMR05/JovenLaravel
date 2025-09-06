<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switch</title>
</head>
<body>
    <h2>Su dia es: </h2>
    @switch($dia)
        
        @case (1)
        <h3>Lunes</h3>
        @break
        @case (2)
        <h3>Martes</h3>
        @break
        @case (3)
        <h3>Miercoles</h3>
        @break
        @case (4)
        <h3>Jueves</h3>
        @break
        @case (5)
        <h3>Viernes</h3>
        @break
        @case (6)
        <h3>Sabado</h3>
        @break
        @case (7)
        <h3>Domingo</h3>
        @break
        @default
        <h3>Numero desconocido</h3>
        @break
    @endswitch

</body>
</html>