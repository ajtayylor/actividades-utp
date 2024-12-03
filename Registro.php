<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Clubes Universitarios UTP</title>
    <style>
        /* Estilo general */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Encabezado de navegación */
        nav {
            background-color: #49d849; /* Verde manzana */
            padding: 1rem;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 1rem;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Formulario */
        .container {
            max-width: 600px;
            margin: 2rem auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .container h2 {
            text-align: center;
            color: #7fbf7f;
            margin-bottom: 1rem;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-top: 1rem;
        }

        form input, form select, form button {
            width: 100%;
            padding: 0.8rem;
            margin-top: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button {
            background-color: #29e929;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #29e929;
        }

        /* Footer */
        footer {
            background-color: #29e929;
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 2rem;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Navegador -->
    <nav>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Actividades</a></li>
            <li><a href="#">Ingreso</a></li>
        </ul>
    </nav>

    <!-- Contenedor principal -->
    <div class="container">
        <h2>Registro en plataforma de Clubes UTP</h2>
        <form method="POST" action="ConexionaBD.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <label for="carrera">Rol</label>
            <input type="text" id="rol" name="rol" required>

            <label for="carrera">Club del Organizador</label>
            <input type="text" id="clubOrg" name="clubOrg">



            <button type="submit">Registrar</button>
        </form>
    </div>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2024 Clubes UTP. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
