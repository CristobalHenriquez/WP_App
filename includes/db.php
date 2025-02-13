<?php
require_once __DIR__ . '/../config.php';

function getDbConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function getAreas() {
    $conn = getDbConnection();
    $result = $conn->query("SELECT * FROM areas");
    $areas = [];
    while ($row = $result->fetch_assoc()) {
        $areas[] = $row;
    }
    $conn->close();
    return $areas;
}

function getWhatsAppNumberForArea($areaName) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT whatsapp_number FROM areas WHERE name = ?");
    $stmt->bind_param("s", $areaName);
    $stmt->execute();
    $result = $stmt->get_result();
    $number = $result->fetch_assoc()['whatsapp_number'] ?? null;
    $stmt->close();
    $conn->close();
    return $number;
}