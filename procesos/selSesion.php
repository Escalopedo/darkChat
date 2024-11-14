<?php
// Inicia la sesión
session_start();
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_user = htmlspecialchars($_POST['correo_user']);
    $contrasena = htmlspecialchars($_POST['contrasena']);

    // Verifica si el usuario es el administrador
    if ($correo_user === 'admin@gmail.com' && $contrasena === 'qweQWE123') {
        $_SESSION['user_id'] = 0; // ID especial para el administrador
        $_SESSION['nombre_user'] = 'Administrador';
        $_SESSION['es_admin'] = true;
        header("Location: ../inicio.php");
        exit();
    }

    // Consulta para usuarios regulares
    $sql = "SELECT * FROM user WHERE correo_user='$correo_user'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_user'] = $user['nombre_user'];
            $_SESSION['es_admin'] = false; // No es administrador
            header("Location: ../inicio.php");
            exit();
        } else {
            $_SESSION['error_message'] = 'Contraseña incorrecta.';
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Usuario no existe.';
        header("Location: ../index.php");
        exit();
    }
}
mysqli_close($conn);
?>
