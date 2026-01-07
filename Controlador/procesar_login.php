<?php
session_start();

/* ============================================================
 * Includes
 * ============================================================ */
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Modelo/conexion.php";
require_once __DIR__ . "/../Modelo/modeloUsuario.php";

/* ============================================================
 * Instanciar modelo (MongoDB)
 * ============================================================ */
// El modelo ya no necesita recibir $db por parámetro, lo hace interno
$modeloUsuario = new modeloUsuario();

/* ============================================================
 * Procesar login
 * ============================================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombreU = trim($_POST['nombreU'] ?? '');
    $pass    = $_POST['pass'] ?? '';

    if ($nombreU === '' || $pass === '') {
        header("Location: " . BASE_URL . "index.php?error=vacio");
        exit();
    }

    // Buscar usuario por nombreU en MongoDB
    $row = $modeloUsuario->buscarPorNombreU($nombreU);

    // Verificamos contraseña
    if ($row && password_verify($pass, $row['pass'])) {

        // --- CAMBIOS CLAVE PARA MONGODB ---
        
        // 1. Convertimos el _id (ObjectId) a string para la sesión
        $_SESSION['idUsuario'] = (string)$row['_id'];
        
        $_SESSION['nombreU']   = $row['nombreU'];

        // 2. Nos aseguramos de que esAdmin sea tratado como booleano
        // (En MongoDB Atlas puede venir como int o bool dependiendo de cómo lo insertaste)
        $_SESSION['esAdmin']   = (bool)($row['esAdmin'] ?? false);

        // Redirección según rol
        if ($_SESSION['esAdmin']) {
            header("Location: " . BASE_URL . "Controlador/controladorAdministrador.php?seccion=usuarios");
        } else {
            header("Location: " . BASE_URL . "Controlador/controladorProducto.php?accion=listar");
        }
        exit();
    }

    // Si falla
    header("Location: " . BASE_URL . "index.php?error=incorrecto");
    exit();
}