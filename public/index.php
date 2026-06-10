<?php
session_start();
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
}
// Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Cargar vistas según el módulo y acción
if ($modulo === 'usuarios' && $accion === 'login') {
    require_once __DIR__ . '/../apps/usuarios/vistas/login.php';
} 
elseif ($modulo === 'usuarios' && $accion === 'registro') {
    require_once __DIR__ . '/../layouts/registro.php';

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