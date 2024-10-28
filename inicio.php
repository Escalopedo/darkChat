<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo esté en el mismo directorio

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$nombre_user = $_SESSION['nombre_user'];

// Manejar la solicitud de amistad
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar_amigo'])) {
    $id_receptor = intval($_POST['id_receptor']);
    $id_emisor = $_SESSION['user_id'];

    // Verifica si la solicitud ya existe
    $query_check = "SELECT * FROM solicitudes WHERE id_emisor = $id_emisor AND id_receptor = $id_receptor";
    $result_check = mysqli_query($conn, $query_check);
    
    if (mysqli_num_rows($result_check) == 0) {
        $query_insert = "INSERT INTO solicitudes (id_emisor, id_receptor, estado) VALUES ($id_emisor, $id_receptor, 'PENDIENTE')";
        mysqli_query($conn, $query_insert);
    }
}

// Obtener la lista de amigos sin duplicados
$query_amigos = "
    SELECT DISTINCT u.* 
    FROM amigos a 
    JOIN user u ON (u.id = a.id_user1 OR u.id = a.id_user2) 
    WHERE (a.id_user1 = {$_SESSION['user_id']} OR a.id_user2 = {$_SESSION['user_id']}) 
    AND u.id != {$_SESSION['user_id']}
";
$result_amigos = mysqli_query($conn, $query_amigos);
$amigos = mysqli_fetch_all($result_amigos, MYSQLI_ASSOC);

// Obtener todos los usuarios para la búsqueda
$query_usuarios = "SELECT * FROM user WHERE id != {$_SESSION['user_id']}";
$result_usuarios = mysqli_query($conn, $query_usuarios);
$usuarios = mysqli_fetch_all($result_usuarios, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Inicio</title>
    <link rel="stylesheet" href="./css/chat.css">
</head>
<body>
    <div class="chat-container">
        <!-- Encabezado del chat -->
        <header>
            <h2>Hola, <?php echo htmlspecialchars($nombre_user); ?></h2>
        </header>

        <div class="columns-container">
            <!-- Sección de búsqueda de amigos (izquierda) -->
            <div class="search-friends">
                <h3>Buscar Amigos</h3>
                <form method="POST">
                    <input type="text" name="nombre_usuario" placeholder="Buscar amigos..." required>
                    <button type="submit" name="solicitar_amigo">Enviar Solicitud</button>
                </form>

            </div>

            <!-- Sección de lista de amigos (centro) -->
            <div class="friends-list">
                <h3>Amigos</h3>
                <div class="friends-container">
                        <div class="friends-grid">
                            <?php foreach ($amigos as $amigo): ?>
                                <div class="friend-card">
                                    <p><?php echo htmlspecialchars($amigo['nombre_user']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                </div>
            </div>

            <!-- Sección del chat (derecha) -->
            <div class="chat-section">
                <h3>Chat</h3>
                <hr>
                <!-- Aquí iría el contenido del chat -->
            </div>
        </div>
    </div>
</body>
</html>
