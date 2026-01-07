<?php
session_start();

require_once __DIR__ . "/../Modelo/modeloAdministrador.php";
require_once __DIR__ . "/../Modelo/modeloMovimientos.php";
require_once __DIR__ . "/../Modelo/conexion.php";

// Si no es admin, lo sacamos
if (empty($_SESSION['esAdmin'])) {
    header("Location: ../index.php");
    exit();
}

$baseUrl = "../Controlador/controladorAdministrador.php";

// Sección solicitada
$seccion = $_GET['seccion'] ?? 'usuarios';

// Instancia de modelos
$modelo    = new modeloAdministrador();
$modeloMov = new modeloMovimientos();

// Variables para la vista
$usuarios    = [];
$movimientos = [];
$idUsuario   = null;
$nombreU     = null;
$apellidoU   = null;
$correo      = null;

/* ========================================================
   POST: CRUD USUARIOS
   ======================================================== */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $seccion === 'usuarios') {

    // CREATE
    if (isset($_POST['add'])) {
        $nombreU   = trim($_POST['nombreU'] ?? '');
        $apellidoU = trim($_POST['apellidoU'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $pass      = $_POST['pass'] ?? '';

        if ($nombreU && $apellidoU && $correo && $pass && strlen($pass) >= 6) {
            $modelo->createUser($nombreU, $apellidoU, $correo, $pass);
        }

        header("Location: {$baseUrl}?seccion=usuarios");
        exit();
    }

    // UPDATE
    if (isset($_POST['update'])) {
        // CAMBIO: Eliminamos (int), MongoDB usa strings para el _id
        $idUsuario = $_POST['idUsuario'] ?? ''; 
        $nombreU   = trim($_POST['nombreU'] ?? '');
        $apellidoU = trim($_POST['apellidoU'] ?? '');
        $correo    = trim($_POST['correo'] ?? '');
        $pass      = $_POST['pass'] ?? '';

        if ($idUsuario && $nombreU && $apellidoU && $correo) {
            $modelo->updateUser($idUsuario, $nombreU, $apellidoU, $correo, $pass);
        }

        header("Location: {$baseUrl}?seccion=usuarios");
        exit();
    }

    // DELETE
    if (isset($_POST['delete'])) {
        // CAMBIO: Eliminamos (int)
        $idUsuario = $_POST['idUsuario'] ?? '';

        if ($idUsuario) {
            $modelo->deleteUser($idUsuario);
        }

        header("Location: {$baseUrl}?seccion=usuarios");
        exit();
    }
}

/* ========================================================
   GET: Cargar datos para edición
   ======================================================== */
if ($seccion === 'usuarios' && isset($_GET['edit'])) {

    $editId = $_GET['edit']; // CAMBIO: Es un string (ObjectId)
    $todos  = $modelo->readUser();

    foreach ($todos as $u) {
        // CAMBIO: En MongoDB el campo es '_id'. Lo convertimos a string para comparar.
        if ((string)$u['_id'] === $editId) {
            $idUsuario = (string)$u['_id'];
            $nombreU   = $u['nombreU'];
            $apellidoU = $u['apellidoU'];
            $correo    = $u['correo'];
            break;
        }
    }
}

/* ========================================================
   Cargar datos para la vista
   ======================================================== */
if ($seccion === 'usuarios') {
    $usuarios = $modelo->readUser();
}
elseif ($seccion === 'movimientos') {
    $movimientos = $modeloMov->obtenerMovimientos();
}

/* Evitar notices */
$idUsuario = $idUsuario ?? "";
$nombreU   = $nombreU ?? "";
$apellidoU = $apellidoU ?? "";
$correo    = $correo ?? "";

/* ========================================================
   Cargar vista
   ======================================================== */
include "../Vista/vistaAdministrador.php";