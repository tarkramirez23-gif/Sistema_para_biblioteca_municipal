<?php
// SRP: Solo maneja operaciones de reservas
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require_once 'conexion.php';
$conn   = getConexion();
$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_GET['accion'] ?? '';
$id     = $_GET['id']     ?? '';

// PUT /api/reservas.php?accion=cancelar&id=5
if ($metodo === 'PUT' && $accion === 'cancelar') {
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'ID requerido']); exit; }

    $stmt = $conn->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['mensaje' => 'Reserva cancelada']);

// POST /api/reservas.php  { libroId, usuarioId }
} elseif ($metodo === 'POST') {
    $body      = json_decode(file_get_contents('php://input'), true);
    $libroId   = trim($body['libroId']   ?? '');
    $usuarioId = trim($body['usuarioId'] ?? '');

    if (!$libroId || !$usuarioId) {
        http_response_code(400);
        echo json_encode(['error' => 'libroId y usuarioId son obligatorios']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO reservas (libro_id, usuario_id, estado) VALUES (?, ?, 'pendiente')");
    $stmt->bind_param("ss", $libroId, $usuarioId);
    $stmt->execute();
    $nuevoId = $conn->insert_id;
    $stmt->close();

    http_response_code(201);
    echo json_encode(['id' => $nuevoId, 'libroId' => $libroId, 'usuarioId' => $usuarioId, 'estado' => 'pendiente']);

// GET /api/reservas.php
} else {
    $sql = "
        SELECT r.*, l.titulo AS libro_titulo, u.nombre AS usuario_nombre
        FROM reservas r
        LEFT JOIN libros   l ON r.libro_id   = l.id
        LEFT JOIN usuarios u ON r.usuario_id = u.id
        ORDER BY r.fecha_reserva DESC
    ";
    $result  = $conn->query($sql);
    $reservas = [];
    while ($fila = $result->fetch_assoc()) {
        $reservas[] = $fila;
    }
    echo json_encode($reservas);
}

$conn->close();
