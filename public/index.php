<?php
session_start();
// --- SIMULADOR DE LOGIN TEMPORAL PARA PRUEBAS ---
// Autoload para cargar clases automáticamente
spl_autoload_register(function($clase){
    $rutaBase = __DIR__ . '/../';
    $archivo = $rutaBase . str_replace('\\', '/', $clase) . '.php';
    if (file_exists($archivo)) {
        require_once $archivo;
    }
});

// Determinar módulo y acción desde la URL
$modulo = $_GET['modulo'] ?? 'usuarios';
$accion = $_GET['accion'] ?? 'login';
$parametro = $_GET['id'] ?? null;


// Función que niega o protege las rutas según los permisos del usuario (Soporta múltiples roles)
function is_auth_role($roles_permitidos) {
    // 1. Validar si existe la sesión
    if (!isset($_SESSION['usuario_rol'])) {
        $_SESSION['error_mensaje'] = "Debes iniciar sesión para acceder.";
        header("Location: index.php?modulo=usuarios&accion=login");
        exit();
    }

    if ($_SESSION['usuario_rol'] == 1) {
        return; 
    }

    // 3. Si pasas un solo rol (ej: 2) en vez de un array, lo convertimos en array [2]
    if (!is_array($roles_permitidos)) {
        $roles_permitidos = [$roles_permitidos];
    }

    // 4. Comprobamos si el rol del usuario NO está dentro del arreglo de roles permitidos
    if (!in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
        $_SESSION['error_mensaje'] = "No tienes permitida esta acción.";
        header("Location: index.php?modulo=dashboard&accion=home");
        exit();
    }
}

    


// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Determinar módulo y acción desde la URL
$modulo = $_GET['modulo'] ?? 'usuarios';
$accion = $_GET['accion'] ?? 'login';
$method = $_SERVER['REQUEST_METHOD'];


if($modulo ==='usuarios'){
    switch ($method){
        case 'GET':
            if($accion=== 'login'){
                require_once __DIR__ . '/../apps/usuarios/vistas/login.php';
            }
            elseif($accion === 'logout') {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Limpiamos el arreglo de sesión
                $_SESSION = array();
                
                // Destruimos la cookie de sesión en el navegador si existe
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params["path"], $params["domain"],
                        $params["secure"], $params["httponly"]
                    );
                }
                
                // Destruimos la sesión en el servidor
                session_destroy();
                
                // Redireccionamos con un mensaje de éxito
                session_start();
                header("Location: index.php?modulo=usuarios&accion=login");
                exit();
            }
            elseif($accion === 'listar') {
                is_auth_role(1);
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/usuarios/vistas/listar.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion === 'editar') {
                is_auth_role(1);
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/usuarios/vistas/editar.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion === 'registro') {
                is_auth_role(1);
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/usuarios/vistas/registro.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            break;
        case 'POST':
            if ($modulo === 'usuarios' && $accion === 'login') {
                $controller = new \apps\usuarios\controladores\UsuarioController();
                $controller->procesarLogin();
            }
            elseif ($modulo === 'usuarios' && $accion === 'eliminar') {
                is_auth_role(1);
                $controller = new \apps\usuarios\controladores\UsuarioController();
                $controller->eliminarUsuarios($_POST['usuario_id'] ?? null);
            }
            elseif ($modulo === 'usuarios' && $accion === 'procesar') {
                is_auth_role(1);
                $controller = new \apps\usuarios\controladores\UsuarioController();
                $controller->procesarUpdate();
            }
            elseif ($modulo === 'usuarios' && $accion === 'editar') {
                is_auth_role(1);
                $controller = new \apps\usuarios\controladores\UsuarioController();
                $controller->editar();
            }
            elseif ($modulo === 'usuarios' && $accion === 'registro') {
                is_auth_role(1);
                $controller = new \apps\usuarios\controladores\UsuarioController();
                $controller->guardar();
            }
            break;
    }
}elseif($modulo==='productos'){
    $parametro = $_GET['producto_id'] ?? $_GET['id'] ?? null;
    switch($method){
        case 'GET':
                is_auth_role(1);
            if($accion==='listar'){
                require_once __DIR__ . '/../layouts/header.php';
                    require_once __DIR__ . '/../apps/productos/vistas/listar.php';
                    require_once __DIR__ . '/../layouts/footer.php';
                }elseif($accion==='guardar'){
                    require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/productos/vistas/ingreso.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion==='editar'){
                require_once __DIR__ . '/../layouts/header.php';
                $productoController = new apps\productos\controladores\productoController();
                $prod = $productoController->getOne($parametro); 
                require_once __DIR__ . '/../apps/productos/vistas/editar.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            break;
        case 'POST':
                is_auth_role(1);
            if($accion==='guardar'){
                $controller = new \apps\productos\controladores\productoController();
                $controller->guardar();
            }
            elseif($accion==='editar'){
                $controller = new \apps\productos\controladores\productoController();
                $controller->editar();
            }
            // elseif($accion==='eliminar'){
            //     $controller = new \apps\productos\controladores\productoController();
            //     $controller->eliminar();
            // }

            break;
    }
}elseif($modulo==='dashboard'){
    switch($method){
        case 'GET':
            if($accion==='home'){
                require_once __DIR__ . '/../layouts/header.php';
                if($_SESSION['usuario_rol']==1){

                    require_once __DIR__ . '/../layouts/home.php';
                }elseif($_SESSION['usuario_rol']==2){
                    require_once __DIR__ . '/../layouts/homeVenta.php';

                }elseif($_SESSION['usuario_rol']==3){
                    require_once __DIR__ . '/../layouts/homeReporte.php';

                }
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion==='reportes'){
                is_auth_role(3);
                $controller = new \apps\reportes\controladores\ReporteController();
                $controller->index();
            }
            elseif($accion==='historial'){
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/reportes/vistas/ventas.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            
    }
}elseif($modulo==='ventas'){
    switch($method){
        case 'GET':
            if($accion==='crear'){
                is_auth_role(2);
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/ventas/vistas/crear.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion==='historial'){
                is_auth_role([2,3]);
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/ventas/vistas/historial.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif($accion==='detalle'){
                is_auth_role([2,3]);
                //$id_venta = $parametro;
                require_once __DIR__ . '/../layouts/header.php';
                require_once __DIR__ . '/../apps/ventas/vistas/detalle.php';
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif ($accion === 'buscarProducto') {
                is_auth_role(2);
                $controller = new \apps\ventas\controladores\VentaController();
                $controller->buscarProducto();
                exit();
            }

            break;
        case 'POST':
            is_auth_role(2);
            //agregado para ventas, buscar producto
            if ($modulo === 'ventas'&& $accion === 'buscarProducto') {
                $controller = new \apps\ventas\controladores\VentaController();
                $controller->buscarProducto();
                exit();
            } 

            elseif($accion == 'procesar') {
                $controller = new \apps\ventas\controladores\VentaController();
                $controller->procesar();
            }
        }
}elseif($modulo==='categorias'){

    switch($method){
        case 'GET':
            is_auth_role(1);
            if ($accion === 'listar') {
                require_once __DIR__ . '/../layouts/header.php';
        
                $controller = new \apps\productos\controladores\CategoriaController();
                $controller->index();
                require_once __DIR__ . '/../layouts/footer.php';
            }

            elseif ($accion === 'crear') {
                require_once __DIR__ . '/../layouts/header.php';
                $controller = new \apps\productos\controladores\CategoriaController();
                $controller->crear();
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif ($accion === 'editar') {
                require_once __DIR__ . '/../layouts/header.php';
                $controller = new \apps\productos\controladores\CategoriaController();
                $controller->editar();
                require_once __DIR__ . '/../layouts/footer.php';
            }
            elseif ($accion === 'eliminar') {
            $controller = new \apps\productos\controladores\CategoriaController();
            $controller->eliminar();
            exit();
        }
            break;
        case 'POST':
            is_auth_role(1);
            if ($accion === 'guardar') {
                $controller = new \apps\productos\controladores\CategoriaController();
                $controller->guardar();
               
                exit();
            }
            elseif ($accion === 'actualizar') {
            $controller = new \apps\productos\controladores\CategoriaController();
            $controller->actualizar();
                exit();
            }
    }


    
    

}

else {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/404.php';
    require_once __DIR__ . '/../layouts/footer.php';
}


     
 


        // require_once __DIR__ . '/../layouts/header.php';
        // require_once __DIR__ . '/../layouts/404.php';
        // require_once __DIR__ . '/../layouts/footer.php';




    // elseif ($modulo === 'dashboard' && $accion === 'home'){
    //     require_once __DIR__ . '/../layouts/header.php';
    //     require_once __DIR__ . '/../layouts/home.php';
    //     require_once __DIR__ . '/../layouts/footer.php';

    // }
    // elseif ($modulo === 'productos' && $accion === 'listar'){
    //         
    //     }

    /* elseif ($modulo === 'inventario' && $accion === 'lista') {
        // Aquí irás agregando tus módulos reales del sistema más adelante
        require_once __DIR__ . '/../layouts/header.php';
        require_once __DIR__ . '/../apps/inventario/vistas/lista.php';
        require_once __DIR__ . '/../layouts/footer.php';
    } 
    */
    



//agregado para ventas
// elseif ($modulo === 'ventas' && $accion === 'crear') {
//     require_once __DIR__ . '/../layouts/header.php';
//     require_once __DIR__ . '/../apps/ventas/vistas/crear.php';
//     require_once __DIR__ . '/../layouts/footer.php';
// }

// elseif ($modulo === 'ventas' && $accion ==='historial') {
//     require_once __DIR__ . '/../layouts/header.php';
//     require_once __DIR__ . '/../apps/ventas/vistas/historial.php';
//     require_once __DIR__ . '/../layouts/footer.php';
// }

// elseif ($modulo === 'ventas' && $accion === 'detalle') {
//     require_once __DIR__ . '/../layouts/header.php';
//     require_once __DIR__ . '/../apps/ventas/vistas/detalle.php';
//     require_once __DIR__ . '/../layouts/footer.php';
// }

/* elseif ($modulo === 'inventario' && $accion === 'lista') {
    // Aquí irás agregando tus módulos reales del sistema más adelante
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/inventario/vistas/lista.php';
    require_once __DIR__ . '/../layouts/footer.php';
} 
*/


