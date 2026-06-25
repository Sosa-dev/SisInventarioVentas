<?php
/**
 * @var array $categoria datos de la categoría enviados desde el controlador
 */
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-primary"></i> Editar Categoría
        </h1>
        <a href="index.php?modulo=categorias&accion=listar" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left"></i> Cancelar y Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Actualizar Datos de la Categoría</h6>
        </div>
        <div class="card-body">
            <form action="index.php?modulo=categorias&accion=actualizar" method="POST">
                
                <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">

                <div class="form-group">
                    <label for="nombre" class="font-weight-bold text-dark">
                        Nombre de la Categoría <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                           value="<?php echo htmlspecialchars($categoria['nombre']); ?>"
                           placeholder="Ej: Calzado Deportivo">
                </div>

                <div class="form-group mb-4">
                    <label for="descripcion" class="font-weight-bold text-dark">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                              placeholder="Agrega detalles sobre esta categoría..."><?php echo htmlspecialchars($categoria['descripcion']); ?></textarea>
                </div>

                <hr>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-sync-alt"></i> Actualizar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>