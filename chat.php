<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo esté en el mismo directorio

// Verificar si el usuario ha iniciado sesión y si se seleccionó un amigo
if (!isset($_SESSION['user_id']) || !isset($_POST['amigo_id'])) {
    header("Location: inicio.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$amigo_id = intval($_POST['amigo_id']);

// Obtener mensajes entre el usuario y el amigo seleccionado
$mensajes = [];
$query_mensajes = "
    SELECT m.*, u.nombre_user AS autor 
    FROM mensajes m 
    JOIN user u ON m.id_emisor = u.id
    WHERE (m.id_emisor = $user_id AND m.id_receptor = $amigo_id) 
       OR (m.id_emisor = $amigo_id AND m.id_receptor = $user_id) 
    ORDER BY m.id ASC";
$result_mensajes = mysqli_query($conn, $query_mensajes);

if ($result_mensajes) {
    $mensajes = mysqli_fetch_all($result_mensajes, MYSQLI_ASSOC);
}

// Manejar el envío de mensajes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar_mensaje'])) {
    $mensaje = htmlspecialchars($_POST['mensaje']);

    // Insertar el mensaje en la base de datos
    if (!empty($mensaje)) {
        $query = "INSERT INTO mensajes (id_emisor, id_receptor, texto) VALUES ($user_id, $amigo_id, '$mensaje')";
        mysqli_query($conn, $query);
        header("Location: chat.php");  
        exit(); // Asegúrate de salir después de la redirección
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con Amigo</title>
    <link rel="stylesheet" href="./css/chat.css">
</head>
<body>
    <div class="chat-page">
        <header>
            <h2>Chat con amigo</h2>
        </header>

        <div class="chat-section">
            <h3>Mensajes</h3>
            <div class="chat-messages">
                <?php foreach ($mensajes as $mensaje): ?>
                    <div class="message <?php echo $mensaje['id_emisor'] == $user_id ? 'sent' : 'received'; ?>">
                        <p><?php echo htmlspecialchars($mensaje['texto']); ?></p>
                        <span class="author">- <?php echo htmlspecialchars($mensaje['autor']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-enviar">
            <form action="chat.php" method="POST" class="message-form">
                <input type="hidden" name="amigo_id" value="<?php echo $amigo_id; ?>">
                <textarea name="mensaje" required placeholder="Escribe tu mensaje..."></textarea>
                <button type="submit" name="enviar_mensaje">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>
