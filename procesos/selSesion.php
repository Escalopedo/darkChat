<?php
session_start();
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_user = htmlspecialchars($_POST['correo_user']);
    $contrasena = htmlspecialchars($_POST['contrasena']);

    $sql = "SELECT * FROM user WHERE correo_user='$correo_user'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_user'] = $user['nombre_user'];
            $_SESSION['correo_user'] = $user['correo_user'];
            $_SESSION['es_admin'] = ($correo_user === 'admin@gmail.com') ? true : false;
            
            header("Location: ../inicio.php");
            exit();
        } else {
            $_SESSION['error_message'] = 'CONTRASEÑA INCORRECTA.';
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'USUARIO NO EXISTE.';
        header("Location: ../index.php");
        exit();
    }
}

mysqli_close($conn);
?>
