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
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
             header("Location: index.php?modulo=productos&accion=listar");
            exit();
        }else{
            echo "todo mal";

        }


}
        
}
