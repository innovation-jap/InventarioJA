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
    // CAMBIO: Añadimos $almacen como parámetro
    public function agregarProducto($idUsuario, $nombreP, $descripcionP, $stock, $almacen, $imagen = null) {
        try {
            $nuevoProducto = [
                'idUsuario' => new ObjectId($idUsuario), 
                'nombreP' => $nombreP,
                'descripcionP' => $descripcionP,
                'stock' => (int)$stock,
                'almacen' => $almacen,
                'imagen' => $imagen, // Se guarda la ruta o URL
                'fechaI' => new UTCDateTime()
            ];
            
            $resultado = $this->db->producto->insertOne($nuevoProducto);
            $idProducto = $resultado->getInsertedId();

            $this->db->movimientos->insertOne([
                'tipo' => 'entrada',
                'idUsuario' => new ObjectId($idUsuario),
                'idProducto' => $idProducto,
                'cantidad' => (int)$stock,
                'comentario' => "Ingreso inicial en $almacen",
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
    // CAMBIO: Añadimos $almacen para que la edición rápida también lo actualice
    public function actualizarProducto($idProducto, $nombreP, $descripcionP, $stock, $almacen, $imagen = null) {
    try {
        $datosActualizar = [
            'nombreP' => $nombreP,
            'descripcionP' => $descripcionP,
            'stock' => (int)$stock,
            'almacen' => $almacen
        ];

        // Solo añadimos la imagen al set si no es null
        if ($imagen !== null) {
            $datosActualizar['imagen'] = $imagen;
        }

        $resultado = $this->db->producto->updateOne(
            ['_id' => new ObjectId($idProducto)],
            ['$set' => $datosActualizar]
        );
        
        return $resultado->getModifiedCount() > 0;
    } catch (Exception $e) {
        return false;
    }
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

    /* ============================================================
        CONTAR TOTAL PARA PAGINACIÓN
    ============================================================ */
    public function contarProductos() {
        // Cuenta todos los documentos en la colección producto
        return $this->db->producto->countDocuments();
    }

    /* ============================================================
        OBTENER PRODUCTOS CON PAGINACIÓN Y ORDEN DESCENDENTE
    ============================================================ */
    public function obtenerProductosPaginados($skip, $limit) {
        try {
            // Buscamos todos los productos
            // sort(['_id' => -1]) hace que los más nuevos aparezcan primero
            // skip() se salta los registros de páginas anteriores
            // limit() trae solo la cantidad permitida por página
            $opciones = [
                'sort'  => ['_id' => -1], 
                'skip'  => (int)$skip,
                'limit' => (int)$limit
            ];

            $cursor = $this->db->producto->find([], $opciones);
            return iterator_to_array($cursor);
        } catch (Exception $e) {
            return [];
        }
    }
}