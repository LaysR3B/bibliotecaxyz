<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Iniciada con Éxito</title>

    {{-- CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/confirmacion.css') }}"> 

    {{-- Fuentes e íconos --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body onclick="redirigir()">
    <div class="background-split">
        <div class="confirmacion-card">
            
            <div class="check-icon-container">
                <i class="fas fa-check check-icon"></i>
            </div>

            <h1 class="mensaje-exito">Sesión iniciada con éxito</h1>

            <p class="instruccion">
                Presiona cualquier sitio<br>para continuar
            </p>
        </div>
    </div>

    <script>
        // Redirigir al dashboard de Laravel
        function redirigir() {
            window.location.href = "{{ url('/dashboard') }}";
        }

        // Si prefieres redirigir automáticamente después de 3 segundos, descomenta:
        // setTimeout(redirigir, 3000);
    </script>
</body>
</html>
