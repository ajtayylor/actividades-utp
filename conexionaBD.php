<?php
include 'conexion.php';

$conexion = new Conecta();
$cnn = $conexion->conectarBD(); // Obtiene la conexión devuelta

// Variables del formulario
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$rol = $_POST['rol'] ?? '';
$clubOrg = $_POST['clubOrg'] ?? ''; // Capturar el club

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos obligatorios están completos
    if (!empty($nombre) && !empty($apellido) && !empty($correo) && !empty($contrasena) && !empty($rol)) {

        // Validación básica de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "<br>Error: Correo electrónico no válido.";
        } else {
            try {
                // Tabla fija: usuarios
                $tabla = 'usuarios';

                // Consulta preparada para insertar los datos en la tabla usuarios
                $stmt = $cnn->prepare("INSERT INTO $tabla (nombre, apellido, correo, contrasena, rol, clubOrg) VALUES (?, ?, ?, ?, ?, ?)");

                if ($stmt) {
                    // Encriptar la contraseña antes de guardarla
                    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
                    
                    // Asociar parámetros
                    $stmt->bind_param("ssssss", $nombre, $apellido, $correo, $hashedPassword, $rol, $clubOrg);

                    // Ejecutar la consulta
                    if ($stmt->execute()) {
                        echo "<br>Usuario registrado exitosamente.";
                    } else {
                        // Analizar el error para mostrar un mensaje personalizado
                        if ($stmt->errno === 1062) { // Código de error para entradas duplicadas
                            echo "<br>Error: Correo ya registrado.";
                        } else {
                            echo "<br>Error al registrar el usuario: " . $stmt->error;
                        }
                    }
                } else {
                    echo "<br>Error al preparar la consulta: " . $cnn->error;
                }
            } catch (Exception $e) {
                echo "<br>Ocurrió un error: " . $e->getMessage();
            }
        }
    } else {
        echo "<br>Por favor, complete todos los campos obligatorios.";
    }
}

$conexion->cerrar();
?>
