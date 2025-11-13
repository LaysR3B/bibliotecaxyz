<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Universidad Tecnológica XYZ</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <input type="text" id="searchInput" placeholder="Buscar Usuarios, Nombres, Email o palabras clave">
            </div>
             <a href="{{ url('/dashboard') }}" class="volver-inicio">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
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
                <div class="controls-row">
                    <div class="input-group search-input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar Usuario">
                    </div>
                    
                    <div class="actions-group">
                        <button type="button" class="btn btn-primary btn-lg action-btn" data-bs-toggle="modal" data-bs-target="#usuarioModal" onclick="abrirModalAgregar()">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover" id="usuariosTable">
                        <thead class="table-dark table-header-custom">
                            <tr>
                                <th>Nombres y Apellidos</th>
                                <th>DNI</th>
                                <th>Correo Electrónico</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Datos Completos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                @php
                                    $datosCompletos = !empty($usuario->dni) && !empty($usuario->nacionalidad) && !empty($usuario->direccion) && !empty($usuario->telefono);
                                @endphp
                                <tr>
                                    <td>{{ $usuario->name }}</td>
                                    <td>
                                        @if($usuario->dni)
                                            {{ $usuario->dni }}
                                        @else
                                            <span class="text-muted"><i class="fas fa-exclamation-circle"></i> Sin DNI</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @if($usuario->telefono)
                                            {{ $usuario->telefono }}
                                        @else
                                            <span class="text-muted"><i class="fas fa-exclamation-circle"></i> Sin teléfono</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->rol ?? 'Cliente' }}</td>
                                    <td>
                                        <span class="badge {{ $usuario->estado === 'Activo' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $usuario->estado ?? 'Activo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($datosCompletos)
                                            <span class="badge bg-success"><i class="fas fa-check"></i> Completo</span>
                                        @else
                                            <span class="badge bg-warning"><i class="fas fa-exclamation-triangle"></i> Incompleto</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="abrirModalEditar({{ $usuario->id }}, '{{ addslashes($usuario->name) }}', '{{ $usuario->dni ?? '' }}', '{{ addslashes($usuario->nacionalidad ?? '') }}', '{{ addslashes($usuario->direccion ?? '') }}', '{{ $usuario->telefono ?? '' }}', '{{ $usuario->email }}', '{{ $usuario->rol ?? 'Cliente' }}', '{{ $usuario->estado ?? 'Activo' }}')" title="Editar usuario">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        @if($usuario->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $usuario->id }})" title="Eliminar usuario">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="No puedes eliminar tu propio usuario">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay usuarios registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Usuario -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="usuarioForm" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombres y Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni" maxlength="20" placeholder="Opcional">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" maxlength="100" placeholder="Ej: PERU">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej: 912 345 678">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" maxlength="255" placeholder="Ej: AV. LAS PALMERAS 274">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger" id="passwordRequired">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="8">
                                    <small class="form-text text-muted" id="passwordHelp">Mínimo 8 caracteres. Dejar vacío para no cambiar (solo en edición).</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select" id="rol" name="rol" required>
                                        <option value="Cliente">Cliente</option>
                                        <option value="Trabajador">Trabajador</option>
                                        <option value="Administrador">Administrador</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle"></i> Los campos marcados con <span class="text-danger">*</span> son obligatorios. Los demás campos pueden completarse después.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para eliminar -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para abrir modal de agregar
        function abrirModalAgregar() {
            document.getElementById('usuarioModalLabel').textContent = 'Agregar Usuario';
            document.getElementById('usuarioForm').action = '{{ route("usuarios.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('password').required = true;
            document.getElementById('passwordRequired').style.display = 'inline';
            document.getElementById('passwordHelp').textContent = 'Mínimo 8 caracteres.';
            document.getElementById('usuarioForm').reset();
        }

        // Función para abrir modal de editar
        function abrirModalEditar(id, name, dni, nacionalidad, direccion, telefono, email, rol, estado) {
            document.getElementById('usuarioModalLabel').textContent = 'Editar Usuario';
            document.getElementById('usuarioForm').action = '{{ url("usuarios") }}/' + id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('name').value = name;
            document.getElementById('dni').value = dni || '';
            document.getElementById('nacionalidad').value = nacionalidad || '';
            document.getElementById('direccion').value = direccion || '';
            document.getElementById('telefono').value = telefono || '';
            document.getElementById('email').value = email;
            document.getElementById('rol').value = rol || 'Cliente';
            document.getElementById('estado').value = estado || 'Activo';
            document.getElementById('password').required = false;
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordHelp').textContent = 'Dejar vacío para no cambiar la contraseña.';
            document.getElementById('password').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('usuarioModal'));
            modal.show();
        }

        // Función para confirmar eliminación
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ url("usuarios") }}/' + id;
                form.submit();
            }
        }

        // Búsqueda en tiempo real
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('usuariosTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            }
        });
    </script>
</body>
</html>
