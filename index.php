<?php
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/whatsapp.php';

function handleIncomingMessage($message) {
    $sender = $message['from'];
    $body = $message['body'];

    $conn = getDbConnection();

    // Dividir el mensaje en palabras
    $words = explode(' ', strtolower($body));
    $foundArea = null;

    foreach ($words as $word) {
        if (strlen($word) < 3) continue; // Ignorar palabras muy cortas

        $stmt = $conn->prepare("SELECT name, whatsapp_number FROM areas WHERE LOWER(name) LIKE ?");
        $searchTerm = '%' . $word . '%';
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "Buscando: '$searchTerm'<br>";

        if ($result->num_rows > 0) {
            $foundArea = $result->fetch_assoc();
            break;
        }

        $stmt->close();
    }

    if ($foundArea) {
        $targetNumber = $foundArea['whatsapp_number'];
        $areaName = $foundArea['name'];
        echo "Área identificada: $areaName<br>";
        sendWhatsAppMessage($targetNumber, "Nuevo mensaje de $sender: $body");
        sendWhatsAppMessage($sender, "Tu mensaje ha sido enviado al área de $areaName. Pronto te contactarán.");
    } else {
        echo "No se pudo identificar un área específica.<br>";
        $availableAreas = getAvailableAreas($conn);
        $suggestedArea = suggestArea($conn, $body);
        
        $response = "Lo siento, no pude identificar el área específica en tu mensaje. ";
        if ($suggestedArea) {
            $response .= "¿Quizás quisiste decir '$suggestedArea'? ";
        }
        $response .= "Las áreas disponibles son: $availableAreas. ";
        $response .= "Por favor, intenta nuevamente especificando una de estas áreas.";
        sendWhatsAppMessage($sender, $response);
    }

    $conn->close();
}
function getAvailableAreas($conn) {
    $result = $conn->query("SELECT DISTINCT name FROM areas ORDER BY name");
    $areas = [];
    while ($row = $result->fetch_assoc()) {
        $areas[] = $row['name'];
    }
    return implode(", ", $areas);
}

// Simular mensajes entrantes para pruebas
$testMessages = [
    ['from' => '1234567890', 'body' => 'Hola, quiero hablar con administración'],
    ['from' => '9876543210', 'body' => 'Necesito información de PLACS'],
    ['from' => '5555555555', 'body' => 'Contactar con Relaciones Internacionales'],
    ['from' => '1111111111', 'body' => 'Quiero hablar con alguien de ventas'],
    ['from' => '2222222222', 'body' => 'Información sobre direccion']
];

function suggestArea($conn, $input) {
    $input = strtolower($input);
    $areas = [];
    $result = $conn->query("SELECT name FROM areas");
    while ($row = $result->fetch_assoc()) {
        $areas[] = $row['name'];
    }

    $bestMatch = null;
    $bestScore = 0;

    foreach ($areas as $area) {
        similar_text($input, strtolower($area), $percent);
        if ($percent > $bestScore) {
            $bestScore = $percent;
            $bestMatch = $area;
        }
    }

    return $bestScore > 50 ? $bestMatch : null;
}

foreach ($testMessages as $message) {
    echo "<h3>Probando mensaje: '{$message['body']}' de {$message['from']}</h3>";
    handleIncomingMessage($message);
    echo "<hr>";
}