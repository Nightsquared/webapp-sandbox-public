<?php
// required headers
header("Access-Control-Allow-Origin: *");

require_once("./classes/user.php");

$userObj = new User();
if(!isset($_GET['name']) || !isset($_GET['password'])){
    http_response_code(400);
    echo json_encode(array(
        "response" => 400,
        "message" => "bad request."));
}else{
    $results = $userObj->getUserByNameAndPassword($_GET['name'], $_GET['password']);
    if($results['result'] === false){
        http_response_code(503);
        echo json_encode(array(
            "response" => 503,
            "message" => "unable to perform search."));
    } else {
        if(count($results['result']) == 1){
            http_response_code(200);
            echo json_encode(
                array(
                    "response" => 200,
                    "message" => "Login OK",
                    "sqlstring" => $results['sqlstring'],
                    "content" => $results['result']
                )
            );
        } else{
            echo json_encode(
                array(
                    "response" => 401,
                    "message" => "Login invalid",
                    "sqlstring" => $results['sqlstring'],
                )
            );
        }
    }
}
?>