<?php
session_start(); // Iniciar la sesión

// Verificar si la sesión está activa y si se ha iniciado correctamente
if (!isset($_SESSION['correo'])) {
    // Redirigir a la página de login si no está autenticado
    header("Location: login.php");
    exit();
}

// Asegurarse de que la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Acceso denegado o datos inválidos.";
    exit();
}

// Verificar si los campos necesarios están presentes
if (isset($_POST['nombreActividad'], $_POST['descripcion'], $_POST['fecha'], $_POST['club'])) {
    
    // Sanitizar y validar los datos
    $nombreActividad = filter_var(trim($_POST['nombreActividad']), FILTER_SANITIZE_STRING);
    $descripcion = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);
    $fecha = $_POST['fecha']; // Suponiendo que el formato de la fecha es válido
    $club = filter_var(trim($_POST['club']), FILTER_SANITIZE_STRING);
    $correo = $_SESSION['correo']; // Correo del usuario autenticado

    // Validar datos (por ejemplo, comprobar si la fecha es válida)
    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $nombreActividad)) {
        echo "El nombre de la actividad no es válido.";
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico no válido.";
        exit();
    }

    // Conexión a la base de datos
    include 'conexion.php';
    $conexion = new Conecta();
    $cnn = $conexion->conectarBD();

    // Preparar la consulta SQL para evitar inyecciones SQL
    $queryInsert = "INSERT INTO actividad (nombreActividad, descripcion, fecha, club, correo) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $cnn->prepare($queryInsert);
    $stmtInsert->bind_param("sssss", $nombreActividad, $descripcion, $fecha, $club, $correo);

    // Ejecutar la consulta y manejar el éxito o error
    if ($stmtInsert->execute()) {
        // Redirigir a la página organizador.php después de la inserción exitosa
        header("Location: organizador.php");
        exit(); // Asegurarse de que no se ejecute más código después de la redirección
    } else {
        // Log del error (en producción, no mostrar detalles del error al usuario)
        error_log("Error al agregar actividad: " . $stmtInsert->error);
        echo "Hubo un problema al agregar la actividad. Intente nuevamente.";
    }

    // Cerrar la conexión
    $conexion->cerrar();

} else {
    echo "Por favor, complete todos los campos requeridos.";
}