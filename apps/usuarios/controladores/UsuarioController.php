<?php
namespace apps\usuarios\controladores;

use apps\usuarios\modelos\estudianteModel;

class UsuarioController {
    public function procesarLogin() {
    // 1. Validación estricta del Token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        $_SESSION['error_mensaje'] = "Error de seguridad: Token CSRF inválido.";
        header("Location: index.php?modulo=usuarios&accion=login");
        exit();
    }

    // 2. Captura y limpieza de espacios en blanco
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // 3. Validación de campos vacíos
    if (empty($correo) || empty($contrasena)) {
        $_SESSION['error_mensaje'] = "Por favor, complete todos los campos.";
        header("Location: index.php?modulo=usuarios&accion=login");
        exit();
    }

    // 4. Validación del formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_mensaje'] = "El formato del correo electrónico no es válido.";
        header("Location: index.php?modulo=usuarios&accion=login");
        exit();
    }

    // 5. Instancia del Modelo (Con la 'E' mayúscula corregida)
    $estudianteModel = new EstudianteModel();
    
    // Ejecutamos el método seguro que desinfecta el SQL y verifica el password encriptado
    $usuario = $estudianteModel->login($correo, $contrasena);

    // 6. Manejo de la sesión según el resultado del modelo
    if ($usuario) {
        // ¡Login exitoso! Guardamos los datos clave en la sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
        $_SESSION['usuario_rol'] = $usuario['id_rol']; // Útil si necesitas validar permisos más adelante

        // Regeneramos el token CSRF por seguridad al cambiar el estado de la sesión
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        
        // Redirección al panel principal
        header("Location: index.php?modulo=usuarios&accion=registro");
        exit();
    } else {
        // El modelo devolvió false (usuario no existe, está inactivo o contraseña incorrecta)
        $_SESSION['error_mensaje'] = "Correo o contraseña incorrectos.";
        header("Location: index.php?modulo=usuarios&accion=login");
        exit();
    }
}

}