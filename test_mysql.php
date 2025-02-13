<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

echo "Probando conexión a MySQL...<br>";
echo "Host: " . DB_HOST . "<br>";
echo "Usuario: " . DB_USER . "<br>";
echo "Base de datos: " . DB_NAME . "<br>";
echo "Usando contraseña: " . (DB_PASS ? "SÍ" : "NO") . "<br><br>";

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }
    echo "Conexión exitosa a MySQL.<br>";

    if (!$conn->select_db(DB_NAME)) {
        echo "La base de datos " . DB_NAME . " no existe. Intentando crearla...<br>";
        if ($conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME)) {
            echo "Base de datos creada exitosamente.<br>";
        } else {
            throw new Exception("Error al crear la base de datos: " . $conn->error);
        }
    }

    echo "Conexión exitosa a la base de datos " . DB_NAME . ".<br>";
    
    // Intento de crear la tabla 'areas'
    $conn->select_db(DB_NAME);
    $sql = "CREATE TABLE IF NOT EXISTS areas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        whatsapp_number VARCHAR(20) NOT NULL
    )";
    
    if ($conn->query($sql)) {
        echo "La tabla 'areas' ha sido creada o ya existe.<br>";
    } else {
        echo "Error al crear la tabla: " . $conn->error . "<br>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}