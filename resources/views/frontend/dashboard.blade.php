<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Universidad Tecnológica XYZ</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="main-container">

        <header class="top-nav">
            <div class="nav-left">
                <i class="fas fa-bars menu-toggle"></i>
                <span class="logo-text">Universidad Tecnologica XYZ</span>
            </div>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar Libros, Títulos, Editor o palabras clave">
            </div>
        </header>

        <div class="content-wrapper">
            
            <aside class="sidebar">
                <div class="sidebar-header">
                    <span class="categoria-titulo">Categorías</span>
                    <i class="fas fa-filter"></i>
                </div>
                
                <nav class="sidebar-nav">
                    <a href="{{ url('/libros') }}" class="nav-item">
                        <i class="fas fa-book-reader"></i> Libros
                    </a>
                    <a href="{{ url('/usuarios') }}" class="nav-item">
                        <i class="fas fa-user-friends"></i> Usuarios
                    </a>
                    <a href="{{ url('/prestamos') }}" class="nav-item">
                        <i class="fas fa-handshake"></i> Préstamos
                    </a>
                    <a href="{{ url('/reportes') }}" class="nav-item">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                </nav>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="nav-item logout" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer; padding: 15px 20px; margin: 0; font-family: inherit; font-size: 1rem; display: flex; align-items: center; transition: background-color 0.2s;">
                        <i class="fas fa-arrow-left" style="font-size: 1.2rem; margin-right: 15px;"></i> Cerrar Sesión
                    </button>
                </form>
            </aside>

            <main class="main-content">
                @php
                    // Asegurar que siempre tengamos el usuario autenticado
                    $usuario = $usuario ?? auth()->user();
                @endphp
                
                <div class="profile-header-container">
                    <div class="library-banner"></div> 
                    
                    <div class="profile-info-block">
                        <div class="profile-image-container">
                            <i class="fas fa-user-circle default-avatar"></i>
                        </div>
                        <h2 class="profile-name">{{ strtoupper($usuario->name ?? 'Usuario') }}</h2>
                        <p class="profile-email">{{ $usuario->email ?? '' }}</p>
                    </div>
                </div>

                <div class="profile-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nombre Completo</span>
                        <span class="detail-value">{{ strtoupper($usuario->name ?? '-') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nacionalidad</span>
                        <span class="detail-value">{{ strtoupper($usuario->nacionalidad ?? '-') }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Dirección Correo Electrónico</span>
                        <span class="detail-value">{{ $usuario->email ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dirección</span>
                        <span class="detail-value">{{ strtoupper($usuario->direccion ?? '-') }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">DNI</span>
                        <span class="detail-value">{{ $usuario->dni ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Rol</span>
                        <span class="detail-value">{{ strtoupper($usuario->rol ?? 'Cliente') }}</span>
                    </div>

                    <div class="detail-item action-item">
                        <span class="detail-label">Contraseña</span>
                        <button class="change-password-btn">Cambiar Contraseña</button>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Número de Teléfono</span>
                        <span class="detail-value">{{ $usuario->telefono ?? '-' }}</span>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>