<?php
require_once 'config.php';
require_once 'includes/db.php';

$conn = getDbConnection();

$result = $conn->query("SELECT * FROM areas");

if ($result->num_rows > 0) {
    echo "<h2>Áreas en la base de datos:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Número de WhatsApp</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['whatsapp_number']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron áreas en la base de datos.";
}

$conn->close();