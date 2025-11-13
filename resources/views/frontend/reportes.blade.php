<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Universidad Tecnológica XYZ</title>
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
                <div class="mb-4">
                    <h2 class="mb-3">Reportes de Préstamos</h2>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="reporteSelect" class="form-label">Selecciona un reporte</label>
                            <select id="reporteSelect" class="form-select">
                                <option value="masPrestamos">Usuarios con más préstamos</option>
                                <option value="vencidos">Usuarios con préstamos vencidos</option>
                                <option value="inactivos">Usuarios inactivos (sin préstamos recientes)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="verReporteBtn" class="btn btn-primary">
                                <i class="fas fa-chart-pie"></i> Ver reporte
                            </button>
                        </div>
                    </div>
                </div>

                <div id="mensajeDatos" class="text-muted small mb-3"></div>

                <div class="mb-4">
                    <h5>Usuarios con más préstamos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Total de Préstamos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPrestamos as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? 'Usuario eliminado' }}</td>
                                        <td>{{ $item->total }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">Sin datos disponibles</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Usuarios con préstamos vencidos</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Préstamos Pendientes (Vencidos)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prestamosVencidos as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['total'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center">Sin datos disponibles</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Usuarios inactivos (sin préstamos recientes)</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuariosInactivos as $usuario)
                                    <tr>
                                        <td>{{ $usuario->name }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center">Sin datos disponibles</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal para visualizar reporte -->
    <div class="modal fade" id="reporteModal" tabindex="-1" aria-labelledby="reporteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reporteModalLabel">Visualización de reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-muted" id="reporteDescripcion">Usuarios con más préstamos</p>
                    <canvas id="reporteChart" width="280" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const reportData = @json($chartData);
        const descriptions = {
            masPrestamos: 'Distribución de usuarios con más préstamos registrados.',
            vencidos: 'Usuarios con préstamos pendientes que superan los 15 días.',
            inactivos: 'Usuarios sin registro de préstamos en los últimos 60 días.',
        };

        const mensajeDatos = document.getElementById('mensajeDatos');
        const canvas = document.getElementById('reporteChart');
        const ctx = canvas.getContext('2d');
        let chartInstance = null;
        const reporteModal = new bootstrap.Modal(document.getElementById('reporteModal'));

        function renderChart(key) {
            const data = reportData[key];
            const descripcion = descriptions[key] || '';

            document.getElementById('reporteDescripcion').textContent = descripcion;

            if (!data || !data.labels || data.labels.length === 0 || data.values.every(v => v === 0)) {
                mensajeDatos.textContent = 'No hay datos suficientes para este reporte.';
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }
                return false;
            }

            mensajeDatos.textContent = '';

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.values,
                        backgroundColor: [
                            '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc948',
                            '#b07aa1', '#ff9da7', '#9c755f', '#bab0ab'
                        ],
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            return true;
        }

        const reporteSelect = document.getElementById('reporteSelect');
        document.getElementById('verReporteBtn').addEventListener('click', function () {
            const key = reporteSelect.value;
            const hasData = renderChart(key);
            if (hasData) {
                reporteModal.show();
            }
        });
    </script>
</body>
</html>