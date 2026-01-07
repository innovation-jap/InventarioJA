<?php
require_once __DIR__ . "/conexion.php";
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class modeloUsuario {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar(); // Ahora es la instancia de MongoDB\Database
    }

    /* ====================================================
       BUSCAR USUARIO POR NOMBRE (Para el Login)
       ==================================================== */
    public function buscarPorNombreU(string $nombreU): ?array {
        // findOne reemplaza a SELECT * ... LIMIT 1
        $res = $this->db->usuario->findOne(['nombreU' => $nombreU]);
        return $res ? (array)$res : null;
    }

    /* ====================================================
       BUSCAR USUARIO POR CORREO
       ==================================================== */
    public function buscarPorCorreo(string $correo): ?array {
        $res = $this->db->usuario->findOne(
            ['correo' => $correo],
            ['projection' => ['_id' => 1]] // Solo traemos el ID para optimizar
        );
        return $res ? (array)$res : null;
    }

    /* ====================================================
       GUARDAR TOKEN PARA RESET PASSWORD
       ==================================================== */
    public function guardarTokenReset($idUsuario, string $token, string $expira): void {
        // Convertimos la fecha de expiración a objeto UTCDateTime de MongoDB
        $fechaExpira = new UTCDateTime(strtotime($expira) * 1000);

        $this->db->usuario->updateOne(
            ['_id' => new ObjectId($idUsuario)],
            ['$set' => [
                'reset_token' => $token,
                'reset_expires' => $fechaExpira
            ]]
        );
    }

    /* ====================================================
       BUSCAR USUARIO POR TOKEN
       ==================================================== */
    public function buscarPorToken(string $token): ?array {
        $res = $this->db->usuario->findOne(
            ['reset_token' => $token],
            ['projection' => ['_id' => 1, 'reset_expires' => 1]]
        );
        return $res ? (array)$res : null;
    }

    /* ====================================================
       ACTUALIZAR PASSWORD Y LIMPIAR TOKEN
       ==================================================== */
    public function actualizarPassword($idUsuario, string $hash): void {
        $this->db->usuario->updateOne(
            ['_id' => new ObjectId($idUsuario)],
            ['$set' => [
                'pass' => $hash,
                'reset_token' => null,
                'reset_expires' => null
            ]]
        );
    }
}
?>