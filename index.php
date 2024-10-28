<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARK WEB</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <script src="./js/validaciones.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container login-container">
            <form action="./procesos/selSesion.php" method="POST">
                <h1>INICIAR SESIÓN</h1>
                <input type="email" name="correo_user" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">ENTRAR</button>
            </form>
        </div>

        <div class="form-container register-container">
            <form action="./procesos/insRegistro.php" method="POST">
                <h1>REGISTRO</h1>
                <input type="text" name="nombre_user" placeholder="Nombre de Usuario" >
                <input type="email" name="correo_user" placeholder="Correo Electrónico" >
                <input type="password" name="contrasena" placeholder="Contraseña" >
                <button type="submit">REGISTRARSE</button>
            </form>
        </div>
    </div>
</body>
</html>