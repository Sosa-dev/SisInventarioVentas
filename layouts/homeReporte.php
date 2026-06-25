<?php
use apps\reportes\modelos\ReporteModel;
$reporteModel = new ReporteModel();

// Fechas para analizar el mes en curso
$inicio_mes = date('Y-m-01');
$fin_mes = date('Y-m-t');

// 2. PEDIMOS LOS DATOS EN MODO LECTURA
$total_mes = $reporteModel->getTotalVendido($inicio_mes, $fin_mes);
$alertas_stock = count($reporteModel->getProductosBajoStock());
$top_productos = $reporteModel->getProductosMasVendidos();
?>

<div class="container-fluid py-4">
    
    <!-- Banner Informativo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-light rounded-3 shadow-sm border border-info">
                <h1 class="display-6 fw-bold text-dark">📋 Panel de Consulta y Auditoría</h1>
                <p class="fs-5 text-muted mb-0">Acceso exclusivo de <strong>Solo Lectura</strong> para análisis del mes actual.</p>
            </div>
        </div>
    </div>

    <!-- Módulos de Rendimiento Mensual -->
    <div class="row g-3 mb-4">
        <!-- KPI: Total Mes -->
        <div class="col-12 col-md-6">
            <div class="card h-100 border-start border-info border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Facturación del Mes</h6>
                    <h2 class="card-title display-6 fw-bold text-info">$<?= number_format($total_mes, 2) ?></h2>
                    <p class="card-text text-muted small mb-0">Total bruto facturado desde el 1 de este mes.</p>
                </div>
            </div>
        </div>

        <!-- KPI: Riesgo Stock -->
        <div class="col-12 col-md-6">
            <div class="card h-100 border-start border-warning border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Índice de Rotación Crítica</h6>
                    <h2 class="card-title display-6 fw-bold text-warning"><?= $alertas_stock ?> <span class="fs-5 text-muted">Avisos</span></h2>
                    <p class="card-text text-muted small mb-0">Productos que requieren reposición inmediata.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos de Solo Lectura -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 bg-white rounded-3 border shadow-sm">
                <h5 class="fw-bold mb-3 text-secondary" style="font-size: 0.9rem;">Módulos de Inspección Disponibles</h5>
                <div class="d-flex flex-wrap gap-2">
                    <!-- Enlaces directos a las tablas principales, NO a formularios de creación -->
                    <a href="index.php?modulo=productos&accion=index" class="btn btn-outline-primary px-4">🔍 Consultar Catálogo</a>
                    <a href="index.php?modulo=dashboard&accion=historial" class="btn btn-outline-primary px-4">📄 Examinar Historial Ventas</a>
                    <a href="index.php?modulo=dashboard&accion=reportes" class="btn btn-info text-white px-4">📊 Reportes Estadísticos</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla: Top Tendencias de Mercado -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-secondary">📈 Top 5 Productos con Mayor Demanda</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Nombre del Producto</th>
                                    <th class="text-end pe-3">Volumen Comercializado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($top_productos)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">No hay registros comerciales en este período.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($top_productos as $p): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark"><?= htmlspecialchars($p['nombre']) ?></td>
                                        <td class="text-end pe-3 text-secondary fw-bold"><?= $p['total_vendido'] ?> unidades</td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>