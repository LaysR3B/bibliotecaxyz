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
                <input type="text" id="searchInput" placeholder="Buscar Libros, Títulos, Editor o palabras clave">
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
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar Libro">
                    </div>
                    
                    <div class="actions-group">
                        <button type="button" class="btn btn-primary btn-lg action-btn" data-bs-toggle="modal" data-bs-target="#libroModal" onclick="abrirModalAgregar()">
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
                
                <div class="table-responsive mt-4">
                    <table class="table table-striped table-hover" id="librosTable">
                        <thead class="table-dark table-header-custom">
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Categoría</th>
                                <th>Ejemplares</th>
                                <th>Área (Estante)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($libros as $libro)
                                <tr>
                                    <td>{{ $libro->codigo }}</td>
                                    <td>{{ $libro->titulo }}</td>
                                    <td>{{ $libro->autor }}</td>
                                    <td>{{ $libro->categoria }}</td>
                                    <td>{{ $libro->ejemplares }}</td>
                                    <td>{{ $libro->area_estante }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="abrirModalEditar({{ $libro->id }}, '{{ $libro->codigo }}', '{{ addslashes($libro->titulo) }}', '{{ addslashes($libro->autor) }}', '{{ addslashes($libro->categoria) }}', {{ $libro->ejemplares }}, '{{ addslashes($libro->area_estante) }}')">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $libro->id }})">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay libros registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para Agregar/Editar Libro -->
    <div class="modal fade" id="libroModal" tabindex="-1" aria-labelledby="libroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="libroModalLabel">Agregar Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="libroForm" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                        </div>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" required>
                        </div>
                        <div class="mb-3">
                            <label for="ejemplares" class="form-label">Ejemplares</label>
                            <input type="number" class="form-control" id="ejemplares" name="ejemplares" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="area_estante" class="form-label">Área (Estante)</label>
                            <input type="text" class="form-control" id="area_estante" name="area_estante" required>
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
            document.getElementById('libroModalLabel').textContent = 'Agregar Libro';
            document.getElementById('libroForm').action = '{{ route("libros.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('libroForm').reset();
        }

        // Función para abrir modal de editar
        function abrirModalEditar(id, codigo, titulo, autor, categoria, ejemplares, area_estante) {
            document.getElementById('libroModalLabel').textContent = 'Editar Libro';
            document.getElementById('libroForm').action = '{{ url("libros") }}/' + id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('codigo').value = codigo;
            document.getElementById('titulo').value = titulo;
            document.getElementById('autor').value = autor;
            document.getElementById('categoria').value = categoria;
            document.getElementById('ejemplares').value = ejemplares;
            document.getElementById('area_estante').value = area_estante;
            
            const modal = new bootstrap.Modal(document.getElementById('libroModal'));
            modal.show();
        }

        // Función para confirmar eliminación
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este libro?')) {
                const form = document.getElementById('deleteForm');
                form.action = '{{ url("libros") }}/' + id;
                form.submit();
            }
        }

        // Búsqueda en tiempo real
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('librosTable');
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
