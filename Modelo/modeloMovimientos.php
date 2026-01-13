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

    /**
     * Obtiene todos los movimientos cruzando datos con usuarios y productos.
     */
    public function obtenerMovimientos() {
        return $this->ejecutarAgregacion([]);
    }

    /**
     * Obtiene movimientos filtrados por fecha, producto y usuario.
     */
    public function obtenerMovimientosFiltrados($fechaInicio = null, $fechaFin = null, $idProducto = null, $idUsuario = null) {
        $filters = [];

        // Filtro por rango de fechas
        if (!empty($fechaInicio) || !empty($fechaFin)) {
            $dateFilter = [];
            if (!empty($fechaInicio)) {
                $dateFilter['$gte'] = new UTCDateTime(strtotime($fechaInicio) * 1000);
            }
            if (!empty($fechaFin)) {
                // Se suma 1 día al final para incluir todo el día seleccionado
                $dateFilter['$lte'] = new UTCDateTime(strtotime($fechaFin . ' +1 day') * 1000);
            }
            $filters['fecha'] = $dateFilter;
        }

        // Filtro por Producto (debe ser ObjectId)
        if (!empty($idProducto)) {
            $filters['idProducto'] = new ObjectId($idProducto);
        }

        // Filtro por Usuario (debe ser ObjectId)
        if (!empty($idUsuario)) {
            $filters['idUsuario'] = new ObjectId($idUsuario);
        }

        return $this->ejecutarAgregacion($filters);
    }

    /**
     * Lógica central de Agregación (Equivalente a JOIN en SQL)
     */
    private function ejecutarAgregacion($filters) {
        $pipeline = [];

        // 1. Aplicar filtros iniciales (WHERE)
        if (!empty($filters)) {
            $pipeline[] = ['$match' => $filters];
        }

        // 2. Unir con la colección de usuarios (JOIN usuario)
        $pipeline[] = [
            '$lookup' => [
                'from' => 'usuario',
                'localField' => 'idUsuario',
                'foreignField' => '_id',
                'as' => 'usuario_info'
            ]
        ];

        // 3. Unir con la colección de productos (JOIN producto)
        $pipeline[] = [
            '$lookup' => [
                'from' => 'producto',
                'localField' => 'idProducto',
                'foreignField' => '_id',
                'as' => 'producto_info'
            ]
        ];

        // 4. Descomponer arreglos de unión (Unwind)
        // preserveNullAndEmptyArrays permite ver el movimiento aunque el producto/usuario haya sido borrado
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
                'idProducto'   => '$idProducto'
            ]
        ];

        // 6. Ordenar por fecha descendente (ORDER BY)
        $pipeline[] = ['$sort' => ['fechaM' => -1]];

        $cursor = $this->db->movimientos->aggregate($pipeline);
        return iterator_to_array($cursor);
    }
}
?>