<?php
require_once 'db.php';
require_once 'whatsapp.php';

function sendWelcomeMenu($to) {
  $areas = getAreas();
  $menuText = "Bienvenido a nuestro servicio. Por favor, seleccione el área con la que desea comunicarse:\n\n";
  foreach ($areas as $index => $area) {
    $menuText .= ($index + 1) . ". " . $area['name'] . "\n";
  }
  $menuText .= "\nResponda con el número correspondiente al área deseada.";
  
  sendWhatsAppMessage($to, $menuText);
}

function handleMenuSelection($from, $message) {
  $areas = getAreas();
  $selection = intval($message);
  
  if ($selection > 0 && $selection <= count($areas)) {
    $selectedArea = $areas[$selection - 1];
    $targetNumber = $selectedArea['whatsapp_number'];
    
    sendWhatsAppMessage($from, "Gracias por su selección. Su mensaje será redirigido al área de " . $selectedArea['name'] . ". Un representante se pondrá en contacto con usted pronto.");
    sendWhatsAppMessage($targetNumber, "Nuevo mensaje del número $from para el área de " . $selectedArea['name'] . ". Por favor, contáctese con el cliente.");
    
    return true;
  }
  
  return false;
}