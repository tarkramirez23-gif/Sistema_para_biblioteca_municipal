<?php
// SRP: Solo devuelve estadísticas del sistema
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'conexion.php';
$conn = getConexion();

$totalLibros      = $conn->query("SELECT COUNT(*) AS n FROM libros")->fetch_assoc()['n'];
$disponibles      = $conn->query("SELECT COUNT(*) AS n FROM libros WHERE disponible = 1")->fetch_assoc()['n'];
$totalUsuarios    = $conn->query("SELECT COUNT(*) AS n FROM usuarios")->fetch_assoc()['n'];
$prestamosActivos = $conn->query("SELECT COUNT(*) AS n FROM prestamos WHERE estado = 'activo'")->fetch_assoc()['n'];
$reservasPend     = $conn->query("SELECT COUNT(*) AS n FROM reservas WHERE estado = 'pendiente'")->fetch_assoc()['n'];

echo json_encode([
    'totalLibros'       => (int)$totalLibros,
    'disponibles'       => (int)$disponibles,
    'prestados'         => (int)$totalLibros - (int)$disponibles,
    'totalUsuarios'     => (int)$totalUsuarios,
    'prestamosActivos'  => (int)$prestamosActivos,
    'reservasPendientes'=> (int)$reservasPend,
]);

$conn->close();
