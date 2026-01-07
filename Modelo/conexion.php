<?php
// Importante: Cargar el autoload de Composer para que PHP encuentre la librería de MongoDB
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    private $uri;
    private $dbname;
    public $conn;

    public function __construct() {
        // En Render configurarás la variable MONGO_URI con tu cadena de Atlas
        // En DB_NAME pondrás el nombre de tu base de datos (ej. "inventario")
        $this->uri    = getenv("MONGO_URI") ?: "mongodb://localhost:27017";
        $this->dbname = getenv("DB_NAME") ?: "inventario";
    }

    public function conectar() {
        try {
            // Creamos el cliente de MongoDB (Equivalente al new PDO)
            $client = new \MongoDB\Client($this->uri);
            
            // Seleccionamos la base de datos
            $this->conn = $client->selectDatabase($this->dbname);
            
            return $this->conn;
        } catch (Exception $e) {
            // Detiene la ejecución si falla la conexión a Atlas
            die("Error de conexión a MongoDB Atlas: " . $e->getMessage());
        }
    }
}