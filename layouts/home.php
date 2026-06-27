
<?php

use apps\reportes\modelos\ReporteModel;

// Instanciamos el modelo de reportes
$reporteModel = new ReporteModel();

// Definimos la fecha de hoy en formato Año-Mes-Día
$hoy = date('Y-m-d');

// 2. LE PEDIMOS LOS DATOS AL MODELO UTILIZANDO SUS MÉTODOS

$total_dinero_hoy = $reporteModel->getTotalVendido($hoy, $hoy);

// Reutilizamos el listado de ventas de hoy y contamos cuántas fueron
$ventas_de_hoy = $reporteModel->getVentasPorFecha($hoy, $hoy);
$total_ventas_hoy = count($ventas_de_hoy);

// Reutilizamos las alertas de bajo stock
$productos_bajo_stock = $reporteModel->getProductosBajoStock();
$alertas_stock = count($productos_bajo_stock);

// Para las últimas 5 ventas, podemos recortar el array de ventas de hoy 
// o usar las que ya nos trae el método por fecha
$ventas_recientes = array_slice($ventas_de_hoy, 0, 5); 
?>
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">HOME</h1>
<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-white rounded-3 shadow-sm border">
                <h1 class="display-6 fw-bold text-dark">¡Bienvenido al Panel Administrativo!</h1>
                <p class="fs-5 text-muted mb-0">Monitoreo operativo de <strong>SisInventarioVentas</strong> para el día de hoy.</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Caja Hoy</h6>
                    <h2 class="card-title display-6 fw-bold text-success">$<?= number_format($total_dinero_hoy, 2) ?></h2>
                    <p class="card-text text-muted small mb-0">Total neto recaudado el día de hoy.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Ventas Realizadas</h6>
                    <h2 class="card-title display-6 fw-bold text-primary"><?= $total_ventas_hoy ?></h2>
                    <p class="card-text text-muted small mb-0">Órdenes facturadas el día de hoy.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-danger border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Alertas de Stock</h6>
                    <h2 class="card-title display-6 fw-bold text-danger"><?= $alertas_stock ?> <span class="fs-5 text-muted">ítems</span></h2>
                    <p class="card-text text-muted small mb-0">Productos con 10 o menos unidades restantes.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 bg-light rounded-3 border">
                <h5 class="fw-bold mb-3 text-secondary">Accesos Rápidos Obligatorios</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="index.php?modulo=ventas&accion=crear" class="btn btn-primary px-4 py-2 shadow-sm">🛒 Nueva Venta</a>
                    <a href="index.php?modulo=productos&accion=guardar" class="btn btn-outline-secondary px-4 py-2 bg-white">📦 Agregar Producto</a>
                    <a href="index.php?modulo=dashboard&accion=reportes" class="btn btn-outline-secondary px-4 py-2 bg-white">📊 Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">⏱️ Últimas Ventas en Tiempo Real</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ID Venta</th>
                                    <th>Fecha / Hora</th>
                                    <th class="text-end pe-3">Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($ventas_recientes)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No se han registrado ventas recientemente.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($ventas_recientes as $v): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">#<?= $v['id_venta'] ?></td>
                                        <td class="text-muted"><?= date('d/m/Y h:i A', strtotime($v['fecha_venta'])) ?></td>
                                        <td class="text-end pe-3 fw-bold text-success">$<?= number_format($v['total'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100 bg-dark text-white">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div>
                        <h4 class="fw-bold mb-3">Estado del Sistema</h4>
                        <p class="text-light-50 small">La base de datos se encuentra sincronizada de forma correcta.</p>
                        <hr class="border-secondary">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>Conexión BD:</span>
                            <span class="text-success fw-bold">● Activa</span>
                        </div>
                        <!-- <div class="d-flex justify-content-between small">
                            <span>Zona Horaria:</span>
                            <span><?= date_default_timezone_get() ?></span>
                        </div> -->
                    </div>
                    <div class="mt-4">
                        <a href="index.php?modulo=dashboard&accion=reportes" class="btn btn-warning w-100 fw-bold">Ir a Auditoría Completa →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

            