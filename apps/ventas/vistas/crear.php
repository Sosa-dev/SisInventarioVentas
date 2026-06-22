<?php
use apps\ventas\controladores\VentaController;

// Instanciamos el controlador de ventas
$ventaController = new VentaController();

// Llamamos a un método del controlador para que nos devuelva los clientes disponibles
$clientes = $ventaController->obtenerClientesVenta();


?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Venta</h1>
        <a href="index.php?modulo=ventas&accion=listar" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Historial
        </a>
    </div>

    <?php if (isset($_SESSION['error_mensaje'])): ?>
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            <?php 
                echo $_SESSION['error_mensaje']; 
                unset($_SESSION['error_mensaje']); 
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['venta_exitosa'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: '<?php echo $_SESSION['venta_exitosa']; ?>',
                    confirmButtonColor: '#1cc88a'
                });
            });
        </script>
        <?php unset($_SESSION['venta_exitosa']); ?>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-lg-6">
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos del Cliente</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="cliente_id">Seleccione un cliente:</label>
                        <select class="form-control" id="cliente_id" name="cliente_id">
                            <option value="">-- Consumidor Final --</option>
                            <?php if(!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo htmlspecialchars($cliente['id_cliente']); ?>">
                                        <?php echo htmlspecialchars($cliente['nombre_completo']); ?> - Tel: <?php echo htmlspecialchars($cliente['telefono']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Buscar Producto</h6>
                </div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" id="criterioBusqueda" placeholder="Ingrese código o nombre (Ej. Nike)">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn-buscar-producto">
                                    <i class="fas fa-search fa-sm"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="resultados-busqueda" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Detalle de Factura</h6>
                    <button class="btn btn-danger btn-sm" id="btn-vaciar-carrito" title="Vaciar Carrito">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Producto</th>
                                    <th width="15%">Cant.</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody id="lista-carrito">
                                <tr id="fila-vacia">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No hay productos agregados a la venta.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h3 class="font-weight-bold text-gray-800 mb-0">Total: $<span id="total-venta">0.00</span></h3>
                        
                        <form action="index.php?modulo=ventas&accion=procesar" method="POST" id="form-procesar-venta">
                            <input type="hidden" name="cliente_final_id" id="cliente_final_id" value="">
                            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    
                            <input type="hidden" name="productos_carrito" id="productos_carrito" value="">
    
                            <button type="submit" class="btn btn-success btn-lg shadow-sm" id="btn-procesar" disabled>
                                <i class="fas fa-check-circle"></i> Procesar Venta
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


<script>
    //javasricpt para hacer que el producto se agregue a la factura

    //crear donde se guardaran los productos
    let carrito =[];
    
    //con esto buscamos el producto con el buscador
    document.addEventListener('DOMContentLoaded', function() {
        const btnBuscar = document.getElementById('btn-buscar-producto');
        const inputCriterio = document.getElementById('criterioBusqueda');
        const contenedorResultados = document.getElementById('resultados-busqueda');

        btnBuscar.addEventListener('click', function() {
            let criterio = inputCriterio.value;
            if(criterio.trim() === '') {
                contenedorResultados.innerHTML = '<div class="alert alert-danger small"> Escriba un codigo o nombre de calzado</div>';
                return;
            }

            let formData = new FormData();
            formData.append('termino', criterio);

            fetch('index.php?modulo=ventas&accion=buscarProducto', {
                method: 'POST',
                body: formData

            })
            .then(response => response.text())
            .then(html => {
                contenedorResultados.innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
        });

        //evento para vacia el carrito con el boton rojo de basura
        document.getElementById('btn-vaciar-carrito').addEventListener('click', function() {
            if(confirm("¿Estas seguro de vaciar toda la factura?")) {
                carrito = [];
                actualizarTablaCarrito();
            }
        });
        
        //aca antes de enviar el formulario, js debe copiar el cliente al formulario de envio
        document.getElementById('form-procesar-venta').addEventListener('submit', function(e) {
            // Si el carrito está vacío, frenamos el proceso y no enviamos nada a PHP
            if (carrito.length === 0) {
                e.preventDefault();
                alert("Agregue al menos un zapato a la factura.");
                return;
            }

            //Copiamos el cliente seleccionado
            let clienteSeleccionado = document.getElementById('cliente_id').value;
            document.getElementById('cliente_final_id').value = clienteSeleccionado;

            
            // Forzamos a que el arreglo se convierta en texto y entre a la cajita
            //  un instante antes de que el formulario viaje al servidor.
            document.getElementById('productos_carrito').value = JSON.stringify(carrito);
            
        });
});


// funcion para meter los producto o zapatos al carrito
function agregarAlCarrito(id, nombre, precio, stock) {
    //verificacion si el producto ya esta en la lista
    let productoExistente = carrito.find(item => item.id === id);

    if (productoExistente) {
        // si ya esta y aun hay stock le sumamos de uno en uno a la cantidad que tenemos
        if (productoExistente.cantidad < stock) {
            productoExistente.cantidad++;
        } else {
            alert("¡No puedes agregar más! El stock máximo es de " + stock + " pares.");
            return;
        } 
    } 
    else {
        //si no existe lo ingresamo como producto nuevo a la factura
    carrito.push({
        id: id,
        nombre : nombre,
        precio: parseFloat(precio),
        cantidad : 1,
        stock: parseInt(stock)
    });
}
//limpiar el buscador
document.getElementById('resultados-busqueda').innerHTML = '';
document.getElementById('criterioBusqueda').value = '';
document.getElementById('criterioBusqueda').focus();

//ahora acutalizamos la tabla de la factura
actualizarTablaCarrito();


} 


//funcion para redibujar la tabla de la facutura

function actualizarTablaCarrito() {
    let tbody = document.getElementById('lista-carrito');
    let spanTotal = document.getElementById('total-venta');
    let btnProcesar = document.getElementById('btn-procesar');
    let inputOcultoCarrito = document.getElementById('producto_carrito');

    //vaciar el html de la tabla ante de volverla a pintar

    tbody.innerHTML = '';
    let totalGeneral = 0;

    //en caso de que nos quedemos sin producto mostramos el mensaje\
    if (carrito.length === 0) {
        tbody.innerHTML = '<tr id="fila-vacia"><td colspan="5" class="text-center text-muted py-4">No hay productos agregados a la venta.</td></tr>';
        spanTotal.textContent = '0.00';
        btnProcesar.disabled = true; // Apagamos el botón
        inputOcultoCarrito.value = '';
        return;
    }

    //si hay productos, recorremos uno por nuo
    carrito.forEach((item, index) => {
        let subtotal = item.precio * item.cantidad;
        totalGeneral += subtotal;

        let tr = document.createElement('tr');
        tr.innerHTML = `
        <td class="align-middle font-weight-bold">${item.nombre}</td>
            <td class="align-middle">
                <input type="number" class="form-control form-control-sm text-center" value="${item.cantidad}" min="1" max="${item.stock}" onchange="cambiarCantidad(${index}, this.value)">
            </td>
            <td class="align-middle">$${item.precio.toFixed(2)}</td>
            <td class="align-middle font-weight-bold text-primary">$${subtotal.toFixed(2)}</td>
            <td class="align-middle text-center">
                <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="eliminarDelCarrito(${index})">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    //actualizar los total y encender el boton procesar
    spanTotal.textContent = totalGeneral.toFixed(2);
    btnProcesar.disabled = false;

    //convertir el arreglo a texto y lo escondemos en el formulario para php
    inputOcultoCarrito.value = JSON.stringify(carrito);

   
}

 //funcion para cambiar la cantidad manual en la cajita
    function cambiarCantidad(index, nuevaCantidad) {
        let cant = parseInt(nuevaCantidad);
        let stockMaximo = carrito[index].stock;

        if (cant > stockMaximo) {
            alert("Stock insuficiente. Solo hay " + stockMaximo + " disponibles en bodega.");
            carrito[index].cantidad = stockMaximo;
        } else if (cant < 1 || isNaN(cant)) {
            carrito[index].cantidad = 1;
        } else {
            carrito[index].cantidad =  cant;
        }
        actualizarTablaCarrito();
    }

    //funcion para quitar un producto
    function eliminarDelCarrito(index) {
        //borramos con splice del arreglo
        carrito.splice(index, 1);
        actualizarTablaCarrito();
    }


 

</script>