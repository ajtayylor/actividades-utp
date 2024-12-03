<?php
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener el nombre de la actividad desde la URL
$nombre_actividad = $_GET['nombre'];

// Consultar la actividad por nombre
$query = "SELECT * FROM actividad WHERE nombreActividad = ?";
$stmt = $cnn->prepare($query);
$stmt->bind_param("s", $nombre_actividad);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $actividad = $result->fetch_assoc();
} else {
    echo "Actividad no encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Actividad</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Encabezado */
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
        }

        /* Estilo del formulario */
        form {
            width: 50%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Editar Actividad</h1>
    <form action="guardar_edicion.php" method="post">
        <input type="hidden" name="id" value="<?php echo $actividad['id']; ?>"> <!-- Asegúrate de tener un campo id -->
        
        <label for="nombreActividad">Nombre de la actividad:</label>
        <input type="text" name="nombreActividad" id="nombreActividad" value="<?php echo $actividad['nombreActividad']; ?>" required><br><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?php echo $actividad['descripcion']; ?></textarea><br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" value="<?php echo $actividad['fecha']; ?>" required><br><br>

        <label for="hora">Hora:</label>
        <input type="time" name="hora" id="hora" value="<?php echo $actividad['hora']; ?>" required><br><br>

        <label for="lugar">Lugar:</label>
        <input type="text" name="lugar" id="lugar" value="<?php echo $actividad['lugar']; ?>" required><br><br>

        <button type="submit">Guardar cambios</button>
    </form>
    <a href="Actividades.php">Cancelar</a>
</body>
</html>
