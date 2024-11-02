<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$username = $_SESSION['nombre_user'] ?? '';
$email = $_SESSION['correo_user'] ?? '';
$password = $_SESSION['contrasena'] ?? '';
session_unset(); // Limpiar las variables de sesión después de usarlas
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
            <form action="./js/validaciones.php" method="POST">
                <h1>REGISTRO</h1>
                <input type="text" name="nombre_user" placeholder="Nombre de Usuario" value="<?php echo htmlspecialchars($username); ?>" onblur="validateUsername(this)">
                <?php if (!empty($errors['nombre_user'])): ?>
                    <p class="error"><?php echo $errors['nombre_user']; ?></p>
                <?php endif; ?>

                <input type="email" name="correo_user" placeholder="Correo Electrónico" value="<?php echo htmlspecialchars($email); ?>" onblur="validateEmail(this)">
                <?php if (!empty($errors['correo_user'])): ?>
                    <p class="error"><?php echo $errors['correo_user']; ?></p>
                <?php endif; ?>

                <input type="password" name="contrasena" placeholder="Contraseña" value="<?php echo htmlspecialchars($password); ?>" onblur="validatePassword(this)">
                <?php if (!empty($errors['contrasena'])): ?>
                    <p class="error"><?php echo $errors['contrasena']; ?></p>
                <?php endif; ?>

                <button type="submit">REGISTRARSE</button>
            </form>
        </div>
    </div>
</body>
</html>
