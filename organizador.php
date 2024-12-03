<?php
session_start(); // Continuar con la sesión iniciada

// Regenerar el ID de la sesión en cada solicitud para prevenir la fijación de sesión
session_regenerate_id(true);

// Limitar el tiempo de inactividad de la sesión
$inactivity_limit = 1800; // 30 minutos de inactividad
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactivity_limit) {
    session_unset();
    session_destroy();
    header("Location: Ingreso.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    // Si no hay sesión activa, redirigir al formulario de inicio de sesión
    header("Location: Ingreso.php");
    exit();
}

// Verificar si el usuario tiene el rol de "Organizador"
if ($_SESSION['rol'] !== 'Organizador') {
    echo "Acceso denegado.";
    exit();
}

// Conectar a la base de datos
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener el correo del usuario autenticado
$correo = $_SESSION['correo'];

// Obtener el rol del usuario
$rol = $_SESSION['rol'] ?? 'Usuario';

// Consultar las actividades del usuario autenticado usando consultas preparadas
$query = "SELECT * FROM actividad WHERE correo = ?";
$stmt = $cnn->prepare($query);

if ($stmt === false) {
    // Manejo de error en la preparación de la consulta
    die('Error en la preparación de la consulta: ' . $cnn->error);
}

$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si hay resultados
if ($result->num_rows > 0) {
    // Se encontraron actividades
    $actividades = [];
    while ($row = $result->fetch_assoc()) {
        $actividades[] = $row;
    }
} else {
    $actividades = []; // No hay actividades
}

$stmt->close();
$cnn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizador - Universidad Tecnológica de Panamá</title>
    <style>
        /* El estilo anterior permanece intacto */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
        }

        header {
            background-color: #e8f5e9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 1.5rem;
            color: #2e7d32;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        nav ul li a:hover {
            color: #2e7d32;
        }

        .logout-btn {
            background-color: #2e7d32;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #1b5e20;
        }

        main {
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            color: #004400;
        }

        /* Nueva sección para las cards */
        .activities {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .activity-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .activity-card h2 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .activity-card ul {
            list-style: none;
            padding: 0;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .activity-card ul li {
            margin-bottom: 5px;
        }

        .activity-card .buttons {
            display: flex;
            justify-content: space-between;
        }

        .activity-card .buttons button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .activity-card .buttons button:hover {
            background-color: #1b5e20;
        }

        footer {
            background-color: #2e7d32;
            color: white;
            text-align: center;
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        footer .info {
            margin: 0;
        }

        footer ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        footer ul li {
            font-size: 0.8rem;
        }
         /* Menú desplegable */
nav ul li.dropdown {
    position: relative;
}

nav ul li.dropdown .dropdown-content {
    display: none;
    position: absolute;
    background-color: #e8f5e9;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    min-width: 160px;
    z-index: 1;
    border-radius: 5px;
}

nav ul li.dropdown .dropdown-content a {
    padding: 12px 16px;
    color: #333;
    display: block;
    text-decoration: none;
    font-weight: normal;
}

nav ul li.dropdown .dropdown-content a:hover {
    background-color: #2e7d32;
    color: white;
}

nav ul li.dropdown:hover .dropdown-content {
    display: block;
}

nav ul li.dropdown a:hover {
    color: #2e7d32;
}

    </style>
</head>
<body>

    <!-- Encabezado -->
    <header>
        <h1>Universidad Tecnológica de Panamá</h1>
        <nav>
            <ul>
                <li>
                    <a href="<?php echo $rol === 'Organizador' ? 'PrincipalOrg.php' : 'Principal.php'; ?>">Inicio</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Clubes</a>
                    <div class="dropdown-content">
                        <a href="deportes.php">Deportes</a>
                        <a href="otros.php">Otros</a>
                    </div>
                </li>
               
                <li><a href="Actividades.php">Actividades</a></li>
                <li><a href="#">Participar</a></li>
                <li><a href="#">Inscribirme</a></li>

            </ul>
        </nav>
        <a href="salir.php"><button class="logout-btn">Salir</button></a>
    </header>

    <main>
        <h1>Organizador</h1>
        <a href="Agregar.php"><button class="logout-btn">Añadir una nueva actividad</button></a>
        
        <div class="activities">
            <?php if (count($actividades) > 0): ?>
                <?php foreach ($actividades as $actividad): ?>
                    <div class="activity-card">
                        <h2><?php echo htmlspecialchars($actividad['nombreActividad']); ?></h2>
                        <ul>
                            <li>Descripción: <?php echo htmlspecialchars($actividad['descripcion']); ?></li>
                            <li>Fecha: <?php echo htmlspecialchars($actividad['fecha']); ?></li>
                            <li>Club: <?php echo htmlspecialchars($actividad['club']); ?></li>
                        </ul>
                        <div class="buttons">
                            <a href="editar.php?nombre=<?php echo urlencode($actividad['nombreActividad']); ?>"><button>Editar</button></a>
                            <a href="eliminar.php?nombre=<?php echo urlencode($actividad['nombreActividad']); ?>"><button>Eliminar</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay actividades programadas para este usuario.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p class="info">Universidad Tecnológica de Panamá - 2024</p>
        <p>Dirección: Avenida Universidad Tecnológica de Panamá, Vía Puente Centenario, Teléfono: +507 560-3000</p>
        <ul>
            <li>Matrícula UTP</li>
            <li>Biblioteca</li>
            <li>Bolsas de Trabajo</li>
            <li>Correo de Lenguas</li>
        </ul>
    </footer>
</body>
</html>
