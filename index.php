<?php

/**
 * Librerias necesarias para el funcionamiento modular de la API
 * autoload.php        -> Contiene el funcionamiento del framework 'Slim' para las peticiones http
 * ResponseBuilder.php -> Constructor para generar las respuestas JSON de la API
 * SQLProcedures.php   -> Contiene los constructores para las rutinas de la base de datos
 * Validator.php       -> Contiene funciones para la validacion de los datos enviados
 */
require_once './vendor/autoload.php';
require_once './ResponseBuilder.php';
require_once './SQLProcedures.php';
require_once './Validator.php';

// App -> Objeto de la libreria Slim
$app = new \Slim\Slim();
// Conexion a la base de datos
$dataBase = new mysqli('162.248.52.104', 'root', 'gombar', 'dbtest');
// Instanciacion de las rutinas de la base de datos
$SQLQuerys = new SQLProcedures();

/**
 * Headers CROPS para la comunicacion de la API
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

/**
 *              Endpoints para la comunicacion y envio de datos
*/

/**
 * @Endpoint /Login/
 * @Purpose Generar una validacion del nombre de usuario y contraseña mandadas al servidor
 * 
 * @Params Username -> String contiene el nombre del usuario
 * @Params Password -> String contiene el password del usuario
 */
$app->post('/Login/', function() use($app, $dataBase, $SQLQuerys){
    $response = new ResponseJson();
    $validation = new UserValidation();
    $json = $app->request->getBody();
    $data = json_decode($json, true);
    if($validation->ValidLogin($data)){
        $Query = $dataBase->query($SQLQuerys->Login($data['Username'], $data['Password']));
        if(!$Query == null){
            if ($Query->num_rows == 1) {
                $user = $Query->fetch_assoc();
                $response->SetRequestStatus('ok', 'User and password OK');
                $response->SetRequestData('User', $user);
            }
            else{
                $response->SetRequestStatus('error', 'Not founded');
            }
        }   
        else{
            $response->SetRequestStatus('error', 'Error in database query');
        }
    }
    else{
        $response->SetRequestStatus('error', 'Arguments not valid');
    }
    echo $response->BuildJson();
});

$app->post('/Register/', function() use($app, $dataBase, $SQLQuerys){
    $response = new ResponseJson();
    $validation = new UserValidation();
    $json = $app->request->getBody();
    $data = json_decode($json, true);
    if($validation->ValidRegister($data)){
        $Query = $dataBase->query($SQLQuerys->Register($data['Username'], $data['Password'], $data['Name'], $data['Email']));
        if(!$Query == null){
            $response->SetRequestStatus('ok', 'Registrated success');
            $response->SetRequestData('Data', $Query);
        }   
        else{
            $response->SetRequestStatus('error', 'Error in database query');
        }
    }
    else{
        $response->SetRequestStatus('error', 'Arguments not valid');
    }
    echo $response->BuildJson();
});

$app->get('/ValidateUsername/:username', function($username) use($app, $dataBase, $SQLQuerys){
    $response = new ResponseJson();
    $query = $dataBase->query($SQLQuerys->ValidateUsername($username))->fetch_assoc() != null ? false : true;
    $response->SetRequestData('Data', $query);
    if(!$query)
        $response->SetRequestStatus('ok', 'Username founded');
    else
        $response->SetRequestStatus('ok', 'Username not founded');
    echo $response->BuildJson();
});

$app->group('/Productos/', function() use($app, $dataBase, $SQLQuerys){
    $app->get('GetAll/:Empresa', function($Empresa) use($app, $dataBase, $SQLQuerys){
        $response = new ResponseJson();
        $response->SetRequestStatus('error', 'Error reading from DB');
        $query = $dataBase->query($SQLQuerys->GetProductos($Empresa));
        if($query){
            $productos = array();
            while($producto = $query->fetch_assoc())
                $productos[] = $producto;
            $response->SetRequestData('Products', $productos);
            $response->SetRequestStatus('ok', 'GetAll products from company: '.$Empresa);
        }
        echo $response->BuildJson();
    });
    $app->get('Search/:ID', function($ID) use($app, $dataBase, $SQLQuerys){
        $response = new ResponseJson();
        $query = $SQLQuerys->SeachPost($ID);
        if($query){
            
        }
    });
    $app->post('Add', function() use($app, $dataBase, $SQLQuerys){

    });
});

$app->run();

?>