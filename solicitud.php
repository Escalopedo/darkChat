<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Verifica si se ha enviado la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $emisor_id = $_SESSION['user_id'];

    // Insertar solicitud de amistad en la base de datos
    $query = "INSERT INTO solicitudes (id_emisor, id_receptor, estado) VALUES ('$emisor_id', '$user_id', 'PENDIENTE')";
    
    if (mysqli_query($conn, $query)) {
        echo "Solicitud de amistad enviada.";
    } else {
        echo "Error al enviar la solicitud: " . mysqli_error($conn);
    }
}

// Cierra la conexión
mysqli_close($conn);

// Redireccionar de vuelta a la página de chat
header("Location: index.php");
exit();
