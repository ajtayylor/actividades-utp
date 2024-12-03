<?php
session_start(); // Continuar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    header("Location: Ingreso.php"); // Redirigir al login si no está autenticado
    exit();
}

include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener los datos del formulario
$correoUsuario = $_POST['correo']; // Correo del usuario
$nombreClub = $_POST['nombre']; // Nombre del club

// Sentencia preparada para evitar inyección SQL
$stmt = $cnn->prepare("INSERT INTO miembros (correo, nombre) VALUES (?, ?)");
$stmt->bind_param('ss', $correoUsuario, $nombreClub); // 'ss' indica que ambas variables son cadenas

// Ejecutar la consulta de inserción
if ($stmt->execute()) {
    echo "Te has inscrito exitosamente en el club de " . htmlspecialchars($nombreClub) . ".";
    // Redirigir al listado de clubes después de un pequeño retraso
    header("refresh:3;url=deportes.php"); // Redirigir después de 3 segundos
    exit();
} else {
    echo "Error al inscribirse: " . $cnn->error; // Mostrar error si la consulta falla
}

