<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_user = htmlspecialchars($_POST['nombre_user']);
    $correo_user = htmlspecialchars($_POST['correo_user']);
    $contrasena = htmlspecialchars($_POST['contrasena']);
    
    // Hash de la contraseña
    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
    
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
            echo "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
