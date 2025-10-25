<?php

//definimos en namesapce del archivo para poder llamarlo de este nombre desde otros archivos
namespace Models;

//importamos las librerias de PDO y la clase database para poder crear una conexion en base a esta clase
use PDO;
use Config\Database;

//creamos la clase product que manejara todo lo relacionado con los productos en la base de datos
class Product {
    //creamos una variable conn privada ¿por que privada? por que solo se usara dentro de esta clase para manejar la conexion
    private $conn;

    //creamos el constructor de la clase que se ejecutara al crear un objeto en base a esta clase
    public function __construct() {
        //creamos un objeto database para obtener la conexion a la base de datos que ahora contiene el calor de conn del otro archivo
        $db = new Database();
        //asignamos a la variable conn de esta clase la conexion obtenida del objeto database
        //que es connect()? un metodo dentro de php que devuelve la conexion a la base de datos
        $this->conn = $db->connect();
    }

    //creamos el metodo que inserta un nuevo preoducto en la base de datos con los parametros necesarios
    //y usa conn para hacer la conexion ala base de datos y ejecutar la consulta
    public function create($nombre, $precio){
        $sql = "INSERT INTO productos (nombre, precio) VALUES (:nombre, :precio)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":precio", $precio);
        //retorna true o false dependiendo de si la ejecucion fue exitosa o no
        return $stmt->execute(); 
    }


    public function getter() {
        $sql = "SELECT * FROM productos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }


    public function deleteProduct($id){
        $sql = "DELETE FROM productos where id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id" , $id);
        return $stmt->execute();
    }


    public function updateProduct($id , $nombre , $precio){
        $sql = "UPDATE productos SET nombre = :nombre , precio = :precio WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nombre" , $nombre);
        $stmt->bindParam(":precio" , $precio);
        $stmt->bindParam(":id" , $id);

        return $stmt->execute();
    }





}

?>