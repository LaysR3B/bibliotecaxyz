<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos - Universidad Tecnológica XYZ</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <input type="text" placeholder="Buscar Libros, Títulos, Editor o palabras clave">
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
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar Préstamo">
                    </div>
                    
                    <div class="actions-group">
                        <button type="button" class="btn btn-primary btn-lg action-btn" data-bs-toggle="modal" data-bs-target="#prestamoModal" onclick="abrirModalAgregar()">
                            <i class="fas fa-plus"></i> Registrar préstamo
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover" id="prestamosTable">
                        <thead class="table-dark table-header-custom">
                            <tr>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Libro</th>
                                <th>Fecha Préstamo</th>
                                <th>Fecha Devolución</th>
                                <th>Días Prestado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prestamos as $prestamo)
                                <tr>
                                    <td>{{ $prestamo->user->name ?? 'Usuario eliminado' }}</td>
                                    <td>
                                        <span class="badge {{ $prestamo->estado === 'Pendiente' ? 'bg-warning text-dark' : 'bg-success' }}">
                                            {{ $prestamo->estado }}
                                        </span>
                                    </td>
                                    <td>{{ $prestamo->libro->titulo ?? 'Libro eliminado' }}</td>
                                    <td>{{ $prestamo->fecha_prestamo?->format('d/m/Y') }}</td>
                                    <td>{{ $prestamo->fecha_devolucion?->format('d/m/Y') ?? '-' }}</td>
                                    <td>
                                        @if(!is_null($prestamo->dias_prestamo))
                                            <span class="badge bg-info text-dark">{{ $prestamo->dias_prestamo }} día{{ $prestamo->dias_prestamo === 1 ? '' : 's' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="abrirModalEditar({{ $prestamo->id }}, {{ $prestamo->user_id }}, '{{ $prestamo->estado }}', {{ $prestamo->libro_id }}, '{{ $prestamo->fecha_prestamo?->toDateString() }}', '{{ $prestamo->fecha_devolucion?->toDateString() }}')" title="Editar préstamo">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $prestamo->id }})" title="Eliminar préstamo">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay préstamos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Préstamo -->
    <div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prestamoModalLabel">Registrar Préstamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="prestamoForm" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="" disabled selected>Selecciona un usuario</option>
                                @foreach($usuarios as $usuarioOption)
                                    <option value="{{ $usuarioOption->id }}">{{ $usuarioOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="libro_id" class="form-label">Libro Prestado <span class="text-danger">*</span></label>
                            <select class="form-select" id="libro_id" name="libro_id" required>
                                <option value="" disabled selected>Selecciona un libro</option>
                                @foreach($libros as $libroOption)
                                    <option value="{{ $libroOption->id }}">{{ $libroOption->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_prestamo" class="form-label">Fecha de Préstamo <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_devolucion" class="form-label">Fecha de Devolución</label>
                                    <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion">
                                    <small class="form-text text-muted">Obligatorio si el estado es "Devuelto".</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Devuelto">Devuelto</option>
                            </select>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const estadoSelect = document.getElementById('estado');
        const fechaDevolucionInput = document.getElementById('fecha_devolucion');
        const userSelect = document.getElementById('user_id');
        const libroSelect = document.getElementById('libro_id');

        function toggleFechaDevolucionRequirement() {
            if (estadoSelect.value === 'Devuelto') {
                fechaDevolucionInput.required = true;
            } else {
                fechaDevolucionInput.required = false;
                fechaDevolucionInput.value = '';
            }
        }

        estadoSelect.addEventListener('change', toggleFechaDevolucionRequirement);

        function abrirModalAgregar() {
            document.getElementById('prestamoModalLabel').textContent = 'Registrar Préstamo';
            document.getElementById('prestamoForm').action = '{{ route("prestamos.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('prestamoForm').reset();
            document.getElementById('estado').value = 'Pendiente';
            userSelect.selectedIndex = 0;
            libroSelect.selectedIndex = 0;
            toggleFechaDevolucionRequirement();
        }

        function abrirModalEditar(id, userId, estado, libroId, fechaPrestamo, fechaDevolucion) {
            document.getElementById('prestamoModalLabel').textContent = 'Editar Préstamo';
            document.getElementById('prestamoForm').action = '{{ url("prestamos") }}/' + id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            userSelect.value = userId;
            libroSelect.value = libroId;
            document.getElementById('fecha_prestamo').value = fechaPrestamo || '';
            document.getElementById('fecha_devolucion').value = fechaDevolucion || '';
            estadoSelect.value = estado || 'Pendiente';
            toggleFechaDevolucionRequirement();

            const modal = new bootstrap.Modal(document.getElementById('prestamoModal'));
            modal.show();
        }

        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro que deseas eliminar este préstamo?')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ url("prestamos") }}/' + id;
                form.submit();
            }
        }

        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('prestamosTable');
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