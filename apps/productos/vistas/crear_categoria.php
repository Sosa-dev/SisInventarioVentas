<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Agregar Nueva Categoría</h1>
        <a href="index.php?modulo=categorias&accion=listar" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left"></i> Volver a la Lista
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Datos de la Categoría</h6>
        </div>
        <div class="card-body">
            <form action="index.php?modulo=categorias&accion=guardar" method="POST">
                
                <div class="form-group">
                    <label for="nombre" class="font-weight-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Tenis Deportivos" required>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="font-weight-bold">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Breve descripción de los productos que irán aquí..."></textarea>
                </div>

                <hr>
                <div class="text-right">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-save"></i> Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>