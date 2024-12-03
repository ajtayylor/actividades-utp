<?php

// Configuración para ocultar errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
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

        .hero button {
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .hero button:hover {
            background-color: #1b5e20;
        }

        .content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            text-align: center;
        }

        .content h2 {
            color: #2e7d32;
            font-size: 2rem;
        }

        .campus-container {
            width: 100%;
            height: 500px;
            overflow: hidden;
        }

        .campus-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Categorías */
        .categories {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            padding: 20px;
        }

        .category {
            position: relative;
            width: 300px;
            height: 200px;
            overflow: hidden;
            border-radius: 10px;
        }

        .category img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category .logout-btn {
            position: absolute;
            bottom: 10px;
            right: 40%;
            transform: translateX(-50%);
            background-color: #2e7d32;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            opacity: 0.9;
        }

        .category .logout-btn:hover {
            background-color: #1b5e20;
            opacity: 1;
        }

        /* Footer */
        footer {
            background-color: #2e7d32;
            color: white;
            text-align: center;
            padding: 10px 20px;
            font-size: 0.9rem;
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
    <header>
        <h1>Universidad Tecnológica de Panamá</h1>
        <nav>
            <ul>
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
        <a href="Ingreso.php"><button class="logout-btn">Ingresar</button></a>
    </header>

    <section class="hero">
        <h2>Bienvenido a los Clubes Universitarios UTP</h2>
        <a href="Actividades.php"><button>Buscar</button></a>
    </section>

    <section>
        <div class="campus-container">
            <img src="fachada_utp_2.jpg" alt="Imagen del campus UTP" class="campus-img">
        </div>
    </section>

    <div class="content">
        <h2>Conoce nuestros clubes y actividades</h2>
        <p>En los Clubes Universitarios de la UTP, podrás encontrar una gran variedad de actividades culturales, deportivas y académicas. Únete a nosotros para formar parte de una comunidad estudiantil activa y enriquecedora.</p>
    </div>

    <section class="categories">
        <div class="category">
            <img src="deportes.jpg" alt="Deportes">
            <a href="deportes.php"><button class="logout-btn">Deportes</button></a>
        </div>
        <div class="category">
            <img src="debate.jpg" alt="Otros">
            <a href="otros.php"><button class="logout-btn">Otros</button></a>
        </div>
    </section>

    <footer>
        <p>Universidad Tecnológica de Panamá - 2024</p>
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
