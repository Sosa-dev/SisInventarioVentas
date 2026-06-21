<?php
namespace apps\productos\modelos;

// Traemos la conexión de la carpeta global
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../../../config/Model.php';


class categoriaModel extends \Model{
    protected $table = 'categorias';


}