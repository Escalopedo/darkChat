<?php
session_start();

// Inicializar variables para almacenar mensajes de error y valores de los campos
$errors = [];
$username = $email = $password = "";

// Validación del formulario al enviarse
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación del nombre de usuario
    if (empty($_POST["nombre_user"])) {
        $errors['nombre_user'] = "El nombre de usuario es obligatorio.";
    } else {
        $username = trim($_POST["nombre_user"]);
        if (strlen($username) > 20) {
            $errors['nombre_user'] = "El nombre de usuario no puede contener más de 20 caracteres.";
        }
    }

    // Validación del correo electrónico
    if (empty($_POST["correo_user"])) {
        $errors['correo_user'] = "El correo electrónico es obligatorio.";
    } else {
        $email = trim($_POST["correo_user"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['correo_user'] = "El correo debe ser un correo válido.";
        }
    }

    // Validación de la contraseña
    if (empty($_POST["contrasena"])) {
        $errors['contrasena'] = "La contraseña es obligatoria.";
    } else {
        $password = $_POST["contrasena"];
        if (strlen($password) < 6) {
            $errors['contrasena'] = "La contraseña debe tener al menos 6 caracteres.";
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
            $errors['contrasena'] = "La contraseña no debe incluir caracteres especiales.";
        }
        if (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $errors['contrasena'] = "La contraseña debe incluir al menos una letra y un número.";
        }
    }

    // Si hay errores, guarda los errores y datos en la sesión y redirige a index.php
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['nombre_user'] = $username;
        $_SESSION['correo_user'] = $email;
        $_SESSION['contrasena'] = $password;
        header("Location: ../index.php");
        exit();
    } else {
        // Si no hay errores, envía el formulario a insRegistro.php
        include '../procesos/insRegistro.php';
        exit();
    }
}
?>
