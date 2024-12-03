<?php
include 'conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo'])) {
    echo "Debe iniciar sesión para inscribirse en una actividad.";
    exit;
}

// Crear conexión con la base de datos
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombreActividad'])) {
    $correo = $_SESSION['correo']; // Correo del usuario (obtenido de la sesión)
    $nombreActividad = filter_var($_POST['nombreActividad'], FILTER_SANITIZE_STRING);

    // Validar que la actividad exista
    $queryValidarActividad = "SELECT * FROM actividad WHERE nombreActividad = ?";
    $stmtValidar = $cnn->prepare($queryValidarActividad);
    $stmtValidar->bind_param("s", $nombreActividad);
    $stmtValidar->execute();
    $resultadoValidacion = $stmtValidar->get_result();

    if ($resultadoValidacion->num_rows > 0) {
        // Insertar inscripción
        $queryInsertar = "INSERT INTO inscripciones (correo, nombreActividad) VALUES (?, ?)";
        $stmtInsertar = $cnn->prepare($queryInsertar);
        $stmtInsertar->bind_param("ss", $correo, $nombreActividad);

        if ($stmtInsertar->execute()) {
            echo "Inscripción realizada con éxito.";
        } else {
            echo "Error al registrar la inscripción.";
        }
    } else {
        echo "La actividad seleccionada no existe.";
    }
} else {
    echo "Datos inválidos enviados.";
}
?>
