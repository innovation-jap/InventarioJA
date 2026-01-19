<?php
require_once __DIR__ . "/conexion.php";
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class modeloMovimientos {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    public function obtenerMovimientos() {
        return $this->ejecutarAgregacion([]);
    }

    public function obtenerMovimientosFiltrados($fechaInicio = null, $fechaFin = null, $idProducto = null, $idUsuario = null) {
        $filters = [];

        if (!empty($fechaInicio) || !empty($fechaFin)) {
            $dateFilter = [];
            if (!empty($fechaInicio)) {
                $dateFilter['$gte'] = new UTCDateTime(strtotime($fechaInicio) * 1000);
            }
            if (!empty($fechaFin)) {
                $dateFilter['$lte'] = new UTCDateTime(strtotime($fechaFin . ' +1 day') * 1000);
            }
            $filters['fecha'] = $dateFilter;
        }

        if (!empty($idProducto)) {
            $filters['idProducto'] = new ObjectId($idProducto);
        }

        if (!empty($idUsuario)) {
            $filters['idUsuario'] = new ObjectId($idUsuario);
        }

        return $this->ejecutarAgregacion($filters);
    }

    private function ejecutarAgregacion($filters) {
        $pipeline = [];

        if (!empty($filters)) {
            $pipeline[] = ['$match' => $filters];
        }

        $pipeline[] = [
            '$lookup' => [
                'from' => 'usuario',
                'localField' => 'idUsuario',
                'foreignField' => '_id',
                'as' => 'usuario_info'
            ]
        ];

        $pipeline[] = [
            '$lookup' => [
                'from' => 'producto',
                'localField' => 'idProducto',
                'foreignField' => '_id',
                'as' => 'producto_info'
            ]
        ];

        $pipeline[] = ['$unwind' => ['path' => '$usuario_info', 'preserveNullAndEmptyArrays' => true]];
        $pipeline[] = ['$unwind' => ['path' => '$producto_info', 'preserveNullAndEmptyArrays' => true]];

        // 5. Proyectar campos finales (SELECT)
        $pipeline[] = [
            '$project' => [
                'idMovimiento' => '$_id',
                'tipo'         => '$tipo',
                'cantidad'     => '$cantidad',
                'fechaM'       => '$fecha',
                'nombreU'      => '$usuario_info.nombreU',
                'nombreP'      => '$producto_info.nombreP',
                // LÍNEA AGREGADA: Extrae la ubicación desde la información del producto
                'almacen'      => '$producto_info.almacen', 
                'idProducto'   => '$idProducto'
            ]
        ];

        $pipeline[] = ['$sort' => ['fechaM' => -1]];

        $cursor = $this->db->movimientos->aggregate($pipeline);
        return iterator_to_array($cursor);
    }
}
?>