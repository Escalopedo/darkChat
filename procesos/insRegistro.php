<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtiene y sanitiza los datos del formulario
    $nombre_user = htmlspecialchars($_POST['nombre_user']);
    $correo_user = htmlspecialchars($_POST['correo_user']);
    $contrasena = htmlspecialchars($_POST['contrasena']);
    
    // Validación del correo: debe contener '@'
    if (!filter_var($correo_user, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'El correo debe ser un email válido.';
        header('Location: ../index.php'); // Redirecciona sin JS
        exit();
    }
    
    // Validación de la contraseña: mínimo 6 caracteres
    if (strlen($contrasena) < 6) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres.';
        header('Location: ../index.php'); // Redirecciona sin JS
        exit();
    }

    // Hash de la contraseña usando BCRYPT
    $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

    // Verifica si el correo ya está registrado
    $check_email = "SELECT * FROM user WHERE correo_user='$correo_user'";
    $result = mysqli_query($conn, $check_email);

    // Verifica si se encontraron registros
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = 'El correo ya está registrado. Intenta con otro.';
        header('Location: ../index.php'); // Redirecciona sin JS
        exit();
    } else {
        // Inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO user (nombre_user, contrasena, correo_user) VALUES ('$nombre_user', '$hashed_password', '$correo_user')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = 'USUARIO REGISTRADO.';
            header('Location: ../index.php'); // Redirecciona sin JS
            exit();
        } else {
            $_SESSION['error'] = 'Error al registrar el usuario: ' . mysqli_error($conn);
            header('Location: ../index.php'); // Redirecciona sin JS
            exit();
        }
    }
}

// Cierra la conexión
mysqli_close($conn);
?>
