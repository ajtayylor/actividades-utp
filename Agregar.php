<?php
session_start(); // Iniciar la sesión para acceder a los datos de sesión

// Verificar si la sesión está activa
if (!isset($_SESSION['correo'])) {
    // Si no está iniciada la sesión, redirigir al usuario a la página de login
    header("Location: login.php"); // Cambia "login.php" por el nombre de tu página de inicio de sesión
    exit(); // Termina la ejecución del código para evitar que se muestre el formulario
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Actividad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9; /* Verde claro */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 20px;
        }

        h2, h3 {
            color: #2e7d32; /* Verde oscuro */
            text-align: center;
        }

        form {
            background-color: #ffffff; /* Fondo blanco */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #2e7d32; /* Azul */
            font-weight: bold;
        }

        input[type="text"], textarea, input[type="date"] {
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

        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff; /* Fondo blanco */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #1565c0; /* Azul */
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e0f7fa; /* Azul claro */
        }
    </style>
</head>
<body>
    <h2>Agregar Nueva Actividad</h2>
    <form action="agregar_actividad.php" method="POST">
        <label for="nombreActividad">Nombre de la Actividad:</label><br>
        <input type="text" id="nombreActividad" name="nombreActividad" required><br><br>

        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

        <label for="fecha">Fecha:</label><br>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="club">Club:</label><br>
        <input type="text" id="club" name="club" required><br><br>

        <button type="submit">Agregar Actividad</button>
    </form>
</body>
</html>
