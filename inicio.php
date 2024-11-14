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
                <!-- Código para los usuarios no administradores -->
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
