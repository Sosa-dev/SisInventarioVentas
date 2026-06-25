<?php

// 1. Instanciamos el modelo de ventas (Ajusta el namespace si tu archivo usa otro)
use apps\ventas\modelos\ventaModel;
$ventaModel = new ventaModel();

// 2. Recuperamos el ID del vendedor desde la sesión
$id_vendedor = $_SESSION['usuario_id']; 

// 3. Alimentamos las variables llamando a los métodos del modelo
$resumen_hoy = $ventaModel->getResumenVendedorHoy($id_vendedor);
$mi_total_dinero = $resumen_hoy['mi_total'];
$mis_tickets_emitidos = $resumen_hoy['mis_tickets'];

$mis_ventas_recientes = $ventaModel->getUltimasVentasVendedor($id_vendedor);
?>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-white rounded-3 shadow-sm border d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold text-dark">¡Hola, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Vendedor') ?>!</h1>
                    <p class="fs-5 text-muted mb-0">Módulo de Facturación y Caja Abierta.</p>
                </div>
                <div>
                    <a href="index.php?modulo=ventas&accion=crear" class="btn btn-success btn-lg px-5 py-3 fs-4 fw-bold shadow">
                        🛒 NUEVA VENTA
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Mi Venta del Día</h6>
                    <h2 class="card-title display-6 fw-bold text-primary">$<?= number_format($mi_total_dinero, 2) ?></h2>
                    <p class="card-text text-muted small mb-0">Tu acumulado para el arqueo de caja actual.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-info border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Mis Tickets Emitidos</h6>
                    <h2 class="card-title display-6 fw-bold text-info"><?= $mis_tickets_emitidos ?> <span class="fs-5 text-muted">facturas</span></h2>
                    <p class="card-text text-muted small mb-0">Clientes atendidos por ti el día de hoy.</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.8rem;">Estado del Turno</h6>
                    <h2 class="card-title display-6 fw-bold text-success">Activo</h2>
                    <p class="card-text text-muted small mb-0">Estación de trabajo sincronizada y autorizada.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark">⏱️ Mis Últimas Facturas Emitidas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">N° Ticket</th>
                                    <th>Hora de Emisión</th>
                                    <th class="text-end pe-3">Total Cobrado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($mis_ventas_recientes)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No has realizado ventas en este turno todavía.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($mis_ventas_recientes as $mv): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold">#<?= $mv['id_venta'] ?></td>
                                        <td class="text-muted"><?= date('h:i A', strtotime($mv['fecha_venta'])) ?></td>
                                        <td class="text-end pe-3 fw-bold text-primary">$<?= number_format($mv['total'], 2) ?></td>
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
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="card-title mb-0 fw-bold">🔍 Consulta de Precios</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <p class="text-muted small">Usa el módulo principal de productos si necesitas validar variaciones o códigos de barra específicos.</p>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Acceso Directo al Catálogo</label>
                            <a href="index.php?modulo=productos&accion=index" class="btn btn-outline-dark w-100 text-start d-flex justify-content-between align-items-center">
                                <span>📦 Ver Lista de Precios</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded border text-center">
                        <span class="d-block text-muted small">¿Problemas con la ticketera?</span>
                        <span class="fw-bold text-secondary">Avisa de inmediato al Administrador.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>