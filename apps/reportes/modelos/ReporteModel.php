<?php
namespace apps\reportes\modelos;
require_once __DIR__ . '/../../../config/conexion.php';
class ReporteModel {
    private $db; 
    public function __construct() {
            $this->db = \Conexion::conectar();
        }   
    // 1. Total Dinero Vendido en un rango de fechas
    // 1. Total Dinero Vendido en un rango de fechas
public function getTotalVendido($inicio, $fin) {
    $sql = "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha_venta) BETWEEN :inicio AND :fin";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':inicio', $inicio);
    $stmt->bindParam(':fin', $fin);
    $stmt->execute();
    
    $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    // Si no hay ventas, devolvemos 0 para que number_format no falle
    return $resultado['total'] ?? 0;
}

    // 2. Listado de ventas desglosado por fecha
    // 2. Listado de ventas desglosado por fecha
    public function getVentasPorFecha($inicio, $fin) {
        $sql = "SELECT * FROM ventas WHERE DATE(fecha_venta) BETWEEN ? AND ? ORDER BY fecha_venta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$inicio, $fin]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // 3. Productos con bajo stock (ej: menos de 10 unidades)
    public function getProductosBajoStock() {
        $sql = "SELECT nombre, stock FROM productos WHERE stock <= 10 ORDER BY stock ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // 4. Top 5 productos más vendidos
    public function getProductosMasVendidos() {
        $sql = "SELECT p.nombre, SUM(dv.cantidad) as total_vendido 
                FROM detalle_ventas dv 
                JOIN productos p ON dv.id_producto = p.id_producto 
                GROUP BY dv.id_producto 
                ORDER BY total_vendido DESC LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}