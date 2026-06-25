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
     *  Traer todos los clientes activos para el select de la factura
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
     *  Buscar un producto en el inventario por código o nombre para la realizacion de la venta
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

    //esta funcion es la que se encargara de registra la venta tanto en la tabla ventas junto a su detalle_ventas
    public function registrarVenta($cliente_id, $usuario_id, $productos) {
        try {
            // Iniciamos la transacción, esto no indica que si algo sale mal no se hara la transcaccion y si todo esta bien ahi si.
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

    //esta es la funcion que se encarga de obtener el historial de ventas directamente de la base de datos
    public function obtenerHistorialVentas() {
        try {
            // Relacionamos usando un left join 3 tablas ventas, clientes y usuarios
            $sql = "SELECT v.id_venta, v.fecha_venta, v.total, 
                           c.nombre_completo AS cliente, 
                           u.nombre_completo AS cajero
                    FROM ventas v
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
                    ORDER BY v.fecha_venta DESC"; // Las ventas mas recientes primero
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    //obtendra los datos especificos de cada venta
    public function obtenerInfoVenta($id_venta) {
        try {
            $sql = "SELECT v.*, c.nombre_completo AS cliente, c.telefono, u.nombre_completo AS cajero 
                    FROM ventas v
                    LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                    LEFT JOIN usuarios u ON v.id_usuario = u.id_usuario
                    WHERE v.id_venta = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id_venta]);
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Usar fetch porque es un solo registro
        } catch (\PDOException $e) {
            return false;
        }
    }

    // Obtiene la lista de zapatos que se compraron en ese transaccion
    public function obtenerDetallesVenta($id_venta) {
        try {
            // Unimos detalle_ventas con productos para sacar el nombre y código del calzado
            $sql = "SELECT d.*, p.codigo, p.nombre 
                    FROM detalle_ventas d
                    INNER JOIN productos p ON d.id_producto = p.id_producto
                    WHERE d.id_venta = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id_venta]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); // fetchAll porque son varios productos
        } catch (\PDOException $e) {
            return [];
        }
    }




}