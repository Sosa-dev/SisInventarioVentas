<?php
namespace apps\productos\modelos;

// Traemos la conexión de la carpeta global
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/Model.php';


class productoModel extends \Model{
    protected $table = 'productos';

    public function list_productos_categoria(){
        $sql = "SELECT p.*, c.nombre AS categoria_nombre From productos p INNER JOIN categorias c ON p.id_categoria = c.id_categoria";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}