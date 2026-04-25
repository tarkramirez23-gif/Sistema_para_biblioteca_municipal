<?php
// SRP: Solo gestiona la conexión a MySQL
function getConexion() {
    $host     = 'localhost';
    $puerto   = 3306;
    $usuario  = 'root';
    $password = '';           // Dejar vacío si XAMPP no tiene contraseña
    $base     = 'bd_bibliotecamunicipal';

    $conn = new mysqli($host, $usuario, $password, $base, $puerto);

    if ($conn->connect_error) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
        exit;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
