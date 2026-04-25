<?php
// SRP: Solo maneja operaciones de usuarios
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require_once 'conexion.php';
$conn   = getConexion();
$metodo = $_SERVER['REQUEST_METHOD'];

// DELETE /api/usuarios.php?id=U001
if ($metodo === 'DELETE') {
    $id = $_GET['id'] ?? '';
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'ID requerido']); exit; }

    // Verificar si tiene préstamos activos antes de eliminar
    $chkP = $conn->prepare("SELECT COUNT(*) FROM prestamos WHERE usuario_id = ? AND estado = 'activo'");
    $chkP->bind_param("s", $id);
    $chkP->execute();
    $chkP->bind_result($prestamosActivos);
    $chkP->fetch();
    $chkP->close();

    if ($prestamosActivos > 0) {
        http_response_code(400);
        echo json_encode(['error' => "No se puede eliminar: el usuario tiene $prestamosActivos préstamo(s) activo(s). Primero registra la devolución."]);
        $conn->close();
        exit;
    }

    // Eliminar reservas del usuario primero (FK)
    $d1 = $conn->prepare("DELETE FROM reservas WHERE usuario_id = ?");
    $d1->bind_param("s", $id);
    $d1->execute();
    $d1->close();

    // Eliminar préstamos devueltos del usuario (FK)
    $d2 = $conn->prepare("DELETE FROM prestamos WHERE usuario_id = ?");
    $d2->bind_param("s", $id);
    $d2->execute();
    $d2->close();

    // Ahora eliminar el usuario
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
    } else {
        echo json_encode(['mensaje' => 'Usuario eliminado correctamente']);
    }
    $stmt->close();

// POST /api/usuarios.php  { id, nombre, email, telefono }
} elseif ($metodo === 'POST') {
    $body     = json_decode(file_get_contents('php://input'), true);
    $id       = trim($body['id']       ?? '');
    $nombre   = trim($body['nombre']   ?? '');
    $email    = trim($body['email']    ?? '');
    $telefono = trim($body['telefono'] ?? '');

    if (!$id || !$nombre) {
        http_response_code(400);
        echo json_encode(['error' => 'id y nombre son obligatorios']);
        exit;
    }

    // Verificar duplicado
    $check = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
    $check->bind_param("s", $id);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['error' => "El usuario con ID $id ya existe"]);
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, email, telefono, prestamos_activos) VALUES (?, ?, ?, ?, '[]')");
    $stmt->bind_param("ssss", $id, $nombre, $email, $telefono);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode([
            'id'              => $id,
            'nombre'          => $nombre,
            'email'           => $email,
            'telefono'        => $telefono,
            'prestamosActivos'=> []
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al registrar el usuario']);
    }
    $stmt->close();

// GET /api/usuarios.php
} else {
    $result = $conn->query("SELECT * FROM usuarios ORDER BY creado_en DESC");

    if (!$result) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al consultar usuarios: ' . $conn->error]);
        $conn->close();
        exit;
    }

    $usuarios = [];
    while ($fila = $result->fetch_assoc()) {
        // Protección: prestamos_activos puede ser NULL en la BD
        $raw = $fila['prestamos_activos'] ?? '[]';
        $fila['prestamosActivos'] = json_decode($raw) ?? [];
        unset($fila['prestamos_activos']);
        $usuarios[] = $fila;
    }
    echo json_encode($usuarios);
}

$conn->close();