<?php
namespace apps\productos\controladores;

use apps\productos\modelos\categoriaModel;

class CategoriaController {
    
    // Metodo para mostrar la lista de categorías
    public function index() {
        $modelo = new categoriaModel();
        $categorias = $modelo->getAll(); 
        
        // Llamamos a la vista donde se van a mostrar la categorias
        require_once __DIR__ . '/../vistas/listar_categorias.php';
    }

    public function crear() {
        require_once __DIR__ . '/../vistas/crear_categoria.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Empaquetamos los datos en un arreglo asociativo

            $datos = [
                'nombre'      => $_POST['nombre'],
                'descripcion' => $_POST['descripcion']
            ];

            $modelo = new categoriaModel();
          try {
                // Intentamos hacer la inserción mágica
                $modelo->insert($datos); 
                
                // Si todo sale bien, regresamos a la lista
                header("Location: index.php?modulo=categorias&accion=listar");
                exit();
                
            } catch (\PDOException $e) {
                // ATRAPAMOS EL ERROR DE MARIADB
                if ($e->getCode() == 23000) {
                   
                    echo "<!DOCTYPE html>
                    <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body style='background-color: #f8f9fc;'> <script>
                            Swal.fire({
                                icon: 'warning',
                                title: '¡Categoría Duplicada!',
                                text: 'Ya existe una categoría llamada \"{$datos['nombre']}\". Por favor, intenta con otro.',
                                confirmButtonColor: '#4e73df', 
                                confirmButtonText: '<i class=\"fas fa-arrow-left\"></i> Volver a intentar'
                            }).then((result) => {
                                // Cuando el usuario le da clic a Volver lo regresamos al formulario
                                window.history.back();
                            });
                        </script>
                    </body>
                    </html>";
                } else {
                    echo "Ocurrió un error en la base de datos: " . $e->getMessage();
                }
                exit();
            }
        }
    }


    //esta funcion hace que cuando demo clic en el boton editar muestre el formualrio para editar la informacion
    public function editar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $modelo = new categoriaModel();
            
            $categoria = $modelo->getById('id_categoria', $id); 
            require_once __DIR__ . '/../vistas/editar_categoria.php';
        } else {
            header("Location: index.php?modulo=categorias&accion=listar");
        }
    }
  
    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $modelo = new categoriaModel();
            // Le pasamos el nombre de la columna llave primaria y el id
          try {
                // Intentamos borrar usando el método genérico de tu compañero
                $modelo->delete('id_categoria', $id);
                
                // Si se elimina con éxito, redirigimos de inmediato
                header("Location: index.php?modulo=categorias&accion=listar");
                exit();
                
            } catch (\PDOException $e) {
                // Si la base de datos lanza un código 23000, significa que hay productos usando esta categoría
                if ($e->getCode() == 23000) {
                    echo "<!DOCTYPE html>
                    <html lang='es'>
                    <head>
                        <meta charset='UTF-8'>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body style='background-color: #f8f9fc;'>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡No se puede eliminar!',
                                text: 'Esta categoría tiene productos asociados en el inventario. Para borrarla, primero debes cambiar o eliminar esos productos.',
                                confirmButtonColor: '#e74a3b', // Color rojo de peligro
                                confirmButtonText: 'Entendido'
                            }).then(() => {
                                window.location.href = 'index.php?modulo=categorias&accion=listar';
                            });
                        </script>
                    </body>
                    </html>";
                } else {
                    echo "Error al intentar eliminar: " . $e->getMessage();
                }
                exit();
            }
        } else {
            header("Location: index.php?modulo=categorias&accion=listar");
            exit();
        }

    }

    //esta se encarga cuando estamo editando categorias de recibir y guardar los cambios mdiante un Update a la db
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_categoria']; // Recibimos el ID oculto
            $datos = [
                'nombre'      => $_POST['nombre'],
                'descripcion' => $_POST['descripcion']
            ];

            $modelo = new categoriaModel();
            try {
               
                $modelo->update($datos, 'id_categoria', $id);
                header("Location: index.php?modulo=categorias&accion=listar");
                exit();
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "<!DOCTYPE html><html lang='es'><head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'>
                    
                    </script></head><body style='background-color: #f8f9fc;'>
                    <script>Swal.fire({icon: 'warning', title: '¡Nombre Duplicado!',
                     text: 'Ya existe otra categoría llamada \"{$datos['nombre']}\".',
                      confirmButtonColor: '#4e73df', confirmButtonText: '<i class=\"fas fa-arrow-left\">
                      </i> Volver'}).then(() => { window.history.back(); });</script></body></html>";
                }
                exit();
            }
        }
    }
}