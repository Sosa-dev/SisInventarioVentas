<?php
use apps\ventas\controladores\VentaController;

$ventaController = new VentaController();
$ventas = $ventaController->obtenerHistorial();
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list text-primary"></i> Historial de Ventas
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reporte General de Transacciones</h6>
        </div>
        
        <div class="card-body">
            
            <?php if (isset($ventas['error'])): ?>
                <div class="alert alert-danger">Error SQL: <?php echo $ventas['error']; ?></div>
            <?php else: ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">N° Ticket</th>
                            <th>Fecha y Hora</th>
                            <th>Cliente</th>
                            <th>Cajero</th>
                            <th class="text-right">Total Cobrado</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ventas)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hay ventas registradas aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td class="align-middle text-center font-weight-bold text-primary">
                                        #<?php echo str_pad($venta['id_venta'], 5, "0", STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php echo date('d/m/Y h:i A', strtotime($venta['fecha_venta'])); ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php echo htmlspecialchars($venta['cliente'] ?? 'Consumidor Final'); ?>
                                    </td>
                                    <td class="align-middle text-muted">
                                        <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($venta['cajero']); ?>
                                    </td>
                                    <td class="align-middle text-right font-weight-bold text-success">
                                        $<?php echo number_format($venta['total'], 2); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check"></i> Pagado
                                        </span>
                                    </td>
                                   
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>