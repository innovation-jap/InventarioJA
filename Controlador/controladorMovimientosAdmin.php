<?php
session_start();

// Verificar que solo el admin accede
if (empty($_SESSION['esAdmin'])) {
    header("Location: ../index.php");
    exit();
}

// Rutas robustas con __DIR__
require_once __DIR__ . "/../Modelo/modeloMovimientos.php";
require_once __DIR__ . "/../Modelo/conexion.php";

// Instanciar modelo (la conexión se gestiona dentro del constructor del modelo)
$modeloMov = new modeloMovimientos();

// =========================================================
// 💡 CAMBIO CLAVE: idUsuario ahora es un STRING (ObjectId)
// =========================================================
$idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : null;

// Obtener movimientos según corresponda
if ($idUsuario !== null && !empty($idUsuario)) {
    // Movimientos filtrados por usuario (el modelo ya maneja el new ObjectId)
    $movimientos = $modeloMov->obtenerMovimientosPorUsuario($idUsuario);
} else {
    // Todos los movimientos
    $movimientos = $modeloMov->obtenerMovimientos();
}

// Mostrar vista
include __DIR__ . "/../Vista/vistaMovimientosAdmin.php";
?>