<?php
use apps\productos\controladores\productoController;
$productoController = new productoController();
$productos = $productoController->listar();
?>

<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                     
                    <h1 class="h3 mb-4 text-gray-800">Productos</h1>
                    <?php if (isset($_SESSION['success_mensaje'])): ?>
                                        <div class="alert alert-success" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            <?php 
                                                echo $_SESSION['success_mensaje']; 
                                                unset($_SESSION['success_mensaje']); // Lo borramos para que no vuelva a aparecer al recargar
                                            ?>
                                            <button type="text" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_mensaje'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            <?php 
                                                echo $_SESSION['error_mensaje']; 
                                                unset($_SESSION['error_mensaje']); // Lo borramos para que no vuelva a aparecer al recargar
                                            ?>
                                            <button type="text" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                    <a href="index.php?modulo=productos&accion=guardar" class="btn btn-primary btn-user btn-block">
                        Agregar Nuevo Producto
                    </a>

                    <?php if (isset($_SESSION['error_mensaje'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            <?php 
                                                echo $_SESSION['error_mensaje']; 
                                                unset($_SESSION['error_mensaje']); // Lo borramos para que no vuelva a aparecer al recargar
                                            ?>
                                            <button type="text" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                        <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Listado de productos</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </tfoot>
                                    <?php if(!empty($productos)): ?>
                                    <tbody>
                                        <?php foreach($productos as $producto): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['codigo']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars('$'.$producto['precio']); ?></td>
                                            <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                                            <!-- <td><?php echo htmlspecialchars($producto['estado']); ?></td> -->
                                            <td><?php echo htmlspecialchars($producto['estado'] ? 'Activo' : 'Inactivo'); ?></td>

                                            <td class="d-flex flex-row">
                                                <a href="index.php?modulo=productos&accion=editar&id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-primary m-2">Editar</a>
                                                <!-- <form action="index.php?modulo=productos&accion=editar&id=" method="GET"><input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>"><button type="submit" class="btn btn-sm btn-primary m-2">Editar</button></form>
                                                <form action="index.php?modulo=productos&accion=eliminar" method="POST"><input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>"><input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"><button type="submit" class="btn btn-sm btn-danger m-2">Eliminar</button></form> -->
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                <?php else: ?>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">No se encontraron productos.</td>
                                        </tr>
                                    </tbody>
                                <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->