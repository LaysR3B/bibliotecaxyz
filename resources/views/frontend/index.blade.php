<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Biblioteca XYZ</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> 
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="columna-azul">
            <h1>Hola Bienvenido!</h1>
            <p>Universidad Tecnologica XYZ</p>
        </div>

        <div class="columna-blanca">
            <div class="logo-biblioteca">
                <img src="{{ asset('img/xyz.png') }}" alt="Logo de la Biblioteca de la Universidad Tecnológica XYZ">
            </div>

            <div class="tarjeta-login">
                <div class="encabezado-login">
                    <i class="fas fa-book-open icono-libro"></i>
                    <h2>Inicio de Sesión</h2>
                </div>
                
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required autocomplete="username">
                    <input type="password" id="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                    <button type="submit" class="boton-iniciar-sesion">Iniciar Sesión</button>
                    <p id="mensajeError" style="color: red; margin-top: 15px; display: none;"></p>
                    @if ($errors->any())
                        <div style="color: red; margin-top: 15px;">
                            @foreach ($errors->all() as $error)
                                <p style="margin: 5px 0;">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="{{ route('register') }}" style="color: #00bfff; text-decoration: none; font-size: 0.9rem;">
                            ¿No tienes una cuenta? Regístrate aquí
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script defer src="{{ asset('js/script.js') }}"></script> 
</body>
</html>