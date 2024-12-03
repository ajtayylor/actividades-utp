<?php
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener los datos del formulario
$id = $_POST['id'];
$nombreActividad = $_POST['nombreActividad'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$lugar = $_POST['lugar'];

// Actualizar la actividad en la base de datos
$query = "UPDATE actividad SET nombreActividad = ?, descripcion = ?, fecha = ?, hora = ?, lugar = ? WHERE id = ?";
$stmt = $cnn->prepare($query);
$stmt->bind_param("sssssi", $nombreActividad, $descripcion, $fecha, $hora, $lugar, $id);

if ($stmt->execute()) {
    echo "Actividad actualizada con éxito.";
    header("Location: Actividades.php"); // Redirigir a la página de actividades
    exit();
} else {
    echo "Error al actualizar la actividad.";
}

$stmt->close();
$cnn->close();
?>