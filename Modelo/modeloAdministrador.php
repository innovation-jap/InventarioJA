<?php
require_once "conexion.php";
// Importamos la clase para manejar IDs de MongoDB
use MongoDB\BSON\ObjectId;

class modeloAdministrador {

    private $db;

    public function __construct() {
        $conexion = new Database();
        $this->db = $conexion->conectar(); // Ahora es una instancia de MongoDB\Database
    }

    // Crear usuario
    public function createUser($nombreU, $apellidoU, $correo, $pass) {
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        try {
            // insertOne reemplaza al INSERT INTO
            $resultado = $this->db->usuario->insertOne([
                "nombreU"   => $nombreU,
                "apellidoU" => $apellidoU,
                "correo"    => $correo,
                "pass"      => $hashedPass,
                "esAdmin"   => 0 // Valor por defecto
            ]);
            return $resultado->getInsertedCount() > 0;
        } catch (Exception $e) {
            error_log("Error createUser: " . $e->getMessage());
            return false;
        }
    }

    // Read usuario
    public function readUser() {
        // find() equivale a SELECT
        $usuarios = $this->db->usuario->find();
        return iterator_to_array($usuarios);
    }

    // Actualizar usuario
    public function updateUser($idUsuario, $nombreU, $apellidoU, $correo, $pass) {
        $updateData = [
            "nombreU"   => $nombreU,
            "apellidoU" => $apellidoU,
            "correo"    => $correo
        ];

        // Si envían password nuevo, lo hasheamos y agregamos al array de actualización
        if (!empty($pass)) {
            $updateData["pass"] = password_hash($pass, PASSWORD_DEFAULT);
        }

        try {
            // updateOne reemplaza al UPDATE ... WHERE
            $resultado = $this->db->usuario->updateOne(
                ["_id" => new ObjectId($idUsuario)], // Filtro por ID
                ['$set' => $updateData]             // Datos a actualizar
            );
            return $resultado->getModifiedCount() >= 0; 
        } catch (Exception $e) {
            error_log("Error updateUser: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar usuario
    public function deleteUser($idUsuario) {
        // En MongoDB Atlas (tier gratuito), no solemos usar transacciones complejas.
        // Ejecutamos las eliminaciones secuencialmente.
        try {
            $idObj = new ObjectId($idUsuario);

            // Eliminar movimientos del usuario
            $this->db->movimientos->deleteMany(["idUsuario" => $idObj]);
            
            // Eliminar el usuario
            $resultado = $this->db->usuario->deleteOne(["_id" => $idObj]);
            
            return $resultado->getDeletedCount() > 0;
        } catch (Exception $e) {
            error_log("Error deleteUser: " . $e->getMessage());
            return false;
        }
    }
}
?>