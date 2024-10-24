<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Online - Login y Registro</title>
    <link rel="stylesheet" href="./css/estilos.css">
    <script>
        function toggleForms() {
            const loginContainer = document.getElementById('login-container');
            const registerContainer = document.getElementById('register-container');

            if (loginContainer.classList.contains('active')) {
                loginContainer.classList.remove('active');
                registerContainer.classList.add('active');
                registerContainer.style.display = 'block'; // Show register container
                loginContainer.style.display = 'none'; // Hide login container
            } else {
                registerContainer.classList.remove('active');
                loginContainer.classList.add('active');
                registerContainer.style.display = 'none'; // Hide register container
                loginContainer.style.display = 'block'; // Show login container
            }
        }

        // To initialize the active form
        window.onload = function() {
            document.getElementById('login-container').classList.add('active');
            document.getElementById('login-container').style.display = 'block';
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container" id="login-container">
            <form action="login.php" method="POST">
                <h1>Iniciar Sesión</h1>
                <input type="email" name="correo_user" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">Entrar</button>
                <p>¿No tienes cuenta? <a href="#register-container" onclick="toggleForms()">Regístrate</a></p>
            </form>
        </div>

        <div class="form-container" id="register-container">
            <form action="register.php" method="POST">
                <h1>Registro</h1>
                <input type="text" name="nombre_user" placeholder="Nombre de Usuario" required>
                <input type="email" name="correo_user" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">Registrarse</button>
                <p>¿Ya tienes cuenta? <a href="#login-container" onclick="toggleForms()">Inicia Sesión</a></p>
            </form>
        </div>
    </div>
</body>
</html>
