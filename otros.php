<?php
session_start(); // Continuar la sesión iniciada

// Regenerar el ID de la sesión para evitar el secuestro de sesión
session_regenerate_id(true);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['correo'])) {
    header("Location: Ingreso.php"); // Redirigir al login si no está autenticado
    exit();
}

// Obtener el rol del usuario
$rol = $_SESSION['rol'] ?? 'Usuario';

include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Sentencia preparada para evitar inyección SQL
$stmt = $cnn->prepare("SELECT id, nombre, categoria FROM clubes WHERE categoria = ?");
$categoria = 'Otros'; // Valor seguro
$stmt->bind_param('s', $categoria); // 's' indica que la variable es una cadena
$stmt->execute();
$resultadoClubes = $stmt->get_result();

// Verificar si la consulta de clubes se ejecutó correctamente
if (!$resultadoClubes) {
    die("Error en la consulta de clubes: " . $cnn->error); // Mostrar error si la consulta falla
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deportes</title>
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
            text-align: center;
            padding: 40px 20px;
            background-color: white;
        }

        .hero h2 {
            font-size: 2rem;
            color: #333;
        }

        /* Contenedor de cards */
        .sport-section {
            display: flex;
            flex-wrap: wrap; /* Permite que las cards se ajusten a la línea */
            gap: 20px; /* Espacio entre las cards */
            justify-content: center; /* Centrar las cards */
            padding: 20px;
            background-color: #e8f5e9;
        }

        /* Diseño de las cards */
        .sport-card {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            width: 300px; /* Tamaño fijo para las cards */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px; /* Margen adicional para separación */
        }

        .sport-image {
            width: 100px;
            height: 100px;
            object-fit: cover; /* Ajusta la imagen al contenedor sin distorsionar */
            margin-right: 20px;
            border-radius: 10px;
        }

        .sport-info {
            flex: 1;
        }

        .button {
            padding: 5px 10px;
            background-color: #4CAF50; /* Color verde */
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin: 10px;
        }

        .button:hover {
            background-color: #2e7d32;
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
    </header>
    <section class="hero"><h2>Clubes Deportivos</h2></section>
    <hr>
    <section class="sport-section">
        <?php
        // Mostrar los resultados de los clubes
        if ($resultadoClubes->num_rows > 0) {
            while ($club = $resultadoClubes->fetch_assoc()) {
                // Generar cada card dinámicamente
                echo '<div class="sport-card">';
                echo '<img src="images/' . strtolower($club['nombre']) . '.jpg" alt="' . $club['nombre'] . '" class="sport-image">'; // Imagen dinámica
                echo '<div class="sport-info">';
                echo '<h2>Club de ' . $club['nombre'] . '</h2>';
                echo '<button class="button">$ Donar</button>';
                echo '<button class="button">Inscribir</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No se encontraron clubes en la categoría 'Otros'.</p>";
        }
        ?>
    </section>
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
