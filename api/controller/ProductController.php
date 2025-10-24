<?php

//lo que hacemos desde controller es recibir los datos de la peticion y validarlos
//para luego llamar al modelo y pedirle que haga las operaciones necesarias en la base de datos


//definimos en namesapce del archivo para poder llamarlo de este nombre desde otros archivos
namespace Controllers;

//usamos model y su clase product para poder crear productos 
use Models\Product;

//esta clase manejara las operaciones relacionadas con los productos y ademas
//actuara como intermediario entre las rutas y el modelo, 
class ProductController {
    
    //metodo que maneja la logica para almacenar un nuevo producto
    //y ademas valida los datos recibidos antes de pasarlos al modelo
    //se llama store por que es comun en las arquitecturas MVC llamar store al metodo que guarda
    // nuevos recursos
    public function store(){
        try {
            
            //esto es la forma de leer los datos enviados en el cuerpo de la peticion HTTP
            $rawData = file_get_contents("php://input");
            //esto lo que hace es registrar en el log del servidor los datos recibidos para depuracion
            //para que sirve? para ver que datos se estan recibiendo realmente
            error_log("Datos recibidos: " . $rawData);

            //esto lo que hace es intentar pasar a json los datos recibidos
            //o mejor dicho decodificarlos, ¿que es decodificarlos? convertirlos de un formato a otro
             // Intentar decodificar JSON
            $data = json_decode($rawData, true);
            
            //verificamos si hubo un error al decodificar el JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error al decodificar JSON: " . json_last_error_msg());
            }

            //validar si en el JSON recibidos existen los datos que necesitamos para poder
            //hacer la operacion de insertar
            if (!isset($data["nombre"]) || !isset($data["precio"])) {
                echo json_encode(["error" => "Faltan campos obligatorios"]);
                return;
            }

            //creamos un objeto product para poder llmar al metodo que crea y guarda el producto
            $product = new Product();
            //lo que hace es llamar al metodo create del modelo product pasandole los parametros
            //que espera el metodo create para poder guardar el producto en la base de datos
            $success = $product->create($data["nombre"], $data["precio"]);

            //lo que hace esto es valida si la operacion de guardado fue exitosa o no
            //y esto lo sabe por que el metodo create del modelo devuelve true o false
            //entonces si es true entra y devuelve un mensaje de exito
            //y en caso de que no devuelve un mensaje de error

            //ahora los datos que estamos imprimiendo en JSON que estan aca dentro 
            //¿quien las recibe? el route por donde pasa la peticion? No, las recibe el cliente 
            // que hizo la peticion y como sabe esto el programa? por que cuando se hace una peticion HTTP
            //el cliente espera una respuesta y esa respuesta es lo que estamos enviando en JSON
            //pero seguro te preguntas ¿si paso por el route y este llama a controller entonces debria ir a 
            //el route la respuesta? No, el route solo llama al metodo del controller y este metodo es el que
            //devuelve la respuesta al cliente directamente.
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Producto guardado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudo guardar el producto"]);
            }

        //en caso de que algo haya salido mal dentro de este proceso el catch lo captura
        //y devuelve un mensaje de error generico
        } catch (\Exception $e) {
            //este imprime en el log del servidor el error ocurrido
            error_log("Error en store: " . $e->getMessage());
            //este devuelve el JSON con error 
            echo json_encode(["error" => $e->getMessage()]);
        }
    }






    public function viewProducts() {
        try{     
            $product = new Product();
            $success = $product->getter(); 
            echo json_encode(["status" => "success" , "data" => $success]);
        //     if ($success) {
        //          echo json_encode(["status" => "succes" , "data" => $success]);
        //     }else{
        //         echo json_encode (["status" => "error" , "message" => "Error al obtener los productos"]);
        //     }
        } catch(\Exception $e) {
            echo json_encode(["error en viewProducts" => $e->getMessage()]);
        }
    }
}


?>