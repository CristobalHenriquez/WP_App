<?php
require_once __DIR__ . '/../config.php';

function sendWhatsAppMessage($to, $message) {
    // Simulación del envío de mensajes
    echo "Simulando envío de mensaje a $to: $message<br>";
    
    // En un entorno de producción, aquí iría el código real para enviar mensajes
    // utilizando la API de WhatsApp Business
    /*
    $url = "https://graph.facebook.com/v17.0/" . WHATSAPP_PHONE_NUMBER_ID . "/messages";
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'text',
        'text' => ['body' => $message]
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\nAuthorization: Bearer " . WHATSAPP_TOKEN,
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Manejar el error
        error_log("Error sending WhatsApp message: " . error_get_last()['message']);
    }

    return $result;
    */
}