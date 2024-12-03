<?php
session_start(); // Iniciar la sesión

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generar un token CSRF
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
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

        h2 {
            color: #2e7d32; /* Verde oscuro */
            text-align: center;
        }

        form {
            background-color: #ffffff; /* Fondo blanco */
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #2e7d32; /* Verde oscuro */
            font-weight: bold;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #bdbdbd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            background-color: #2e7d32; /* Verde oscuro */
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #1b5e20; /* Verde más oscuro */
        }

        form:hover {
            transform: translateY(-3px);
            transition: 0.2s ease-in-out;
        }
    </style>
</head>
<body>
    <div>
        <h2>Iniciar sesión</h2>
        <!-- Formulario de inicio de sesión -->
        <form method="POST" action="InicioSesion.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
