<?php
require_once 'includes/menu.php';

$input = json_decode(file_get_contents('php://input'), true);

// Asumiendo que la estructura del mensaje de WhatsApp es como se muestra a continuación
// Puede que necesites ajustar esto según la estructura real de los mensajes que recibes
$message = $input['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] ?? '';
$sender = $input['entry'][0]['changes'][0]['value']['messages'][0]['from'] ?? '';

if (!$message || !$sender) {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
  exit;
}

// Si el mensaje es "menu" o es el primer mensaje, enviar el menú de bienvenida
if (strtolower($message) == 'menu' || !isset($_SESSION[$sender])) {
  sendWelcomeMenu($sender);
  $_SESSION[$sender] = 'menu_sent';
} else if ($_SESSION[$sender] == 'menu_sent') {
  // Si ya se envió el menú, manejar la selección del usuario
  $handled = handleMenuSelection($sender, $message);
  if ($handled) {
    unset($_SESSION[$sender]); // Reiniciar el estado para este remitente
  } else {
    sendWhatsAppMessage($sender, "Lo siento, no entendí su selección. Por favor, responda con un número del menú o escriba 'menu' para ver las opciones nuevamente.");
  }
} else {
  // Si no estamos en el proceso de selección de menú, reiniciar al menú principal
  sendWelcomeMenu($sender);
  $_SESSION[$sender] = 'menu_sent';
}

echo json_encode(['status' => 'success']);