<?php
// required headers
header("Access-Control-Allow-Origin: *");

require_once("./classes/user.php");

$userObj = new User();
if(!isset($_GET['id'])){
    http_response_code(400);
    echo json_encode(array(
        "response" => 400,
        "message" => "bad request."));
}else{
    $results = $userObj->getUserByID($_GET['id']);
    if($results === false){
        http_response_code(503);
        echo json_encode(array("response" => 503,
        "message" => "unable to perform search."));
    } else{
        http_response_code(200);
        echo json_encode(
            array(
                "response" => 200,
                "message" => "Search ok",
                "content" => $results[0],
            )
        );
    }
}
?>