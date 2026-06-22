<?php
session_start();
// --- SIMULADOR DE LOGIN TEMPORAL PARA PRUEBAS ---
$_SESSION['usuario_id'] = 2;
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

// Procesar formularios POST antes de cargar vistas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($modulo === 'usuarios' && $accion === 'login') {
        $controller = new \apps\usuarios\controladores\UsuarioController();
        $controller->procesarLogin();
    }
    elseif ($modulo === 'usuarios' && $accion === 'eliminar') {
        $controller = new \apps\usuarios\controladores\UsuarioController();
        $controller->eliminarUsuarios($_POST['usuario_id'] ?? null);
    }
    elseif ($modulo === 'usuarios' && $accion === 'procesar') {
        $controller = new \apps\usuarios\controladores\UsuarioController();
        $controller->procesarUpdate();
    }
    elseif ($modulo === 'usuarios' && $accion === 'editar') {
        $controller = new \apps\usuarios\controladores\UsuarioController();
        $controller->editar();
    }
    elseif ($modulo === 'usuarios' && $accion === 'registro') {
        $controller = new \apps\usuarios\controladores\UsuarioController();
        $controller->guardar();
    }

    //agregado para ventas, buscar producto
    elseif ($modulo === 'ventas'&& $accion === 'buscarProducto') {
        $controller = new \apps\ventas\controladores\VentaController();
        $controller->buscarProducto();
        exit();
    }

    //procesar la venta
    elseif($modulo === 'ventas' && $accion == 'procesar') {
        $controller = new \apps\ventas\controladores\VentaController();
        $controller->procesar();
    }
}

// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Cargar vistas según el módulo y acción
if ($modulo === 'usuarios' && $accion === 'login') {
    require_once __DIR__ . '/../apps/usuarios/vistas/login.php';
} 
elseif ($modulo === 'usuarios' && $accion === 'logout') {
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
elseif ($modulo === 'usuarios' && $accion === 'listar') {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/usuarios/vistas/listar.php';
    require_once __DIR__ . '/../layouts/footer.php';
}
elseif ($modulo === 'usuarios' && $accion === 'editar') {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/usuarios/vistas/editar.php';
    require_once __DIR__ . '/../layouts/footer.php';
}
elseif ($modulo=== 'usuarios' && $accion === 'registro') {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/usuarios/vistas/registro.php';
    require_once __DIR__ . '/../layouts/footer.php';
}

elseif ($modulo === 'dashboard' && $accion === 'home'){
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/home.php';
    require_once __DIR__ . '/../layouts/footer.php';

}

//agregado para ventas
elseif ($modulo === 'ventas' && $accion === 'crear') {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/ventas/vistas/crear.php';
    require_once __DIR__ . '/../layouts/footer.php';
}

/* elseif ($modulo === 'inventario' && $accion === 'lista') {
    // Aquí irás agregando tus módulos reales del sistema más adelante
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../apps/inventario/vistas/lista.php';
    require_once __DIR__ . '/../layouts/footer.php';
} 
*/
else {
    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/404.php';
    require_once __DIR__ . '/../layouts/footer.php';
}