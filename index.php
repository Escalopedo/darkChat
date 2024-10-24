<?php
// Comienza la sesión
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Online - Login y Registro</title>
    <link rel="stylesheet" href="./css/estilos.css">
</head>
<body>
    <div class="container">
        <div class="form-container login-container">
            <form action="login.php" method="POST">
                <h1>INICIAR SESIÓN</h1>
                <input type="email" name="correo_user" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">ENTRAR</button>
            </form>
        </div>

        <div class="form-container register-container">
            <form action="register.php" method="POST">
                <h1>REGISTRO</h1>
                <input type="text" name="nombre_user" placeholder="Nombre de Usuario" required>
                <input type="email" name="correo_user" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">REGISTRARSE</button>
            </form>
        </div>
    </div>
</body>
</html>