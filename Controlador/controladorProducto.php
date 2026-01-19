<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ============================================================
 * Includes y configuración
 * ============================================================ */
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Modelo/modeloProducto.php";
require_once __DIR__ . "/../Modelo/modeloMovimientos.php";
require_once __DIR__ . "/../Modelo/conexion.php";

/* ============================================================
 * Verificar sesión
 * ============================================================ */
// En MongoDB el idUsuario en la sesión debe ser el string del _id
$idUsuario = $_SESSION['idUsuario'] ?? null;

if (!$idUsuario) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

/* ============================================================
 * Conexión y modelos (MongoDB)
 * ============================================================ */
// Los modelos ahora gestionan su propia conexión internamente
$modelo    = new modeloProducto();
$modeloMov = new modeloMovimientos();

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'listar';
$datos  = [];

/* ============================================================
 * =============== ACCIONES CRUD + MOVIMIENTOS ===============
 * ============================================================ */

/* -------------------- AGREGAR -------------------- */
if ($accion === 'agregar' && $_SERVER["REQUEST_METHOD"] === "POST") {

    $nombreP      = trim($_POST['nombreP'] ?? '');
    $descripcionP = trim($_POST['descripcionP'] ?? '');
    $stock        = (int)($_POST['stock'] ?? 0);
    $almacen      = $_POST['almacen'] ?? 'Almacen 1';

    if ($nombreP && $stock >= 0) {
        // Pasamos el idUsuario (string) al modelo
        $modelo->agregarProducto($idUsuario, $nombreP, $descripcionP, $stock, $almacen);
    }

    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- ELIMINAR -------------------- */
if ($accion === 'eliminar' && isset($_GET['id'])) {

    $idProducto = $_GET['id']; // CAMBIO: Ahora es un string (ObjectId)

    if (!empty($idProducto)) {
        $modelo->eliminarProducto($idProducto);
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
    // NUEVO: Capturar la nueva ubicación del almacén en la edición
    $almacen      = $_POST['almacen'] ?? ''; 

    if (!empty($idProducto) && $nombreP && $stock >= 0) {
        // CAMBIO: Se añade el parámetro $almacen (asegúrate de que tu modeloProducto->actualizarProducto ahora acepte este 4to parámetro)
        $modelo->actualizarProducto($idProducto, $nombreP, $descripcionP, $stock, $almacen);
    }

    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* -------------------- SALIDA DE PRODUCTO -------------------- */
if ($accion === 'salida' && $_SERVER["REQUEST_METHOD"] === "POST") {

    $idProducto = $_POST['idProducto']; // CAMBIO: String
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

    $idProducto       = $_POST['idProducto']; // CAMBIO: String
    $cantidadDevuelta = (int)$_POST['cantidad'];

    if (!empty($idProducto) && $cantidadDevuelta > 0) {
        $modelo->registrarDevolucion($idUsuario, $idProducto, $cantidadDevuelta);
    }

    header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
    exit();
}

/* Formulario devolución (GET) */
if ($accion === 'devolucion' && $_SERVER["REQUEST_METHOD"] === "GET") {
    $datos['productos'] = $modelo->obtenerProductos();
    include __DIR__ . "/../Vista/vistaNuevaDevolucion.php";
    exit();
}

/* ============================================================
 * =============== SWITCH PRINCIPAL DE VISTAS =================
 * ============================================================ */

switch ($accion) {

    case 'listar':
        $datos['productos']   = $modelo->obtenerProductos();
        $datos['movimientos'] = $modeloMov->obtenerMovimientos();
        include __DIR__ . "/../Vista/vistaProductos.php";
        break;

    case 'agregar':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            include __DIR__ . "/../Vista/vistaNuevoProducto.php";
        }
        break;

    case 'salida':
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $datos['productos'] = $modelo->obtenerProductos();
            include __DIR__ . "/../Vista/vistaNuevaSalida.php";
        }
        break;

    case 'movimientos':
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;
        $idProducto  = $_GET['id_producto'] ?? null; // String ObjectId
        $idUsuarioF  = $_GET['id_usuario'] ?? null;  // String ObjectId

        $datos['productos'] = $modelo->obtenerProductos();
        $datos['usuarios']  = $modelo->obtenerUsuarios();

        $datos['movimientos'] = $modeloMov->obtenerMovimientosFiltrados(
            $fechaInicio,
            $fechaFin,
            $idProducto,
            $idUsuarioF
        );

        include __DIR__ . "/../Vista/vistaMovimientos.php";
        break;

    case 'exportar':
        $fechaInicio = $_GET['fecha_inicio'] ?? null;
        $fechaFin    = $_GET['fecha_fin'] ?? null;
        $idProducto  = $_GET['id_producto'] ?? null;
        $idUsuarioF  = $_GET['id_usuario'] ?? null;

        $movimientos = $modeloMov->obtenerMovimientosFiltrados(
            $fechaInicio,
            $fechaFin,
            $idProducto,
            $idUsuarioF
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=movimientos_inventario.csv');

        $output    = fopen('php://output', 'w');
        $delimiter = ';';

        fputcsv($output, [
            'ID Movimiento',
            'ID Producto',
            'Nombre del Producto',
            'Tipo',
            'Cantidad',
            'Usuario',
            'Fecha y Hora'
        ], $delimiter);

        foreach ($movimientos as $fila) {
            fputcsv($output, [
                (string)$fila['idMovimiento'], // Aseguramos que el ObjectId se exporte como texto
                (string)($fila['idProducto'] ?? ''),
                $fila['nombreP'],
                $fila['tipo'],
                $fila['cantidad'],
                $fila['nombreU'],
                // Si fechaM es un objeto UTCDateTime de MongoDB, hay que formatearlo
                is_object($fila['fechaM']) ? $fila['fechaM']->toDateTime()->format('Y-m-d H:i:s') : $fila['fechaM']
            ], $delimiter);
        }

        fclose($output);
        exit();

    default:
        header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
        exit();
}