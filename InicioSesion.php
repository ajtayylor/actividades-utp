<?php
session_start(); // Iniciar la sesión
include 'conexion.php'; // Incluir archivo de conexión

$conexion = new Conecta();
$cnn = $conexion->conectarBD(); // Conexión a la base de datos

// Validar CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';

    // Verificar el token CSRF
    if ($csrf_token !== $_SESSION['csrf_token']) {
        die("Error: Token CSRF inválido.");
    }

    // Obtener los datos del formulario
    $correo = $_POST['correo'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';

    // Validar los campos del formulario
    if (!empty($correo) && !empty($contrasena)) {
        // Buscar el registro del usuario por correo
        $stmt = $cnn->prepare("SELECT correo, contrasena, rol FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            // Verificar la contraseña ingresada contra el hash almacenado
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Contraseña correcta: Inicio de sesión exitoso
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['rol'] = $usuario['rol']; // Almacenar el rol en la sesión
                
                // Redirigir según el rol
                if ($usuario['rol'] === 'Organizador') {
                    header("Location: PrincipalOrg.php"); // Redirige a la página del Organizador
                } else {
                    header("Location: Principal.php"); // Redirige a la página para otros roles
                }
                exit();
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta. Por favor, intente de nuevo.";
            }
        } else {
            // Usuario no encontrado
            echo "No se encontró un usuario con ese correo.";
        }
    } else {
        echo "Por favor complete todos los campos.";
    }
}

$conexion->cerrar(); // Cerrar la conexión a la base de datos
?>
