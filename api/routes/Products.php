<?php

//definimos el namespace de la ruta para poder llamarla desde otros archivos
namespace Routes;

//importamos el controlador de productos para poder usarlo en esta ruta
use Controllers\ProductController;
// y la clase Exception para manejar errores desde el controlador es decir no desde PDO si no
//desde nuestro propio codigo ¿por que hacemos esto? por que en el controller usamos try catch 
// para manejar errores y lanzamos excepciones y esto nos sirve para capturarlas aqui
use \Exception;

//lo que hacemos aqui es definir las rutas relacionadas con los productos
// ¿para que es esto? para que cuando llegue una peticion a esta ruta podamos
// llamar al metodo correspondiente del controlador y este a su vez llame al modelo
//ahora en vez de crear un archivo .php aparte creamos una condicion aqui mismo
//para manejar la ruta /products con metodo POST entonces ya nos redirige a el metodo store
// para realizar la operacion, esto ya es mucho por que como que antes crabamos un archivo
//para cada cosa y ahora lo hacemos aca para que una funcion qeu sabemos donde esta, en base ala ruta
//de la peticion HTTP podamos llamar al metodo correspondiente del controlador y no un archivo aparte
// para cada ruta, esto hace que el codigo sea mas compacto y facil de manejar


//por que solo /products y no /api/products? por que en index.php ya quitamos la parte /api
// de la ruta entonces aqui solo nos queda /products para manejarla ruta
//por si no entendiste : en index.php tenemos esto:
// $base_path = '/CRUD/api';
// $route = str_replace($base_path, '', $request_uri);
//entonces si la peticion es /CRUD/api/products entonces al quitarle /CRUD/api
// lo que queda en $route es /products y es eso lo que estamos manejando aqui
//entonces si la ruta es /products y el metodo es POST llamamos al metodo store
// del controlador ProductController para que maneje la logica de crear un nuevo producto

if ($route === '/products' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    //aqui llamamos al metodo store del controlador ProductController
    //para que maneja la logica de crear un nuevo producto
    
    try {
        $controller = new ProductController();
        $controller->store();
    } catch (Exception $e) {
        error_log("Error en ProductController: " . $e->getMessage());
        echo json_encode(["error" => "Error interno del servidor"]);
    }
    exit();
}
elseif ($route === '/products' && $_SERVER['REQUEST_METHOD'] === 'GET'){

    try{
        $controller = new ProductController();
        $controller->viewProducts();
    } catch (Exception $e){
        error_log("Error en ProductController: " . $e->getMessage());
        echo json_encode(["error" => "Error interno del servidor"]);
    }
    exit();
}
elseif (preg_match('/^\/products\/(\d+)$/', $route, $matches) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Extraer el ID del producto de la URL
    $productId = $matches[1];

    try {
        $controller = new ProductController();
        $controller->delete($productId);
    } catch (Exception $e) {
        error_log("Error en ProductController: " . $e->getMessage());
        echo json_encode(["error" => "Error interno del servidor"]);
    }
    exit();
}






?>