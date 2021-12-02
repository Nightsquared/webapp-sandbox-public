<?php
// required headers
header("Access-Control-Allow-Origin: *");

require_once("./classes/user.php");

$userObj = new User();
if($userObj->setValues($_GET) === false){
    http_response_code(400);
    echo json_encode(array(
        "response" => 400,
        "message" => "bad request.")
    );
} else{
    $results = $userObj->editUser();
    if($results['result'] === true) {
        http_response_code(201);
        echo json_encode(array(
            "response" => 201,
            "message" => "user was updated.",
            "sqlstring"=>$results['sqlstring']
        )
    );
    }else{
        http_response_code(503);
        echo json_encode(array(
            "response" => 503,
            "message" => "unable to update user.")
        );
    };
}