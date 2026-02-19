<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Modelo/modeloProducto.php";
require_once __DIR__ . "/../Modelo/modeloMovimientos.php";
require_once __DIR__ . "/../Modelo/conexion.php";

$idUsuario = $_SESSION['idUsuario'] ?? null;
if (!$idUsuario) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

$modelo    = new modeloProducto();
$modeloMov = new modeloMovimientos();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';

/**
 * Función para subir a Cloudinary con tus datos: dtj2hwjcx / inventario_preset
 */
function subirCloudinary($file_tmp) {
    $cloud_name = "dtj2hwjcx";       
    $upload_preset = "inventario_preset"; 
    
    $url = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($file_tmp),
        'upload_preset' => $upload_preset
    ]);
    
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    return $result['secure_url'] ?? null;
}

/* -------------------- AGREGAR -------------------- */
if ($accion === 'agregar' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreP      = trim($_POST['nombreP'] ?? '');
    $descripcionP = trim($_POST['descripcionP'] ?? '');
    $stock        = (int)($_POST['stock'] ?? 0);
    $almacen      = $_POST['almacen'] ?? 'Almacén 1';
    
    $rutaImagen = "https://placehold.co/600x400/102222/0df2f2?text=" . urlencode($nombreP);

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $urlNube = subirCloudinary($_FILES['imagen']['tmp_name']);
        if ($urlNube) $rutaImagen = $urlNube;
    }

    if ($nombreP && $stock >= 0) {
        $modelo->agregarProducto($idUsuario, $nombreP, $descripcionP, $stock, $almacen, $rutaImagen);
    }

    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- EDITAR -------------------- */
if ($accion === 'editar' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $idProducto   = $_POST['idProducto']; 
    $nombreP      = trim($_POST['nombreP'] ?? '');
    $descripcionP = trim($_POST['descripcionP'] ?? '');
    $stock        = (int)($_POST['stock'] ?? 0);
    $almacen      = $_POST['almacen'] ?? ''; 

    $rutaImagen = null; 
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaImagen = subirCloudinary($_FILES['imagen']['tmp_name']);
    }

    if (!empty($idProducto) && $nombreP && $stock >= 0) {
        $modelo->actualizarProducto($idProducto, $nombreP, $descripcionP, $stock, $almacen, $rutaImagen);
    }

    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- ELIMINAR -------------------- */
if ($accion === 'eliminar' && isset($_GET['id'])) {
    $idProducto = $_GET['id'];
    if (!empty($idProducto)) {
        $modelo->eliminarProducto($idProducto);
    }
    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- SALIDA DE PRODUCTO -------------------- */
if ($accion === 'salida' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $idProducto = $_POST['idProducto'];
    $cantidad   = (int)$_POST['cantidad'];

    if (!empty($idProducto) && $cantidad > 0) {
        $stock_actual = $modelo->obtenerStock($idProducto);
        if ($cantidad <= $stock_actual) {
            $modelo->registrarSalida("salida", $idUsuario, $idProducto, $cantidad);
        }
    }
    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- DEVOLUCIÓN -------------------- */
if ($accion === 'devolucion' && $_SERVER["REQUEST_METHOD"] === "POST") {
    $idProducto       = $_POST['idProducto'];
    $cantidadDevuelta = (int)$_POST['cantidad'];

    if (!empty($idProducto) && $cantidadDevuelta > 0) {
        $modelo->registrarDevolucion($idUsuario, $idProducto, $cantidadDevuelta);
    }
    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* ============================================================
 * =============== SWITCH PRINCIPAL DE VISTAS =================
 * ============================================================ */

switch ($accion) {
    case 'listar':
        $porPagina = 10;
        $paginaActual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($paginaActual < 1) $paginaActual = 1;
        $skip = ($paginaActual - 1) * $porPagina;

        $datos['productos'] = $modelo->obtenerProductosPaginados($skip, $porPagina);
        $totalRegistros = $modelo->contarProductos(); 
        $datos['totalPaginas'] = ceil($totalRegistros / $porPagina);
        $datos['paginaActual'] = $paginaActual;

        include __DIR__ . "/../Vista/vistaProductos.php";
        break;

    case 'movimientos':
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;
        $idProducto  = $_GET['id_producto'] ?? null;
        $idUsuarioF  = $_GET['id_usuario'] ?? null;

        $datos['productos'] = $modelo->obtenerProductos();
        $datos['usuarios']  = $modelo->obtenerUsuarios();

        $datos['movimientos'] = $modeloMov->obtenerMovimientosFiltrados($fechaInicio, $fechaFin, $idProducto, $idUsuarioF);
        include __DIR__ . "/../Vista/vistaMovimientos.php";
        break;

    case 'exportar':
        // Lógica de exportación CSV mantenida igual
        $movimientos = $modeloMov->obtenerMovimientosFiltrados(
            $_GET['fecha_inicio'] ?? null,
            $_GET['fecha_fin'] ?? null,
            $_GET['id_producto'] ?? null,
            $_GET['id_usuario'] ?? null
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=movimientos_inventario.csv');
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['ID Movimiento','Nombre del Producto','Ubicación','Tipo','Cantidad','Usuario','Fecha y Hora'], ';');

        foreach ($movimientos as $fila) {
            fputcsv($output, [
                (string)$fila['idMovimiento'],
                $fila['nombreP'] ?? 'N/A',
                $fila['almacen'] ?? 'No asignado',
                ucfirst($fila['tipo'] ?? 'N/A'),
                $fila['cantidad'],
                $fila['nombreU'] ?? 'N/A',
                is_object($fila['fechaM']) ? $fila['fechaM']->toDateTime()->format('d/m/Y H:i:s') : $fila['fechaM']
            ], ';');
        }
        fclose($output);
        exit();
}