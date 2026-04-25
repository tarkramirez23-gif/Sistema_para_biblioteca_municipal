<?php
// SRP: Solo maneja operaciones de préstamos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require_once 'conexion.php';
$conn   = getConexion();
$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_GET['accion'] ?? '';

// PUT /api/prestamos.php?accion=devolver  { libroId, usuarioId }
if ($metodo === 'PUT' && $accion === 'devolver') {
    $body      = json_decode(file_get_contents('php://input'), true);
    $libroId   = trim($body['libroId']   ?? '');
    $usuarioId = trim($body['usuarioId'] ?? '');

    if (!$libroId || !$usuarioId) {
        http_response_code(400);
        echo json_encode(['error' => 'libroId y usuarioId son obligatorios']);
        exit;
    }

    // Buscar préstamo activo
    $stmt = $conn->prepare("SELECT id FROM prestamos WHERE libro_id = ? AND usuario_id = ? AND estado = 'activo' LIMIT 1");
    $stmt->bind_param("ss", $libroId, $usuarioId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontró un préstamo activo para ese libro y usuario']);
        $stmt->close(); exit;
    }
    $prestamo = $res->fetch_assoc();
    $stmt->close();

    $ahora = date('Y-m-d H:i:s');

    // Marcar préstamo como devuelto
    $upd = $conn->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = ? WHERE id = ?");
    $upd->bind_param("ss", $ahora, $prestamo['id']);
    $upd->execute();
    $upd->close();

    // Marcar libro como disponible
    $upd2 = $conn->prepare("UPDATE libros SET disponible = 1 WHERE id = ?");
    $upd2->bind_param("s", $libroId);
    $upd2->execute();
    $upd2->close();

    // Actualizar préstamos activos del usuario
    $selU = $conn->prepare("SELECT prestamos_activos FROM usuarios WHERE id = ?");
    $selU->bind_param("s", $usuarioId);
    $selU->execute();
    $resU = $selU->get_result()->fetch_assoc();
    $selU->close();
    $prestamosActivos = json_decode($resU['prestamos_activos'] ?? '[]', true);
    $prestamosActivos = array_values(array_filter($prestamosActivos, fn($p) => $p !== $libroId));
    $updU = $conn->prepare("UPDATE usuarios SET prestamos_activos = ? WHERE id = ?");
    $json = json_encode($prestamosActivos);
    $updU->bind_param("ss", $json, $usuarioId);
    $updU->execute();
    $updU->close();

    echo json_encode(['id' => $prestamo['id'], 'estado' => 'devuelto', 'fechaDevolucion' => $ahora]);

// POST /api/prestamos.php  { libroId, usuarioId }
} elseif ($metodo === 'POST') {
    $body      = json_decode(file_get_contents('php://input'), true);
    $libroId   = trim($body['libroId']   ?? '');
    $usuarioId = trim($body['usuarioId'] ?? '');

    if (!$libroId || !$usuarioId) {
        http_response_code(400);
        echo json_encode(['error' => 'libroId y usuarioId son obligatorios']);
        exit;
    }

    // Verificar que el libro existe y está disponible
    $chkL = $conn->prepare("SELECT disponible FROM libros WHERE id = ?");
    $chkL->bind_param("s", $libroId);
    $chkL->execute();
    $resL = $chkL->get_result();
    if ($resL->num_rows === 0) {
        http_response_code(404); echo json_encode(['error' => 'Libro no encontrado']); $chkL->close(); exit;
    }
    $libro = $resL->fetch_assoc();
    $chkL->close();
    if (!$libro['disponible']) {
        http_response_code(400); echo json_encode(['error' => 'El libro no está disponible']); exit;
    }

    // Verificar que el usuario existe
    $chkU = $conn->prepare("SELECT id, prestamos_activos FROM usuarios WHERE id = ?");
    $chkU->bind_param("s", $usuarioId);
    $chkU->execute();
    $resU = $chkU->get_result();
    if ($resU->num_rows === 0) {
        http_response_code(404); echo json_encode(['error' => 'Usuario no encontrado']); $chkU->close(); exit;
    }
    $usuario = $resU->fetch_assoc();
    $chkU->close();

    $ahora = date('Y-m-d H:i:s');
    $id    = 'P-' . $libroId . '-' . $usuarioId . '-' . time();

    // Insertar préstamo
    $ins = $conn->prepare("INSERT INTO prestamos (id, libro_id, usuario_id, fecha_prestamo, estado) VALUES (?, ?, ?, ?, 'activo')");
    $ins->bind_param("ssss", $id, $libroId, $usuarioId, $ahora);
    $ins->execute();
    $ins->close();

    // Marcar libro como no disponible
    $updL = $conn->prepare("UPDATE libros SET disponible = 0 WHERE id = ?");
    $updL->bind_param("s", $libroId);
    $updL->execute();
    $updL->close();

    // Actualizar préstamos activos del usuario
    $prestamosActivos   = json_decode($usuario['prestamos_activos'] ?? '[]', true);
    $prestamosActivos[] = $libroId;
    $updU = $conn->prepare("UPDATE usuarios SET prestamos_activos = ? WHERE id = ?");
    $jsonP = json_encode($prestamosActivos);
    $updU->bind_param("ss", $jsonP, $usuarioId);
    $updU->execute();
    $updU->close();

    http_response_code(201);
    echo json_encode(['id' => $id, 'libroId' => $libroId, 'usuarioId' => $usuarioId, 'fechaPrestamo' => $ahora, 'estado' => 'activo']);

// GET /api/prestamos.php
} else {
    $sql = "
        SELECT p.*,
               l.titulo AS libroTitulo,
               u.nombre AS usuarioNombre
        FROM prestamos p
        LEFT JOIN libros   l ON p.libro_id   = l.id
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha_prestamo DESC
    ";
    $result   = $conn->query($sql);
    $prestamos = [];
    while ($fila = $result->fetch_assoc()) {
        $prestamos[] = [
            'id'             => $fila['id'],
            'libroId'        => $fila['libro_id'],
            'usuarioId'      => $fila['usuario_id'],
            'libroTitulo'    => $fila['libroTitulo']    ?? '—',
            'usuarioNombre'  => $fila['usuarioNombre']  ?? '—',
            'fechaPrestamo'  => $fila['fecha_prestamo'],
            'fechaDevolucion'=> $fila['fecha_devolucion'],
            'estado'         => $fila['estado'],
        ];
    }
    echo json_encode($prestamos);
}

$conn->close();
