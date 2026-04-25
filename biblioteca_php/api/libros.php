<?php
// SRP: Solo maneja operaciones de libros
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require_once 'conexion.php';
$conn   = getConexion();
$metodo = $_SERVER['REQUEST_METHOD'];

// DELETE /api/libros.php?id=L001
if ($metodo === 'DELETE') {
    $id = $_GET['id'] ?? '';
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'ID requerido']); exit; }

    $stmt = $conn->prepare("DELETE FROM libros WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        http_response_code(404); echo json_encode(['error' => 'Libro no encontrado']);
    } else {
        echo json_encode(['mensaje' => 'Libro eliminado']);
    }
    $stmt->close();

// POST /api/libros.php  { id, titulo, autor, isbn }
} elseif ($metodo === 'POST') {
    $body  = json_decode(file_get_contents('php://input'), true);
    $id    = trim($body['id']    ?? '');
    $titulo = trim($body['titulo'] ?? '');
    $autor  = trim($body['autor']  ?? '');
    $isbn   = trim($body['isbn']   ?? '');

    if (!$id || !$titulo || !$autor) {
        http_response_code(400);
        echo json_encode(['error' => 'id, titulo y autor son obligatorios']);
        exit;
    }

    // Verificar duplicado
    $check = $conn->prepare("SELECT id FROM libros WHERE id = ?");
    $check->bind_param("s", $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['error' => "El libro con ID $id ya existe"]);
        $check->close(); exit;
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO libros (id, titulo, autor, isbn, disponible) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("ssss", $id, $titulo, $autor, $isbn);
    $stmt->execute();

    http_response_code(201);
    echo json_encode(['id' => $id, 'titulo' => $titulo, 'autor' => $autor, 'isbn' => $isbn, 'disponible' => true]);
    $stmt->close();

// GET /api/libros.php
} else {
    $result = $conn->query("SELECT * FROM libros ORDER BY creado_en DESC");
    $libros = [];
    while ($fila = $result->fetch_assoc()) {
        $fila['disponible'] = (bool)$fila['disponible'];
        $libros[] = $fila;
    }
    echo json_encode($libros);
}

$conn->close();
