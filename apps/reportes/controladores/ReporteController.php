<?php
namespace apps\reportes\controladores;
use apps\reportes\modelos\ReporteModel;

class ReporteController {
    private $model;

    public function __construct() {
        // Asumiendo que pasas la conexión PDO al modelo
        $this->model = new ReporteModel(); 
    }

    public function index() {
    // Capturar fechas si el formulario fue enviado, si no, usar el mes actual
    $fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
    $fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-t');

    // Consultar los datos al modelo
    $total_vendido = $this->model->getTotalVendido($fecha_inicio, $fecha_fin);
    $ventas_por_fecha = $this->model->getVentasPorFecha($fecha_inicio, $fecha_fin);
    $bajo_stock = $this->model->getProductosBajoStock();
    $mas_vendidos = $this->model->getProductosMasVendidos();

    // Cargar la vista incrustada dentro de tu diseño global usando rutas absolutas seguras
    require_once __DIR__ . '/../../../layouts/header.php';
    require_once __DIR__ . '/../vistas/index.php';
    require_once __DIR__ . '/../../../layouts/footer.php';
}
}