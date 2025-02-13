<?php
require_once 'config.php';
require_once 'includes/db.php';

$areas = [
    ['name' => 'Administración', 'whatsapp_number' => '+1234567890'],
    ['name' => 'Secretaría Ejecutiva', 'whatsapp_number' => '+1234567891'],
    ['name' => 'Dirección', 'whatsapp_number' => '+1234567892'],
    ['name' => 'PLACS', 'whatsapp_number' => '+1234567893'],
    ['name' => 'Relaciones Internacionales', 'whatsapp_number' => '+1234567894']
];

$conn = getDbConnection();

foreach ($areas as $area) {
    $stmt = $conn->prepare("INSERT INTO areas (name, whatsapp_number) VALUES (?, ?)");
    $stmt->bind_param("ss", $area['name'], $area['whatsapp_number']);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "Área '{$area['name']}' insertada correctamente.<br>";
    } else {
        echo "Error al insertar el área '{$area['name']}': " . $conn->error . "<br>";
    }
    $stmt->close();
}

$conn->close();
echo "Proceso de inserción completado.";