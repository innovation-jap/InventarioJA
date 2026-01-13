<?php
require_once __DIR__ . "/conexion.php";
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class modeloProducto {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    /* ===========================
        OBTENER USUARIOS
    ============================ */
    public function obtenerUsuarios() {
        $usuarios = $this->db->usuario->find([], [
            'sort' => ['nombreU' => 1]
        ]);
        return iterator_to_array($usuarios);
    }

    /* ===========================
        OBTENER PRODUCTOS
    ============================ */
    public function obtenerProductos() {
        $productos = $this->db->producto->find();
        return iterator_to_array($productos);
    }

    /* ===========================
        AGREGAR PRODUCTO + MOVIMIENTO
    ============================ */
    public function agregarProducto($idUsuario, $nombreP, $descripcionP, $stock) {
        try {
            $nuevoProducto = [
                // CORRECCIÓN: Convertir a ObjectId para relaciones en Atlas
                'idUsuario' => new ObjectId($idUsuario), 
                'nombreP' => $nombreP,
                'descripcionP' => $descripcionP,
                'stock' => (int)$stock,
                'fechaI' => new UTCDateTime()
            ];
            
            $resultado = $this->db->producto->insertOne($nuevoProducto);
            $idProducto = $resultado->getInsertedId();

            // Registrar movimiento inicial
            $this->db->movimientos->insertOne([
                'tipo' => 'entrada',
                // CORRECCIÓN: Convertir a ObjectId
                'idUsuario' => new ObjectId($idUsuario),
                'idProducto' => $idProducto,
                'cantidad' => (int)$stock,
                'fecha' => new UTCDateTime()
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
            ['_id' => new ObjectId($idProducto)],
            ['$set' => [
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
            $this->db->movimientos->deleteMany(['idProducto' => new ObjectId($idProducto)]);
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
            $this->db->movimientos->insertOne([
                'tipo' => $tipo,
                // CORRECCIÓN: Convertir a ObjectId
                'idUsuario' => new ObjectId($idUsuario),
                'idProducto' => new ObjectId($idProducto),
                'cantidad' => (int)$cantidad,
                'fecha' => new UTCDateTime()
            ]);

            $this->db->producto->updateOne(
                ['_id' => new ObjectId($idProducto)],
                ['$inc' => ['stock' => -(int)$cantidad]]
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /* ===========================
        REGISTRAR DEVOLUCIÓN
    ============================ */
    public function registrarDevolucion($idUsuario, $idProducto, $cantidad) {
        try {
            $this->db->movimientos->insertOne([
                'tipo' => 'devolucion',
                // CORRECCIÓN: Convertir a ObjectId
                'idUsuario' => new ObjectId($idUsuario),
                'idProducto' => new ObjectId($idProducto),
                'cantidad' => (int)$cantidad,
                'fecha' => new UTCDateTime()
            ]);

            $this->db->producto->updateOne(
                ['_id' => new ObjectId($idProducto)],
                ['$inc' => ['stock' => (int)$cantidad]]
            );

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}