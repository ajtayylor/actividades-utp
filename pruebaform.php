<?php
session_start();
include 'conexion.php';
$conexion = new Conecta();
$cnn = $conexion->conectarBD();

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar el token CSRF
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF no válido.");
    }

    // Sanitizar y validar los datos
    $user_id = $_SESSION['user_id']; // El ID del usuario debe estar en la sesión
    $actividad = filter_var($_POST['actividad'], FILTER_SANITIZE_STRING);
    $fecha_participacion = date("Y-m-d H:i:s");
    $ip_usuario = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $referer = $_SERVER['HTTP_REFERER'];

    // Validar si el usuario ya está registrado
    $stmt = $cnn->prepare("SELECT * FROM participantes WHERE user_id = ? AND actividad = ?");
    $stmt->bind_param("is", $user_id, $actividad);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "Ya estás registrado para esta actividad.";
    } else {
        // Insertar en la base de datos
        $query = "INSERT INTO participantes (user_id, actividad, fecha_participacion, ip_usuario, user_agent, referer)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $cnn->prepare($query);
        $stmt->bind_param("isssss", $user_id, $actividad, $fecha_participacion, $ip_usuario, $user_agent, $referer);
        if ($stmt->execute()) {
            echo "Te has registrado exitosamente para la actividad.";
        } else {
            echo "Error al registrar la participación.";
        }
    }
}

// Generar un token CSRF para el formulario
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<form method="POST" action="">
    <input type="hidden" name="actividad" value="<?php echo htmlspecialchars($actividad); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    
    <p>¿Quieres participar en la actividad <?php echo htmlspecialchars($actividad); ?>?</p>
    <input type="checkbox" name="accept_terms" required> Acepto los términos y condiciones
    <br>
    <button type="submit" class="btn">Participar</button>
</form>
