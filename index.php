<?php
require 'vendor/autoload.php';
require 'vendor/slim/slim/Slim/Slim.php';
require 'db_config.php';
require 'db_university_2710.php';


\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header('Access-Control-Allow-Headers', 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type');
$res->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

$app->options('/(:name+)', function() use ($app) {
    $app->response()->header('Access-Control-Allow-Origin', '*'); //Allow JSON data to be consumed
    $app->response()->header('Access-Control-Allow-Headers', 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type');
    $app->response()->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// add new Route 
$app->get("/", function () use($app) {
    echo "<h1>Hello Slim World</h1>";
});
 
$app->get("/university/departments/salaries", function () use($app, $db_host, $db_name, $db_user, $db_password) {
    $departments = getDepartmentSalaries($db_host, $db_name, $db_user, $db_password);    
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($departments);
});

$app->get("/university/instructors", function () use($app, $db_host, $db_name, $db_user, $db_password) {
    $orderBy = $app->request()->params('orderBy');
    $order = $app->request()->params('order');
    $limit = $app->request()->params('limit');
    $offset = $app->request()->params('offset');
    
    $instructors = getInstructors($db_host, $db_name, $db_user, $db_password,
            $orderBy, $order, $limit, $offset);    
    $app->response()->header("Content-Type", "application/json");
    echo json_encode($instructors);
});

$app->put("/university/instructors/:id", function ($id) use($app, $db_host, $db_name, $db_user, $db_password) {    
    $request = $app->request();
    $body = $request->getBody();
    $instructor = json_decode($body);
     
    $success = addInstructor($db_host, $db_name, $db_user, $db_password,
            $id, $instructor->name, $instructor->dept_name, $instructor->salary);    
    
    $app->response()->header("Content-Type", "application/json");
    
    if ( $success == true )
        echo json_encode($id);
    else {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', "Insert Failed");
    }
});

$app->delete("/university/instructors/:id", function ($id) use($app, $db_host, $db_name, $db_user, $db_password) {    
     
    $success = deleteInstructor($db_host, $db_name, $db_user, $db_password, $id);    
    
    $app->response()->header("Content-Type", "application/json");
    
    if ( $success == true )
        echo json_encode($id);
    else {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', "Insert Failed");
    }
});

// run the Slim app
$app->run();
