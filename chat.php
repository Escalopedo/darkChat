<?php
session_start();
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión y si se seleccionó un amigo
if (!isset($_SESSION['user_id']) || !isset($_POST['id_receptor'])) {
    header("Location: inicio.php");
    exit();
}

$id_emisor = $_SESSION['user_id'];
$id_receptor = intval($_POST['id_receptor']);
$mensaje = htmlspecialchars($_POST['mensaje']);

// Insertar el mensaje en la base de datos
if (!empty($mensaje)) {
    $query = "INSERT INTO mensajes (id_emisor, id_receptor, texto) VALUES ($id_emisor, $id_receptor, '$mensaje')";
    mysqli_query($conn, $query);
}

// Redirigir a inicio.php con el amigo seleccionado
header("Location: inicio.php?amigo_id=$id_receptor");
exit();
?>
