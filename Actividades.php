<?php
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener lista de clubes disponibles
$queryClubes = "SELECT DISTINCT club FROM actividad";
$resultadoClubes = $cnn->query($queryClubes);

// Verificar si la consulta de clubes se ejecutó correctamente
if (!$resultadoClubes) {
    error_log("Error en la consulta de clubes: " . $cnn->error); // Loguear el error en el servidor
    die("Hubo un problema al cargar los clubes."); // Mensaje genérico
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['club'])) {
    // Sanitizar la entrada de 'club'
    $club = filter_input(INPUT_GET, 'club', FILTER_SANITIZE_STRING);

    // Obtener actividades del club seleccionado
    $queryActividades = "SELECT id, nombreActividad, descripcion, fecha FROM actividad WHERE club = ? ORDER BY fecha DESC";
    $stmt = $cnn->prepare($queryActividades);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $cnn->error); // Mostrar error si falla la preparación
    }

    $stmt->bind_param("s", $club); // Vincula el parámetro
    $stmt->execute();
    $actividades = $stmt->get_result();
}



// Generar token CSRF (si planeas usar formularios que modifiquen datos)
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Crear un nuevo token CSRF
}

$rol = $_SESSION['rol'] ?? 'Usuario';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades Disponibles</title>
    <style>
        /* Estilo general de la página */
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9; /* Verde claro */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Estilo del contenedor principal */
        .container {
            width: 80%;
            max-width: 900px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #2e7d32; /* Verde oscuro */
            text-align: center;
        }

        h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Estilo del formulario */
        form {
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 200px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        button {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #1b5e20; /* Verde más oscuro */
        }

        /* Estilo de la tabla */
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        td {
            background-color: #f9f9f9;
            color: #2c3e50; /* Cambié el color del texto de las celdas para mejorar la visibilidad */
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1; /* Color suave para las filas pares */
        }

        tr:hover {
            background-color: #e0e0e0; /* Fondo suave cuando se pasa el ratón por encima */
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Actividades por Club</h2>

        <!-- Formulario para seleccionar un club -->
        <form method="GET" action="actividades_disponibles.php">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Token CSRF -->
            <label for="club">Selecciona un Club:</label>
            <select name="club" id="club" required>
                <option value="">-- Seleccionar --</option>
                <?php
                // Mostrar los clubes disponibles en el formulario
                while ($club = $resultadoClubes->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($club['club'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($club['club'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
            <button type="submit">Ver Actividades</button>
        </form>

        <?php if (isset($actividades) && $actividades->num_rows > 0): ?>
            <!-- Si hay actividades para el club seleccionado, mostrarlas -->
            <h3>Actividades Disponibles</h3>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
                <?php while ($actividad = $actividades->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($actividad['nombreActividad'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($actividad['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($actividad['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php elseif (isset($_GET['club'])): ?>
            <!-- Si no hay actividades para el club seleccionado -->
            <p>No hay actividades disponibles para este club.</p>
        <?php endif; ?>

        <a href="<?php echo $rol === 'Organizador' ? 'PrincipalOrg.php' : 'Principal.php'; ?>">
    <button>Regresar al Inicio</button>
</a>
    </div>

</body>
</html>

<?php
// Cerrar la conexión
$conexion->cerrar();
?>