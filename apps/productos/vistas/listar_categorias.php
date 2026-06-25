<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Categorías</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Listado de Categorías</h6>
            <a href="index.php?modulo=categorias&accion=crear" class="btn btn-light btn-sm text-primary font-weight-bold">
                Agregar Nueva Categoría
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nombre de Categoría</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($categorias)): ?>
                            <?php foreach($categorias as $cat): ?>
                                <tr>
                                    <td class="align-middle text-center"><?php echo $cat['id_categoria']; ?></td>
                                    <td class="align-middle font-weight-bold text-dark"><?php echo htmlspecialchars($cat['nombre']); ?></td>
                                    <td class="align-middle"><?php echo htmlspecialchars($cat['descripcion']); ?></td>
                                    <td class="align-middle text-center">
                                        <a href="index.php?modulo=categorias&accion=editar&id=<?php echo $cat['id_categoria']; ?>" class="btn btn-primary btn-sm shadow-sm">Editar</a>
                                        <button type="button" class="btn btn-danger btn-sm shadow-sm" 
                                                onclick="confirmarEliminar('<?php echo $cat['id_categoria']; ?>', '<?php echo htmlspecialchars($cat['nombre']); ?>')">
                                                 Eliminar
                                        </button>                                 
                                       </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No hay categorías registradas en la base de datos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarEliminar(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Vas a eliminar la categoría '" + nombre + "'. ¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b', 
        cancelButtonColor: '#858796',  
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // PHP imprime la ruta exacta del proyecto esto para que JS no se confunda de carpeta
            let urlBase = '<?php echo $_SERVER["PHP_SELF"]; ?>';
            window.location.href = urlBase + '?modulo=categorias&accion=eliminar&id=' + id;
        }
    });
}
</script>