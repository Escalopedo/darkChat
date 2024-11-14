<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$nombre_user = $_SESSION['nombre_user'];
$user_id = $_SESSION['user_id'];
$es_admin = $_SESSION['es_admin'] ?? false;

// Eliminar un usuario
if ($es_admin && isset($_POST['eliminar_usuario'])) {
    $id_usuario_eliminar = intval($_POST['id_usuario']);
    $query_eliminar = "DELETE FROM user WHERE id = $id_usuario_eliminar";
    mysqli_query($conn, $query_eliminar);
    echo "<script>alert('Usuario eliminado con éxito.');</script>";
}

// Actualizar un usuario
if ($es_admin && isset($_POST['actualizar_usuario'])) {
    $id_usuario_actualizar = intval($_POST['id_usuario']);
    $nuevo_nombre = htmlspecialchars($_POST['nombre_user']);
    $nuevo_correo = htmlspecialchars($_POST['correo_user']);
    $query_actualizar = "UPDATE user SET nombre_user = '$nuevo_nombre', correo_user = '$nuevo_correo' WHERE id = $id_usuario_actualizar";
    mysqli_query($conn, $query_actualizar);
    echo "<script>alert('Usuario actualizado con éxito.');</script>";
}

// Obtener la lista de todos los usuarios para el administrador
if ($es_admin) {
    $query_todos_usuarios = "SELECT * FROM user";
    $result_todos_usuarios = mysqli_query($conn, $query_todos_usuarios);
    $todos_usuarios = mysqli_fetch_all($result_todos_usuarios, MYSQLI_ASSOC);
}
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
        <header>
            <h2>Hola, <?php echo htmlspecialchars($nombre_user); ?></h2>
        </header>

        <?php if ($es_admin): ?>
            <div class="admin-panel">
                <h3>Panel de Administración</h3>
                <div class="user-list">
                    <h4>Usuarios Registrados</h4>
                    <?php foreach ($todos_usuarios as $usuario): ?>
                        <div class="user-card">
                            <form method="POST">
                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                <input type="text" name="nombre_user" value="<?php echo htmlspecialchars($usuario['nombre_user']); ?>" required>
                                <input type="email" name="correo_user" value="<?php echo htmlspecialchars($usuario['correo_user']); ?>" required>
                                <button type="submit" name="actualizar_usuario">Actualizar</button>
                                <button type="submit" name="eliminar_usuario" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="columns-container">
            <!-- Código existente para la funcionalidad de amigos -->
        </div>
    </div>
</body>
</html>
