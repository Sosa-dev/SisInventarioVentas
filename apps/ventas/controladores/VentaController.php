<?php
namespace apps\ventas\controladores;

use apps\ventas\modelos\ventaModel;

class VentaController {
    
    /**
     * Método para cargar la interfaz principal de la pantalla de ventas
     */
    public function crear() {
        //se dejara vacia porque el index se encarga de armar la vista
      
    }

    /**
     * Sirve para enviarle los clientes a la vista crear.php
     */
    public function obtenerClientesVenta() {
        // Instanciamos el modelo y traemos los clientes
        $modelo = new ventaModel();
        return $modelo->obtenerClientesVenta();
    }

    /**
     * Método reservado para cuando busquemos el producto con el botón
     */
    public function buscarProducto() {
        // verificar si el javascript envio la palabra buscar que viene del archivo crear.php

        if(!isset($_POST['termino']) || trim($_POST['termino']) === '') {
            echo '<div class="alert alert-danger small"><i class="fas fa-exclamation-circle"></i> Escriba un término válido.</div>';
            exit();
        }
     
            $termino = trim($_POST['termino']);

            //instanciar el modelo y buscar el calzado
        $modelo = new ventaModel();
        $productos = $modelo->buscarProducto($termino);

        //cadazor de errores de base de datos
        if (isset($productos['error'])) {
                echo '<div class="alert alert-danger small"><i class="fas fa-exclamation-triangle"></i> Error SQL: ' . $productos['error'] . '</div>';
                exit();
            }
        //si encuentra, armar un lista con html y boostrap
        if(!empty($productos)) {
            $html = '<div class="list-group">';
            foreach ($productos as $prod) {
                $html .= '<button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-1 shadow-sm border-left-primary" onclick="agregarAlCarrito(' . $prod['id_producto'] . ', \'' . htmlspecialchars($prod['nombre'], ENT_QUOTES) . '\', ' . $prod['precio'] . ', ' . $prod['stock'] . ')">';
                
                $html .= '<div>';
                $html .= '<strong>' . htmlspecialchars($prod['codigo']) . '</strong> - ' . htmlspecialchars($prod['nombre']) . '<br>';
                $html .= '<small class="text-success font-weight-bold">Bodega: ' . $prod['stock'] . ' pares</small>';
                $html .= '</div>';
                    
                $html .= '<span class="badge badge-primary badge-pill p-2">$' . number_format($prod['precio'], 2) . '</span>';
                    
                $html .= '</button>';
            }
            $html .= '</div>';

            echo $html;
        } else {
                // Si no hay resultados o no hay stock
                echo '<div class="alert alert-warning small"><i class="fas fa-exclamation-triangle"></i> No se encontró el calzado o no hay stock disponible.</div>';
            }

        }

        //este metodo se encargara de recibir los datos del producto que tenemos en formatos JSON
        //para preparar la alerta de que fue exitosa la compra
        public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibimos el JSON del carrito y lo convertimos a un arreglo de PHP
            $productos_json = $_POST['productos_carrito'] ?? '[]';
            $productos = json_decode($productos_json, true);//toma texto plano del form y lo convierte a arreglo
            
            $cliente_id = $_POST['cliente_final_id'] ?? null;
            // Usamos el ID del usuario cajero que tiene la sesión iniciada
            $usuario_id = $_SESSION['usuario_id'] ?? 1; 

            // Validamos que nadie haya intentado mandar un formulario vacío
            if (empty($productos)) {
                $_SESSION['error_mensaje'] = "La factura no puede estar vacía.";
                header("Location: index.php?modulo=ventas&accion=crear");
                exit();
            }

            $modelo = new ventaModel();
            $resultado = $modelo->registrarVenta($cliente_id, $usuario_id, $productos);

            if ($resultado === true) {
                // Preparamos la variable para que se dispare la ventana bonita cuando den clic a procesar venta
                $_SESSION['venta_exitosa'] = "¡La venta se ha registrado y el inventario se actualizó!";
            } else {
                $_SESSION['error_mensaje'] = "Error en base de datos: " . $resultado;
            }
            
            // Redirigimos para recargar la página en blanco lista para otro cliente
            header("Location: index.php?modulo=ventas&accion=crear");
            exit();
        }
    }


    //aca esta el puente que usamos para tener el historial de la ventas
    //le pide al modelo ventaModel el historial y se lo pasa a la vista que lo que vemos en la pantalla
    public function obtenerHistorial() {
        $modelo = new \apps\ventas\modelos\ventaModel();
        return $modelo->obtenerHistorialVentas();
    }

    public function obtenerInfoVenta($id_venta){
        $modelo = new \apps\ventas\modelos\ventaModel();
        return $modelo->obtenerInfoVenta($id_venta);
    }

    function obtenerDetalleVenta($id_venta) {
        $modelo = new \apps\ventas\modelos\ventaModel();
        return $modelo->obtenerDetallesVenta($id_venta);
    }
        
        
    }

?>