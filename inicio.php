<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo esté en el mismo directorio

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$nombre_user = $_SESSION['nombre_user'];
$user_id = $_SESSION['user_id'];

// Manejar solicitud de amistad
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar_amigo'])) {
    $nombre_usuario = htmlspecialchars($_POST['nombre_usuario']);
    $id_emisor = $_SESSION['user_id'];

    // Obtener el ID del receptor por nombre
    $query_receptor = "SELECT id FROM user WHERE nombre_user = '$nombre_usuario'";
    $result_receptor = mysqli_query($conn, $query_receptor);
    
    if (mysqli_num_rows($result_receptor) > 0) {
        $receptor = mysqli_fetch_assoc($result_receptor);
        $id_receptor = $receptor['id'];

        // Verifica si la solicitud ya existe
        $query_check = "SELECT * FROM solicitudes WHERE id_emisor = $id_emisor AND id_receptor = $id_receptor";
        $result_check = mysqli_query($conn, $query_check);
        
        if (mysqli_num_rows($result_check) == 0) {
            $query_insert = "INSERT INTO solicitudes (id_emisor, id_receptor, estado) VALUES ($id_emisor, $id_receptor, 'PENDIENTE')";
            mysqli_query($conn, $query_insert);
            echo "<script>alert('Solicitud de amistad enviada a $nombre_usuario.');</script>";
        } else {
            echo "<script>alert('Ya has enviado una solicitud a $nombre_usuario.');</script>";
        }
    } else {
        echo "<script>alert('No hay ningún usuario con el nombre $nombre_usuario.');</script>";
    }
}

// Aceptar o denegar solicitudes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion_solicitud'])) {
    $id_solicitud = intval($_POST['id_solicitud']);
    
    if (isset($_POST['accion'])) {
        $accion = htmlspecialchars($_POST['accion']); // "aceptar" o "denegar"

        if ($accion === 'aceptar') {
            $query_aceptar = "UPDATE solicitudes SET estado = 'ACEPTADA' WHERE id = $id_solicitud";
            mysqli_query($conn, $query_aceptar);
            
            // Agregar a la tabla de amigos
            $query_amigos = "INSERT INTO amigos (id_user1, id_user2) VALUES ((SELECT id_emisor FROM solicitudes WHERE id = $id_solicitud), (SELECT id_receptor FROM solicitudes WHERE id = $id_solicitud))";
            mysqli_query($conn, $query_amigos);
            
            echo "<script>alert('Solicitud aceptada.');</script>";
        } else if ($accion === 'denegar') {
            $query_denegar = "UPDATE solicitudes SET estado = 'RECHAZADA' WHERE id = $id_solicitud";
            mysqli_query($conn, $query_denegar);
            echo "<script>alert('Solicitud denegada.');</script>";
        }
    }
}

// Si el usuario es el admin, mostrar el listado de todos los usuarios
if ($_SESSION['correo_user'] == 'admin@gmail.com' || $_SESSION['correo_user'] == 'agnes@gmail.com' || $_SESSION['correo_user'] == 'alberto@gmail.com') {
    // Eliminar usuario
    if (isset($_GET['eliminar_usuario'])) {
        $id_usuario = (int)$_GET['eliminar_usuario'];
        $query_eliminar = "DELETE FROM user WHERE id = $id_usuario";
        if (mysqli_query($conn, $query_eliminar)) {
            $_SESSION['success'] = "Usuario eliminado correctamente.";
            header("Location: inicio.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario.";
        }
    }

    // Obtener todos los usuarios de la base de datos
    $query_usuarios = "SELECT * FROM user WHERE id != {$_SESSION['user_id']}";
    $result_usuarios = mysqli_query($conn, $query_usuarios);
    $usuarios = mysqli_fetch_all($result_usuarios, MYSQLI_ASSOC);
} else {
    // Código para usuarios no administradores...

    // Obtener la lista de amigos
    $query_amigos = "
        SELECT DISTINCT u.* 
        FROM amigos a 
        JOIN user u ON (u.id = a.id_user1 OR u.id = a.id_user2) 
        WHERE (a.id_user1 = {$_SESSION['user_id']} OR a.id_user2 = {$_SESSION['user_id']}) 
        AND u.id != {$_SESSION['user_id']}
    ";
    $result_amigos = mysqli_query($conn, $query_amigos);
    $amigos = mysqli_fetch_all($result_amigos, MYSQLI_ASSOC);

    // Obtener todas las solicitudes pendientes
    $query_solicitudes = "SELECT s.*, u.nombre_user AS nombre_emisor FROM solicitudes s JOIN user u ON s.id_emisor = u.id WHERE s.id_receptor = {$_SESSION['user_id']} AND s.estado = 'PENDIENTE'";
    $result_solicitudes = mysqli_query($conn, $query_solicitudes);
    $solicitudes = mysqli_fetch_all($result_solicitudes, MYSQLI_ASSOC);

    // Aceptar o denegar solicitudes
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion_solicitud'])) {
        $id_solicitud = (int)$_POST['id_solicitud'];
        $accion = $_POST['accion_solicitud'];
        
        if ($accion == 'aceptar') {
            $query_aceptar = "UPDATE solicitudes SET estado = 'ACEPTADA' WHERE id = $id_solicitud";
            mysqli_query($conn, $query_aceptar);
            
            // Añadir como amigos en la tabla amigos
            $query_solicitud = "SELECT id_emisor, id_receptor FROM solicitudes WHERE id = $id_solicitud";
            $result_solicitud = mysqli_query($conn, $query_solicitud);
            $solicitud = mysqli_fetch_assoc($result_solicitud);
            
            if ($solicitud['id_emisor'] == $_SESSION['user_id']) {
                $amigo_id = $solicitud['id_receptor'];
            } else {
                $amigo_id = $solicitud['id_emisor'];
            }
            
            // Insertar en la tabla amigos
            $query_agregar_amigo = "INSERT INTO amigos (id_user1, id_user2) VALUES ({$_SESSION['user_id']}, $amigo_id)";
            mysqli_query($conn, $query_agregar_amigo);
        } elseif ($accion == 'denegar') {
            $query_denegar = "UPDATE solicitudes SET estado = 'DENEGADA' WHERE id = $id_solicitud";
            mysqli_query($conn, $query_denegar);
        }
        header("Location: inicio.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Inicio</title>
    <link rel="stylesheet" href="./css/chat.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <style>
        /* Estilos básicos para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-eliminar {
            background-color: #f44336; /* Rojo */
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-modificar {
            background-color: #4CAF50; /* Verde */
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn-eliminar:hover, .btn-modificar:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <header>
            <h2>Hola, <?php echo htmlspecialchars($nombre_user); ?></h2>
        </header>

        <div class="columns-container">
            <?php if ($_SESSION['correo_user'] == 'admin@gmail.com' || $_SESSION['correo_user'] == 'agnes@gmail.com' || $_SESSION['correo_user'] == 'alberto@gmail.com'): ?>
                <div class="users-list">
                    <h3>Usuarios</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre de usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre_user']); ?></td>
                                    <td>
                                        <a href="modificar.php?id=<?php echo $usuario['id']; ?>" class="btn-modificar">Modificar</a>
                                        <a href="inicio.php?eliminar_usuario=<?php echo $usuario['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar a este usuario?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- Código para usuarios no administradores -->

                <div class="search-friends">
                    <h3>Buscar Amigos</h3>
                    <form method="POST">
                        <input type="text" name="nombre_usuario" placeholder="Buscar amigos..." required>
                        <button type="submit" name="solicitar_amigo">Enviar</button>
                    </form>
                </div>

                <div class="friends-list">
                    <h3>Amigos</h3>
                    <div class="friends-container">
                        <?php foreach ($amigos as $amigo): ?>
                            <div class="friend-card">
                                <p><?php echo htmlspecialchars($amigo['nombre_user']); ?></p>
                                <form action="chat.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="amigo_id" value="<?php echo $amigo['id']; ?>">
                                    <button type="submit">Chat</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="solicitudes-list">
                    <h3>Solicitudes Pendientes</h3>
                    <div class="solicitudes-container">
                        <?php if (count($solicitudes) > 0): ?>
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <div class="solicitud-card">
                                    <p><?php echo htmlspecialchars($solicitud['nombre_emisor']); ?> ha enviado una solicitud.</p>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id']; ?>">
                                        <input type="hidden" name="accion_solicitud" value="aceptar">
                                        <button type="submit" name="accion_solicitud" value="aceptar">Aceptar</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id']; ?>">
                                        <input type="hidden" name="accion_solicitud" value="denegar">
                                        <button type="submit" name="accion_solicitud" value="denegar">Denegar</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No tienes solicitudes pendientes.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
