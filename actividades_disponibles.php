<?php
include 'conexion.php';

// Crear conexión con la base de datos
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Obtener lista de clubes disponibles
$queryClubes = "SELECT DISTINCT club FROM actividad";
$resultadoClubes = $cnn->query($queryClubes);
// Obtener el rol del usuario
$rol = $_SESSION['rol'] ?? 'Usuario';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['club'])) {
    // Filtrar la entrada para evitar inyecciones maliciosas
    $club = filter_var($_GET['club'], FILTER_SANITIZE_STRING);
    $club = preg_replace("/[^a-zA-Z0-9 ]/", "", $club); // Asegura que solo haya letras y números

    // Obtener actividades del club seleccionado
    $queryActividades = "SELECT nombreActividad, descripcion, fecha FROM actividad WHERE club = ? ORDER BY fecha DESC";
    $stmt = $cnn->prepare($queryActividades);
    if ($stmt) {
        $stmt->bind_param("s", $club);
        $stmt->execute();
        $actividades = $stmt->get_result();

        $htmlActividades = '';

        // Mostrar actividades si existen
        if ($actividades->num_rows > 0) {
            $htmlActividades .= '<div class="card-container">'; // Contenedor para las tarjetas

            // Crear una tarjeta para cada actividad
            while ($actividad = $actividades->fetch_assoc()) {
                $htmlActividades .= '<div class="card">';
                $htmlActividades .= '<img src="https://via.placeholder.com/300x180.png?text=' . urlencode($actividad['nombreActividad']) . '" alt="Imagen de ' . htmlspecialchars($actividad['nombreActividad']) . '">';
                $htmlActividades .= '<div class="card-content">';
                $htmlActividades .= '<h1>' . htmlspecialchars($actividad['nombreActividad']) . '</h1>';
                $htmlActividades .= '<p>' . htmlspecialchars($actividad['descripcion']) . '</p>';
                $htmlActividades .= '<p><strong>Fecha:</strong> ' . htmlspecialchars($actividad['fecha']) . '</p>';
                $htmlActividades .= '</div>';
                $htmlActividades .= '<div class="card-footer">';
                
                // Formulario para participar en la actividad
                $htmlActividades .= '<form method="POST" action="inscripcion.php">';
                $htmlActividades .= '<input type="hidden" name="nombreActividad" value="' . htmlspecialchars($actividad['nombreActividad']) . '">';
                $htmlActividades .= '<button type="submit">Participar</button>';
                $htmlActividades .= '</form>';
                
                $htmlActividades .= '</div>';
                $htmlActividades .= '</div>';
            }

            $htmlActividades .= '</div>'; // Cerrar contenedor de tarjetas
        } else {
            $htmlActividades .= "<p>No se encontraron actividades para el club: $club</p>";
        }
    } else {
        $htmlActividades .= "<p>Error al preparar la consulta.</p>";
    }
} else {
    $htmlActividades = "<p>Seleccione un club para ver las actividades disponibles.</p>";
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades UTP</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: #333;
        }

        /* Encabezado */
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


        .btn {
            background-color: #45a049;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #2e7d32;
        }

        /* Hero section */
        .hero {
            text-align: left;
            padding: 40px 20px;
            background-color: white;
        }

        .hero h2 {
            font-size: 2rem;
            color: #333;
        }

        /* Card container */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        /* Card */
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            text-align: left;
            width: 50%;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
            background-color: e8f5e9;
        }

        .card-content h1 {
            font-size: 1.2rem;
            color: #333;
            margin: 0 0 10px;
        }

        .card-content p {
            font-size: 0.9rem;
            color: #555;
            margin: 5px 0;
        }

        .card-footer {
            padding: 15px;
            text-align: right;
            background-color: #f5f5f5;
            border-top: 1px solid #ddd;
        }

        .card-footer a {
            text-decoration: none;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .card-footer a:hover {
            background-color: #388e3c;
        }

        /* Footer */
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
    </header>

    <!-- Hero section -->
    <section class="hero">
        <?php
        // Mostrar el nombre del club seleccionado
        if (!empty($club)) {
            echo '<h2>Actividades para el club de ' . htmlspecialchars($club) . '</h2>';
        }
        ?>
        <hr>
    </section>

    <!-- Contenido de actividades -->
    <main class="content">
        <?php echo $htmlActividades; ?>
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