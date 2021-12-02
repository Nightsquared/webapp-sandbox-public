<html>
<?php 
session_start();
if(!isset($_SESSION['verbose'])){
    $_SESSION['verbose'] = false;
}
require_once("./includes/constants.php");
require_once(PROJECT_ROOT . "css/styles.php"); 
include_once(PROJECT_ROOT . "includes/setup.php");
include_once(PROJECT_ROOT . "includes/api_call.php");
?>
<head>
</head>
<body>
<?php
if(isset($_GET['id'])){
    $APIObj = new API_Call();
    //api call to reset database
    $result = $APIObj->api_get('getuserbyid.php', array('id' => $_GET['id']));
    if($result === false){
        echo 'There was an error calling the api.';
    } else{
        $result=json_decode($result);
        //print response if verbose mode is on
        $responsecode = $result->response;
        if($_SESSION['verbose'] == true){
            echo(strval($responsecode)."<br>");
            echo($result->message."<br>");
        }
        if($responsecode != 200){
            echo 'There was an error.';
        } else{
            $userInfoObj = $result->content;
            echo "<h2>".$userInfoObj->name."</h2>";
            echo "<p>".$userInfoObj->publicdesc."</p>";
        }
    }
}
?>
</body>
</html>