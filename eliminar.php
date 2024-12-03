<?php
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener el nombre de la actividad desde la URL
$nombre_actividad = $_GET['nombre'];

// Eliminar la actividad de la base de datos
$query = "DELETE FROM actividad WHERE nombreActividad = ?";
$stmt = $cnn->prepare($query);
$stmt->bind_param("s", $nombre_actividad);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Actividad eliminada con éxito.";
} else {
    echo "No se pudo eliminar la actividad.";
}

$stmt->close();
$cnn->close();

// Redirigir a la página de actividades
header("Location: organizador.php");
exit();
?>
