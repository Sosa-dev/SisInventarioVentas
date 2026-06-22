<?php
namespace apps\ventas\modelos;

// Traemos la conexión exactamente como lo hace el modelo de usuarios
require_once __DIR__ . '/../../../config/conexion.php';

use PDO;
use PDOException;

class ventaModel {
    private $db;

    public function __construct() {
        // Instanciamos la clase de conexión 
        $this->db = \Conexion::conectar();
    }

    /**
     * 1. Traer todos los clientes activos para el select de la factura
     */
    public function obtenerClientesVenta() {
        try {
            $sql = "SELECT id_cliente, nombre_completo, telefono FROM clientes ORDER BY nombre_completo ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * 2. Buscar un producto en el inventario por código o nombre para la realizacion de la venta
     */
    public function buscarProducto($terminoBusqueda) {
        try {
            // Buscamos coincidencias en zapatos y verificamos que haya stock
            $sql = "SELECT * FROM productos WHERE (codigo LIKE :termino1 OR nombre LIKE :termino2) AND estado = 1 AND stock > 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'termino1' => "%$terminoBusqueda%",
                'termino2' => "%$terminoBusqueda%"
                ]);
            

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    //esta funcion es la que se encargara de registra la venta tabnto el la tabla ventas junto a su detalle_ventas
    public function registrarVenta($cliente_id, $usuario_id, $productos) {
        try {
            // Iniciamos la transacción: esto no indica que si algo sale mal no se hara la transcaccion y si todo esta bien ahi si.
            $this->db->beginTransaction();

            //  Calcular totales Aplicamos un IVA estándar del 13%
            $total = 0;
            foreach ($productos as $prod) {
                $total += ($prod['precio'] * $prod['cantidad']);
            }
            //los precios que manejamos ya tiene incluido el iva osea los que ve el cliente
            //solo lo desempaquetamos
            $subtotal = $total / 1.13; 
            $iva = $total - $subtotal;

            // Si el cliente viene vacío , lo volvemos NULL para que no dé error
            $cliente_id = empty($cliente_id) ? null : $cliente_id;

            //  Insertar en tabla ventas
            $sqlVenta = "INSERT INTO ventas (id_cliente, id_usuario, subtotal, iva, total) 
                         VALUES (:cliente, :usuario, :subtotal, :iva, :total)";
            $stmtVenta = $this->db->prepare($sqlVenta);
            $stmtVenta->execute([
                'cliente' => $cliente_id,
                'usuario' => $usuario_id,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total
            ]);

            // Obtenemos el ID de la venta que se acaba de crear para registrar su detalle
            $id_venta = $this->db->lastInsertId();

            //  Preparar consultas para detalle_ventas y descontar stock en productos
            $sqlDetalle = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal_linea) 
                           VALUES (:venta, :producto, :cantidad, :precio, :subtotal_linea)";
            $stmtDetalle = $this->db->prepare($sqlDetalle);

            $sqlStock = "UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :producto";
            $stmtStock = $this->db->prepare($sqlStock);

            //  Recorrer el carrito para guardar detalles y restar stock
            foreach ($productos as $prod) {
                $subtotal_linea = $prod['precio'] * $prod['cantidad'];

                $stmtDetalle->execute([
                    'venta' => $id_venta,
                    'producto' => $prod['id'],
                    'cantidad' => $prod['cantidad'],
                    'precio' => $prod['precio'],
                    'subtotal_linea' => $subtotal_linea
                ]);

                $stmtStock->execute([
                    'cantidad' => $prod['cantidad'],
                    'producto' => $prod['id']
                ]);
            }

            // Confirmamos y guardamos todo permanentemente
            $this->db->commit();
            return true;

        } catch (\PDOException $e) {
            // Si algo falla o un error SQL deshacemos todo
            $this->db->rollBack();
            return $e->getMessage();
        }
    }



}