<?php

//este archivo lo que hace es manejar las peticiones que llegan a la API
//¿pero como esto no lo hacia Products de routes? Si, pero aqui es donde se hace
//la configuracion inicial, se cargan las dependencias y se maneja la ruta
//por si no lo entendiste bien, Products.php solo maneja las rutas relacionadas con productos
//entonces aqui en index.php es donde se hace toda la configuracion inicial
//como por ejemplo cargar las dependencias con composer, manejar las cabeceras
//en products podemos poner las rutas para nose borrar productos y demas
//pero aqui es donde sabe a que controlador y metodo llamar en base a la ruta para que
//luego el controlador maneje la logica y llame al modelo


//habilitar la visualizacion de errores para depuracion
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurar cabeceras para permitir CORS y definir el tipo de contenido como JSON
// que es esto? CORS son las siglas de Cross-Origin Resource Sharing, que en español
// significa Compartición de Recursos de Origen Cruzado. Es un mecanismo
//que utiliza cabeceras HTTP adicionales para permitir que un usuario obtenga permiso
//para acceder a recursos seleccionados desde un servidor, en un origen distinto (dominio)
//al que pertenece el recurso que se está solicitando.
// por si no lo entendiste es como una politica de seguridad que los navegadores aplican
//para evitar que paginas maliciosas hagan peticiones a servidores externos

//este define que el contenido que se va a enviar y recibir es JSON
header("Content-Type: application/json");
//estas cabeceras permiten que cualquier origen pueda hacer peticiones a esta API
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

//manejar peticiones OPTIONS que son preflight requests
//¿que son? son peticiones que hace el navegador antes de hacer la peticion real
//para verificar si el servidor permite la peticion real
//pero que tiene que ver con compara con OPTIONS? por que las peticiones OPTIONS son las que se usan
//para verificar que metodos y cabeceras estan permitidos en el servidor
//por ejemplo: si el cliente quiere hacer una peticion POST a /products
// primero hace una peticion OPTIONS a /products para ver si el servidor permite
// esa peticion POST, entonces si el servidor responde que si, el navegador hace la peticion real
// y como sabe que peticion permite? por que el servidor responde a la peticion OPTIONS
// diciendo que metodos y cabeceras estan permitidos, y eso es lo que hacemos aqui
// si la peticion es OPTIONS simplemente salimos del script sin hacer nada mas

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Responder a la petición OPTIONS y salir
    exit(0);
}

// Cargar el autoload de Composer para manejar las dependencias
//como por ejemplo si usamos algun paquete externo instalado con composer
require_once __DIR__ . "/../vendor/autoload.php";

//server lo que hace es obtener la ruta relativa de la peticion 
// que es una ruta relativa? es la parte de la URL que viene despues del dominio
// por ejemplo si la URL es http://localhost/CRUD/api/products
// la ruta relativa seria /CRUD/api/products
$request_uri = $_SERVER['REQUEST_URI'];
//como todas las peticiones que hacemos ala API estan dentro de /CRUD/api
//entonces quitamos esa parte para quedarnos solo con la ruta relativa a la API
//como por si no lo entendiste bien, si la peticion es /CRUD/api/products
// al quitarle /CRUD/api lo que queda es /products
$base_path = '/CRUD/api';

//esto lo que hace es quitar la parte /CRUD/api de la ruta
//le dice reemplaza $base_path en $request_uri por una cadena vacia ''
$route = str_replace($base_path, '', $request_uri);



// Imprimir información de depuración
error_log("Request URI: " . $request_uri);
error_log("Route: " . $route);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);

try {
    // Incluir las rutas de productos para manejar las peticiones relacionadas con productos
    //es decir aca llamamos a Products.php que es donde definimos las rutas
    //por que la variable $route esta definida aqui? por que la usamos en Products.php
    //para saber a que ruta se esta haciendo la peticion y poder mandarlo a el controlador correcto
    require_once __DIR__ . "/routes/Products.php";
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
    exit();
}

// como capturamos que no se encontro la ruta ? si al final no esperamos nada de
// Products.php entonces si llegamos aqui es por que no se encontro la ruta
// Si llegamos aquí, significa que ninguna ruta coincidió y ni siquiera devolvio el exit()
//entonces suponemos que como Products no hizo nada para esa ruta, es por que no se encontro la ruta
//entonces devolvemos un error 404 diciendo que no se encontro la ruta
echo json_encode(["error" => "Ruta no encontrada", "path" => $route]);
?>