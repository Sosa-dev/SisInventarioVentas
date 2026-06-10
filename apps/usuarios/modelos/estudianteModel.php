<?php
namespace apps\usuarios\modelos;

// Traemos la conexión de la carpeta global
require_once __DIR__ . '/../../../config/conexion.php';

class EstudianteModel {
    private $db;

    public function __construct() {
        $this->db = \Conexion::conectar();
    }

    public function guardar($nombre, $apellido, $email) {
        $sql = "INSERT INTO estudiantes (nombre, apellido, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $email]);
    }

    public function login($correo, $contrasena) {
    // // 1. Usamos marcadores de posición (?) en lugar de concatenar las variables directamente
    // $sql = "SELECT * FROM usuarios WHERE correo = ? AND estado = b'1' LIMIT 1";
    
    // $stmt = $this->db->prepare($sql);
    
    // // 2. Ejecutamos pasando únicamente el correo de forma segura
    // $stmt->execute([$correo]);
    
    // // 3. Traemos el registro como un arreglo asociativo
    // $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

    // // 4. Verificamos si el usuario existe y si la contraseña coincide con el hash encriptado
    // if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
    //     return $usuario; // Login exitoso, retornamos los datos
    // }

    // return false; // Credenciales incorrectas

    // Buscamos un registro donde coincidan exactamente el correo y la contraseña en texto plano
    $sql = "SELECT id_usuario, nombre_completo, id_rol FROM usuarios WHERE correo = ? AND contrasena = ? AND estado = b'1' LIMIT 1";
    
    $stmt = $this->db->prepare($sql);
    
    // Pasamos ambos parámetros ordenados al execute
    $stmt->execute([$correo, $contrasena]);
    
    // Retorna el arreglo con los datos del usuario si se encontró coincidencia, o false si no existió
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}
}