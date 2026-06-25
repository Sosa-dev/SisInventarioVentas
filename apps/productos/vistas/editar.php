<?php
use apps\productos\controladores\productoController;
$productoController = new productoController();
$categorias = $productoController->categoria(); 
?>
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-packages">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3l0 -5.5" />
                                    <path d="M2 13.5v5.5l5 3" />
                                    <path d="M7 16.545l5 -3.03" />
                                    <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3l0 -5.5" />
                                    <path d="M12 19l5 3" />
                                    <path d="M17 16.5l5 -3" />
                                    <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
                                    <path d="M7 5.03v5.455" />
                                    <path d="M12 8l5 -3" />
                                </svg>
                                Editar Producto</h1>
                        </div>
                        
                        <form class="user" method="POST" action="index.php?modulo=productos&accion=editar" autocomplete="off">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="id_producto" value="<?php echo $prod['id_producto']; ?>">
                            
                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="codigo">Código del Producto</label>
                                <input type="text" class="form-control form-control-user" id="codigo" 
                                       value="<?php echo htmlspecialchars($prod['codigo']); ?>"
                                       placeholder="ZAP001" name="codigo" required>
                            </div>

                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="id_categoria">Categoría</label>
                                <select class="form-control custom-select-user" id="id_categoria" name="id_categoria" required>
                                    <option value="" disabled>Selecciona una categoría...</option>
                                    <?php if(!empty($categorias)): ?>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>" 
                                                <?php echo ($prod['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="nombre">Nombre del Producto</label>
                                <input type="text" class="form-control form-control-user" id="nombre"
                                       value="<?php echo htmlspecialchars($prod['nombre']); ?>"
                                       placeholder="Adidas" name="nombre" required>
                            </div>

                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="precio">Precio</label>
                                <input type="number" step="0.01" class="form-control form-control-user" id="precio" min="0"
                                       value="<?php echo htmlspecialchars($prod['precio']); ?>"
                                       placeholder="$75.00" name="precio" required>
                            </div>

                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="stock">Stock</label>
                                <input type="number" class="form-control form-control-user" id="stock" min="0"
                                       value="<?php echo htmlspecialchars($prod['stock']); ?>"
                                       placeholder="284" name="stock" required>
                            </div>

                            <div class="form-group">
                                <label class="text-xs font-weight-bold text-primary text-uppercase mb-1" for="estado">Estado del producto</label>
                                <select class="form-control custom-select-user" id="estado" name="estado" required>
                                    <option value="" disabled>Selecciona un estado</option>
                                    <option value="1" <?php echo ($prod['estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                    <option value="0" <?php echo ($prod['estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                            
                            <hr>
                            
                            <button type="submit" class="btn btn-primary btn-user btn-block shadow-sm">
                                <i class="fas fa-save mr-2"></i>Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>