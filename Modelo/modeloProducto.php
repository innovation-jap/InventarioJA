<?php
require_once __DIR__ . "/conexion.php";
// Importante para manejar los IDs de MongoDB
use MongoDB\BSON\ObjectId;

class modeloProducto {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar(); // Ahora es una instancia de MongoDB\Database
    }

    /* ===========================
        OBTENER USUARIOS
    ============================ */
    public function obtenerUsuarios() {
        // Buscamos en la colección 'usuario', ordenando por nombreU (1 = Ascendente)
        $usuarios = $this->db->usuario->find([], [
            'sort' => ['nombreU' => 1]
        ]);
        return iterator_to_array($usuarios);
    }

    /* ===========================
        OBTENER PRODUCTOS
    ============================ */
    public function obtenerProductos() {
        // find() equivale a SELECT *
        $productos = $this->db->producto->find();
        return iterator_to_array($productos);
    }

    /* ===========================
        AGREGAR PRODUCTO + MOVIMIENTO
    ============================ */
    public function agregarProducto($idUsuario, $nombreP, $descripcionP, $stock) {
        try {
            // Insertar producto
            $nuevoProducto = [
                'idUsuario' => $idUsuario,
                'nombreP' => $nombreP,
                'descripcionP' => $descripcionP,
                'stock' => (int)$stock, // Aseguramos que sea número
                'fechaI' => new \MongoDB\BSON\UTCDateTime()
            ];
            
            $resultado = $this->db->producto->insertOne($nuevoProducto);
            $idProducto = $resultado->getInsertedId();

            // Registrar movimiento
            $this->db->movimientos->insertOne([
                'tipo' => 'entrada',
                'idUsuario' => $idUsuario,
                'idProducto' => $idProducto,
                'cantidad' => (int)$stock,
                'fecha' => new \MongoDB\BSON\UTCDateTime()
            ]);

            return $idProducto;

        } catch (Exception $e) {
            return false;
        }
    }

    /* ===========================
        ACTUALIZAR PRODUCTO
    ============================ */
    public function actualizarProducto($idProducto, $nombreP, $descripcionP, $stock) {
        $resultado = $this->db->producto->updateOne(
            ['_id' => new ObjectId($idProducto)], // Filtro
            ['$set' => [                         // Actualización
                'nombreP' => $nombreP,
                'descripcionP' => $descripcionP,
                'stock' => (int)$stock
            ]]
        );
        return $resultado->getModifiedCount() > 0;
    }

    /* ===========================
        ELIMINAR PRODUCTO + MOVIMIENTOS
    ============================ */
    public function eliminarProducto($idProducto) {
        try {
            // Borrar movimientos asociados
            $this->db->movimientos->deleteMany(['idProducto' => new ObjectId($idProducto)]);

            // Borrar producto
            $this->db->producto->deleteOne(['_id' => new ObjectId($idProducto)]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /* ===========================
        OBTENER STOCK
    ============================ */
    public function obtenerStock($idProducto) {
        $producto = $this->db->producto->findOne(['_id' => new ObjectId($idProducto)]);
        return $producto ? $producto['stock'] : 0;
    }

    /* ===========================
        REGISTRAR SALIDA
    ============================ */
    public function registrarSalida($tipo, $idUsuario, $idProducto, $cantidad) {
        try {
            // Registrar movimiento
            $this->db->movimientos->insertOne([
                'tipo' => $tipo,
                'idUsuario' => $idUsuario,
                'idProducto' => new ObjectId($idProducto),
                'cantidad' => (int)$cantidad,
                'fecha' => new MongoDB\BSON\UTCDateTime()
            ]);

            // Actualizar stock (usamos $inc con valor negativo para restar)
            $this->db->producto->updateOne(
                ['_id' => new ObjectId($idProducto)],
                ['$inc' => ['stock' => -(int)$cantidad]]
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>