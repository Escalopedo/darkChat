
<?php

// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include './conexion.php';

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

    // Si en el select hay más de 0 consultas significa que hay un usuario que ya está creado con ese mail, si el valor es 0 onull significa que no
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('El correo ya está registrado. Intenta con otro.');</script>";
    } else {

        // Inserta el nuevo usuario en la base de datos
        $sql = "INSERT INTO user (nombre_user, contrasena, correo_user) VALUES ('$nombre_user', '$hashed_password', '$correo_user')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('USUARIO REGISTRADO.');</script>";
            header("Location: index.php"); 
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}


mysqli_close($conn);
?>
