

    <div class="dashboard-header">
        <h1>Reportes Administrativos</h1>
        <p>Monitoreo general del estado del inventario y ventas.</p>
    </div>

    <div class="kpi-container">
        <div class="card success">
            <h3>Total Vendido (Período)</h3>
            <div class="value">$<?= number_format($total_vendido, 2) ?></div>
        </div>
        <div class="card danger">
            <h3>Productos Bajo Stock</h3>
            <div class="value"><?= count($bajo_stock) ?> Alertas</div>
        </div>
    </div>

    <form method="GET" action="index.php?modulo=dashboard&accion=reportes" class="filter-box">
        <input type="hidden" name="modulo" value="dashboard">
        <input type="hidden" name="accion" value="reportes">
        <label>Desde:</label>
        <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
        
        <label>Hasta:</label>
        <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
        
        <button type="submit" class="btn-filter">Filtrar Reporte</button>
    </form>
<div class="table-section" style="margin-bottom: 30px;">
        <h2>📋 Ventas Realizadas en el Período</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha y Hora</th>
                    <th>Monto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas_por_fecha)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #64748b; padding: 20px;">
                            No se encontraron ventas registradas entre las fechas seleccionadas.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas_por_fecha as $venta): ?>
                        <tr>
                            <td><strong>#<?= $venta['id_venta'] ?></strong></td>
                            <td><?= date('d/m/Y h:i A', strtotime($venta['fecha_venta'])) ?></td>
                            <td style="color: var(--success); font-weight: bold;">
                                $<?= number_format($venta['total'], 2) ?>
                            </td>
                            <td>
                                <a href="index.php?modulo=ventas&accion=detalle&id=<?= $venta['id_venta'] ?>" 
                                   style="color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 600;">
                                   Ver Detalle →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="grid-tables">
        
        <div class="table-section">
            <h2>🔥 Productos Más Vendidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Unidades Vendidas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($mas_vendidos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><strong><?= $p['total_vendido'] ?></strong> unds.</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="table-section">
            <h2>⚠️ Alertas de Bajo Stock</h2>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bajo_stock as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= $p['stock'] ?> unidades</td>
                        <td><span class="badge-danger">Reordenar</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
