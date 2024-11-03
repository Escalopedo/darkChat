-- Crear base de datos y usarla
CREATE DATABASE darkweb;
USE darkweb;

-- Tabla para user
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_user VARCHAR(100) NOT NULL,
    contrasena VARCHAR(100) NOT NULL, 
    correo_user VARCHAR(100) NOT NULL
);

-- Tabla para solicitudes de amistad
CREATE TABLE solicitudes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT NOT NULL,
    id_receptor INT NOT NULL,
    estado ENUM('ACEPTADA', 'RECHAZADA', 'PENDIENTE') NOT NULL,
    FOREIGN KEY (id_emisor) REFERENCES user(id), 
    FOREIGN KEY (id_receptor) REFERENCES user(id)
);

-- Tabla para amigos 
CREATE TABLE amigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user1 INT NOT NULL,
    id_user2 INT NOT NULL,
    FOREIGN KEY (id_user1) REFERENCES user(id),
    FOREIGN KEY (id_user2) REFERENCES user(id)
);

-- Tabla para mensajes de chat (uno a uno)
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT NOT NULL,
    id_receptor INT NOT NULL,
    texto VARCHAR(250) NOT NULL,
    FOREIGN KEY (id_emisor) REFERENCES user(id),
    FOREIGN KEY (id_receptor) REFERENCES user(id)
);  