<?php
require_once "conexion.php";
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class modeloMovimientos {

    private $db;

    public function __construct() {
        $conexion = new Database();
        $this->db = $conexion->conectar();
    }

    /* ===========================
        OBTENER TODOS LOS MOVIMIENTOS
    ============================ */
    public function obtenerMovimientos() {
        return $this->ejecutarAgregacion([]);
    }

    /* ===========================
        OBTENER MOVIMIENTOS POR USUARIO
    ============================ */
    public function obtenerMovimientosPorUsuario($idUsuario) {
        $filtro = ['$match' => ['idUsuario' => new ObjectId($idUsuario)]];
        return $this->ejecutarAgregacion([$filtro]);
    }

    /* ===========================
        MOVIMIENTOS FILTRADOS
    ============================ */
    public function obtenerMovimientosFiltrados($fechaInicio = null, $fechaFin = null, $idProducto = null, $idUsuario = null) {
        $match = [];

        // Filtro de fechas
        if (!empty($fechaInicio) || !empty($fechaFin)) {
            $rangoFechas = [];
            if (!empty($fechaInicio)) {
                $rangoFechas['$gte'] = new UTCDateTime(strtotime($fechaInicio) * 1000);
            }
            if (!empty($fechaFin)) {
                $rangoFechas['$lte'] = new UTCDateTime(strtotime($fechaFin . " 23:59:59") * 1000);
            }
            $match['fechaM'] = $rangoFechas;
        }

        // Filtro de Producto
        if (!empty($idProducto)) {
            $match['idProducto'] = new ObjectId($idProducto);
        }

        // Filtro de Usuario
        if (!empty($idUsuario)) {
            $match['idUsuario'] = new ObjectId($idUsuario);
        }

        $pipeline = !empty($match) ? [['$match' => $match]] : [];
        return $this->ejecutarAgregacion($pipeline);
    }

    /**
     * Función privada para evitar repetir el código de "JOIN" ($lookup)
     */
    private function ejecutarAgregacion($pasosPrevios = []) {
        $pipeline = array_merge($pasosPrevios, [
            // Join con Usuario (u.idUsuario = m.idUsuario)
            [
                '$lookup' => [
                    'from' => 'usuario',
                    'localField' => 'idUsuario',
                    'foreignField' => '_id',
                    'as' => 'usuario_info'
                ]
            ],
            // Join con Producto (p.idProducto = m.idProducto)
            [
                '$lookup' => [
                    'from' => 'producto',
                    'localField' => 'idProducto',
                    'foreignField' => '_id',
                    'as' => 'producto_info'
                ]
            ],
            // Descomponer los arrays de los joins para que sean objetos planos
            ['$unwind' => '$usuario_info'],
            ['$unwind' => '$producto_info'],
            // Ordenar por fecha descendente
            ['$sort' => ['fechaM' => -1]],
            // Formatear la salida para que sea igual a la que tenías en SQL
            [
                '$project' => [
                    'idMovimiento' => '$_id',
                    'tipo' => 1,
                    'cantidad' => 1,
                    'fechaM' => 1,
                    'idUsuario' => '$idUsuario',
                    'nombreU' => '$usuario_info.nombreU',
                    'nombreP' => '$producto_info.nombreP'
                ]
            ]
        ]);

        $cursor = $this->db->movimientos->aggregate($pipeline);
        return iterator_to_array($cursor);
    }
}
?>