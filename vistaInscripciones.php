<?php
include 'conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión y si tiene rol de administrador
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'Organizador') {
    echo "Debe iniciar sesión como administrador para ver las inscripciones.";
    exit;
}

// Crear conexión con la base de datos
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener las inscripciones
$queryInscripciones = "SELECT id, correo, nombreActividad, fecha FROM inscripciones ORDER BY fecha DESC";
$resultadoInscripciones = $cnn->query($queryInscripciones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones Realizadas</title>
    <style>
        /* Estilos básicos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo para el botón de salir */
        .btn {
            background-color: #45a049;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #2e7d32;
        }
    </style>
</head>
<body>
    <h1>Inscripciones Realizadas</h1>

    <!-- Tabla con las inscripciones -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Nombre de la Actividad</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultadoInscripciones->num_rows > 0) {
                // Mostrar cada inscripción en una fila de la tabla
                while ($inscripcion = $resultadoInscripciones->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($inscripcion['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($inscripcion['correo']) . "</td>";
                    echo "<td>" . htmlspecialchars($inscripcion['nombreActividad']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay inscripciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div>
    <!-- Botón para salir de la sesión -->
    <form action="PrincipalOrg.php" method="POST">
        <button class="btn" type="submit">Inicio</button>
    </form>

</body>
</html>
