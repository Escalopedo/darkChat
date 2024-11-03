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
        echo "<script>alert('El correo debe ser un email válido.'); window.location.href = '../index.php';</script>";
        exit();
    }
    
    // Validación de la contraseña: mínimo 6 caracteres
    if (strlen($contrasena) < 6) {
        echo "<script>alert('La contraseña debe tener al menos 6 caracteres.'); window.location.href = '../index.php';</script>";
        exit();
    }

    // Hash de la contraseña usando BCRYPT
    $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

    // Verifica si el correo ya está registrado
    $check_email = "SELECT * FROM user WHERE correo_user='$correo_user'";
    $result = mysqli_query($conn, $check_email);

    // Verifica si se encontraron registros
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('El correo ya está registrado. Intenta con otro.'); window.location.href = '../index.php';</script>";
    } else {
        // Inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO user (nombre_user, contrasena, correo_user) VALUES ('$nombre_user', '$hashed_password', '$correo_user')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('USUARIO REGISTRADO.'); window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario: " . mysqli_error($conn) . "'); window.location.href = '../index.php';</script>";
        }
    }
}

// Cierra la conexión
mysqli_close($conn);
?>
