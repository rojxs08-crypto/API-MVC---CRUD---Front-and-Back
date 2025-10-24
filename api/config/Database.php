<?php

//CONEXION ALA BASE DE DATOS MEDIANTE PROGRAMACION ORIENTADA A OBJETOS



//definimos en namesapce del archivo para poder llamarlo de este nombre desde otros archivos
namespace Config;

require_once __DIR__ . '/../../vendor/autoload.php';

//importamos las librerias de PDO y PDOException para manejar la conexion y errores
use PDO;
use PDOException;
use Dotenv\Dotenv;



//creamos la clase database que creara el objeto con las caracteristicas para hacer una conexion a la base de datos
class Database {


    //declaramos las variables necesarias para la conexion
    private $host;
    private $db_name;
    private $username;
    private $password;




    public function __construct() {

        $dontenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dontenv->load();

        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
    }


    //declaramos la variable conn que almacenara la conexion es publica para poder acceder a ella desde otros archivos
    public $conn;


    public function connect() {
        //por que es null? para inicializar la variable conn sin valor
        $this->conn = null;

        //hacemos una excepcion para la conexion en caso de que funcione devolvera la variable conn 
        //con la conexion y en caso de que no devuelve un JSON con el error
        try {
            //le damos ala variable conn el valor de una nueva conexion PDO con los parametros necesarios
            $this->conn = new PDO ("mysql:host={$this->host};dbname={$this->db_name}", $this->username,$this->password);

        }catch(PDOException $e){
            echo json_encode(["error" => "Error de conexion" . $e->getMessage()]);
            exit;
        }
        //devolvemos la conexion y cuando se crea un objeto en base a esta clase se llama a este metodo para obtener la conexion
        return $this->conn;
    }

}

?>