<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_user = htmlspecialchars($_POST['correo_user']);
    $contrasena = htmlspecialchars($_POST['contrasena']);
    
    // Consulta para obtener el usuario
    $sql = "SELECT * FROM user WHERE correo_user='$correo_user'";
    $result = mysqli_query($conn, $sql);

    // Verifica si el usuario existe
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifica la contraseña
        if (password_verify($contrasena, $user['contrasena'])) {
            // Almacena la información del usuario en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre_user'] = $user['nombre_user'];
            
            // Redirige al usuario a su escritorio
            header("Location: ../escritorio.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.');</script>";
        }
    } else {
        echo "<script>alert('El usuario no existe.');</script>";
    }
}

// Cierra la conexión
mysqli_close($conn);
?>
