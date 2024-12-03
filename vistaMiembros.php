<?php
include 'conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión y tiene rol de organizador
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'Organizador') {
    echo "Debe iniciar sesión como organizador para ver los miembros de su club.";
    exit;
}

// Crear conexión con la base de datos
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener el correo del organizador desde la sesión
$correoOrganizador = $_SESSION['correo'];

// Evitar inyección SQL al usar una sentencia preparada
$query = "
    SELECT m.correo, m.nombre AS clubNombre
    FROM miembros m
    JOIN usuarios u ON u.clubOrg = m.nombre  -- Relacionamos con el club del organizador
    WHERE u.correo = ?  -- Filtramos por el correo del organizador
";
$stmt = $cnn->prepare($query);
$stmt->bind_param("s", $correoOrganizador);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miembros de tu Club</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn {
            margin-top: 15px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Miembros de tu Club</h1>
    <table>
        <thead>
            <tr>
                <th>Correo del Miembro</th>
                <th>Nombre del Club</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultado->num_rows > 0) {
                while ($miembro = $resultado->fetch_assoc()) {
                    // Sanitizar los datos de salida para evitar XSS
                    $correoMiembro = htmlspecialchars($miembro['correo'], ENT_QUOTES, 'UTF-8');
                    $clubNombre = htmlspecialchars($miembro['clubNombre'], ENT_QUOTES, 'UTF-8');
                    echo "<tr>";
                    echo "<td>" . $correoMiembro . "</td>";
                    echo "<td>" . $clubNombre . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No hay miembros registrados en tu club.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón para regresar al inicio -->
    <form action="PrincipalOrg.php" method="POST">
        <button class="btn" type="submit">Volver al Inicio</button>
    </form>
</body>
</html>
