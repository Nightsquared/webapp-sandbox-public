<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once("./classes/postgres_heroku_db.php");

$dbObj = new postgres_heroku_DBI();
$dbresult = $dbObj->resetDB();

if(isset($_GET['createusertable']) && $dbresult === true) {
    require_once("./classes/user.php");

    $userObj = new User();
    if($userObj->createTable() == true){
        http_response_code(201);
        echo json_encode(array("response" => 201,
        "message" => "table was created."));
    }
} else if(!isset($_GET['createusertable']) && $dbresult === true){
    http_response_code(201);
    echo json_encode(array("response" => 201,"message" => "schema was created."));
} else{
    http_response_code(503);
    echo json_encode(array("response" => 503,"message" => "Unable to create schema/table."));
}

?>