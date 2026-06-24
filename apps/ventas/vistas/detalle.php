<?php
use apps\ventas\controladores\VentaController;

// Atrapamos el ID de la URL
$id_venta = $_GET['id'] ?? 0;

$ventaController = new VentaController();
$infoVenta = $ventaController->obtenerInfoVenta($id_venta);
$detalles = $ventaController->obtenerDetalleVenta($id_venta);

// Si alguien pone un ID falso en la URL, lo regresamos al historial
if (!$infoVenta) {
    echo "<script>window.location.href = 'index.php?modulo=ventas&accion=historial';</script>";
    exit();
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar text-primary"></i> 
            Detalle de Venta #<?php echo str_pad($infoVenta['id_venta'], 5, "0", STR_PAD_LEFT); ?>
        </h1>
        <a href="index.php?modulo=ventas&accion=historial" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver al Historial
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">Datos del Comprobante</h6>
                </div>
                <div class="card-body">
                    <p><strong><i class="fas fa-calendar-alt"></i> Fecha:</strong> <?php echo date('d/m/Y h:i A', strtotime($infoVenta['fecha_venta'])); ?></p>
                    <p><strong><i class="fas fa-user"></i> Cliente:</strong> <?php echo htmlspecialchars($infoVenta['cliente'] ?? 'Consumidor Final'); ?></p>
                    <?php if(!empty($infoVenta['telefono'])): ?>
                        <p><strong><i class="fas fa-phone"></i> Teléfono:</strong> <?php echo htmlspecialchars($infoVenta['telefono']); ?></p>
                    <?php endif; ?>
                    <hr>
                    <p class="mb-0 text-muted small"><i class="fas fa-user-circle"></i> Cajero: <?php echo htmlspecialchars($infoVenta['cajero']); ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Productos Adquiridos</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $item): ?>
                                <tr>
                                    <td class="align-middle"><strong><?php echo htmlspecialchars($item['codigo'] ?? 'N/A'); ?></strong></td>
                                    <td class="align-middle"><?php echo htmlspecialchars($item['nombre']); ?></td>
                                    <td class="align-middle text-center"><?php echo $item['cantidad']; ?></td>
                                    <td class="align-middle text-right">$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                                    <td class="align-middle text-right font-weight-bold">$<?php echo number_format($item['subtotal_linea'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <div class="text-muted small mb-1">Subtotal (Sin IVA): $<?php echo number_format($infoVenta['subtotal'], 2); ?></div>
                    <div class="text-muted small mb-2">IVA (13%): $<?php echo number_format($infoVenta['iva'], 2); ?></div>
                    <h4 class="font-weight-bold text-success mb-0">Total: $<?php echo number_format($infoVenta['total'], 2); ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>