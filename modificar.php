<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$nombre_user = $_SESSION['nombre_user'];
$user_id = $_SESSION['user_id'];

// Verificar si el usuario es admin
if ($_SESSION['correo_user'] != 'admin@gmail.com' && $_SESSION['correo_user'] != 'agnes@gmail.com' && $_SESSION['correo_user'] != 'alberto@gmail.com') {
    header("Location: inicio.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Obtener los datos del usuario que se va a modificar
    $query_usuario = "SELECT * FROM user WHERE id = $id_usuario";
    $result_usuario = mysqli_query($conn, $query_usuario);
    $usuario = mysqli_fetch_assoc($result_usuario);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nuevo_nombre = $_POST['nombre_user'];
        $nuevo_correo = $_POST['correo_user'];

        // Actualizar los datos del usuario
        $query_update = "UPDATE user SET nombre_user = '$nuevo_nombre', correo_user = '$nuevo_correo' WHERE id = $id_usuario";
        if (mysqli_query($conn, $query_update)) {
            $_SESSION['success'] = "Usuario modificado correctamente.";
            header("Location: inicio.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al modificar el usuario.";
        }
    }
} else {
    header("Location: inicio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
</head>
<body>
    <h2>Modificar Usuario</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nombre_user">Nombre de Usuario:</label>
        <input type="text" name="nombre_user" value="<?php echo htmlspecialchars($usuario['nombre_user']); ?>" required>
        <br><br>
        
        <label for="correo_user">Correo de Usuario:</label>
        <input type="email" name="correo_user" value="<?php echo htmlspecialchars($usuario['correo_user']); ?>" required>
        <br><br>

        <button type="submit">Actualizar</button>
    </form>

    <br><br>
    <a href="inicio.php">Volver al inicio</a>
</body>
</html>
