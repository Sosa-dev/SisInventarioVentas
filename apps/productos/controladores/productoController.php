<?php
namespace apps\productos\controladores;
use apps\productos\modelos\productoModel;
use apps\productos\modelos\categoriaModel;

class productoController{

    public function listar(){
    $productoModel = new productoModel();   
    return $productoModel->list_productos_categoria(); 
    }

    public function categoria(){
        $categoriaModel = new categoriaModel();
        return $categoriaModel->getALL();
    }

    public function getOne($parametro){
        $productoModel = new productoModel(); 
        $id_columna='id_producto';
        return $productoModel->getById($id_columna, $parametro);
    }

    // public function eliminar(){
    //     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
    //         $_SESSION['error_mensaje'] = "Error de seguridad: Token CSRF inválido.";
    //         header("Location: index.php?modulo=productos&accion=listar");
    //         exit();
    //     }

    //     $id_producto_eliminar = $_POST['producto_id'];
    //     $id_columna = 'id_producto';
    //     if(empty($id_producto_eliminar)){
    //         $_SESSION['error_mensaje'] = "Error al eliminar producto";
    //         header("Location: index.php?modulo=productos&accion=listar");
    //         exit();
    //     }

    //     $productoModel = new productoModel(); 
    //     $respuesta = $productoModel->delete($id_columna, $id_producto_eliminar);

    //     if($respuesta){
    //         $_SESSION['success_mensaje'] = "Producto Eliminado exitosamente.";
    //             $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    //              header("Location: index.php?modulo=productos&accion=listar");
    //             exit();
    //     }
    //     else{
    //         $_SESSION['error_mensaje'] = "Error al Eliminar el producto.";
    //         header("Location: index.php?modulo=productos&accion=listar");
    //         exit();
    //     }


    // }

    public function editar(){
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            $_SESSION['error_mensaje'] = "Error de seguridad: Token CSRF inválido.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }
        $id_categoria = trim($_POST['id_categoria']?? '');
        $codigo = trim($_POST['codigo']?? '');
        $nombre = trim($_POST['nombre']?? '');
        $precio = trim($_POST['precio']?? '');
        $stock = trim($_POST['stock']?? '');
        $estado = trim($_POST['estado']==1 ? true : false);
        $id_producto = trim($_POST['id_producto']);

        if (empty($id_categoria)|| empty($codigo)||empty($nombre)||empty($precio)||empty($stock)){
            $_SESSION['error_mensaje'] = "Todos los campos tienen que estar completos.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }

        if ($stock<0 || $precio<0){
            $_SESSION['error_mensaje'] = "Precio o Stock tienen que ser mayor a 0.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }

        $id_columna = 'id_producto';
        $datos = [
            'id_categoria' =>$id_categoria,
            'codigo' =>$codigo,
            'nombre' =>$nombre,
            'precio' =>$precio,
            'stock' =>$stock,
            'estado' =>$estado
        ];
        $productoModel = new productoModel();
        $insert=$productoModel->update($datos, $id_columna, $id_producto);

        if($insert){
            $_SESSION['success_mensaje'] = "Producto editado exitosamente.";
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
             header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }else{
            $_SESSION['error_mensaje'] = "Error al editar el producto.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();

        }
    }

    public function guardar(){
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            $_SESSION['error_mensaje'] = "Error de seguridad: Token CSRF inválido.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }
        $id_categoria = trim($_POST['id_categoria']?? '');
        $codigo = trim($_POST['codigo']?? '');
        $nombre = trim($_POST['nombre']?? '');
        $precio = trim($_POST['precio']?? '');
        $stock = trim($_POST['stock']?? '');
        $estado = trim($_POST['estado']?? '');

        if (empty($id_categoria)|| empty($codigo)||empty($nombre)||empty($precio)||empty($stock)){
            $_SESSION['error_mensaje'] = "Todos los campos tienen que estar completos.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }

        if ($stock<0 || $precio<0){
            $_SESSION['error_mensaje'] = "Precio o Stock tienen que ser mayor a 0.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }

        $datos = [
            'id_categoria' =>$id_categoria,
            'codigo' =>$codigo,
            'nombre' =>$nombre,
            'precio' =>$precio,
            'stock' =>$stock,
            'estado' =>$estado
        ];
        $productoModel = new productoModel();
        $insert=$productoModel->insert($datos);

        if($insert){
            $_SESSION['success_mensaje'] = "Producto Agregado exitosamente.";
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
             header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }else{
            $_SESSION['error_mensaje'] = "Error al guardar el producto.";
            header("Location: index.php?modulo=productos&accion=listar");
            exit();

        }


}
        
}



class CategoriaController {

    public function index() {
        //muestra la lista de categorias
        $modelo = new categoriaModel();
        $categorias = $modelo->getAll(); 
        
        require_once __DIR__ . '/../vistas/listar_categorias.php';
    }
    
    // Método para mostrar el formulario
    public function crear() {
        require_once __DIR__ . '/../vistas/crear_categoria.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre'      => $_POST['nombre'],
                'descripcion' => $_POST['descripcion']
            ];

            $modelo = new categoriaModel();
            $modelo->insert($datos); 
            
            header("Location: index.php?modulo=categorias&accion=listar");
            exit();
        }

    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $modelo = new categoriaModel();
            $modelo->delete('id_categoria', $id);
        }
        header("Location: index.php?modulo=categorias&accion=listar");
        exit();
    }

}
