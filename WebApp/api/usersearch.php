<?php
// required headers
header("Access-Control-Allow-Origin: *");

require_once("./classes/user.php");

$userObj = new User();
if(!isset($_GET['name'])){
    http_response_code(400);
    echo json_encode(array("response" => 400, "message" => "bad request."));
}else{
    $results = $userObj->userSearch($_GET['name']);
    if($results['result'] === false){
        http_response_code(503);
        echo json_encode(array("response" => 503,"message" => "unable to perform search."));
    } else{
        http_response_code(200);
        echo json_encode(
            array(
                "response" => 200,
                "message" => "Search ok",
                "content" => $results['result'],
                "sqlstring" => $results['sqlstring']
            )
        );
    }
}
?>