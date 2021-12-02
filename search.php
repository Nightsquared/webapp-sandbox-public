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
    <h1> Search Users </h1>
    <p> If verbose mode is enabled, this page will show the sql search string. </p>
    <form method="get" action='search.php'>
        <input type="text" name="name" id="name"></input>
        <button type="submit">Search users</button>
    </form>
</body>
<?php
if(isset($_GET['name'])){
    $APIObj = new API_Call();
    //api call to reset database
    $result = $APIObj->api_get('usersearch.php', array('name' => $_GET['name']));
    if($result == false){
        echo 'There was an error calling the api.';
    } else{
        $result =json_decode($result);
        //print response if verbose mode is on
        $responsecode = $result->response;
        if($_SESSION['verbose'] == true){
            echo(strval($responsecode)."<br>");
            echo($result->message."<br>");
            if($responsecode == 200){
                echo($result->sqlstring."<br>");
            }
        }
        if($responsecode != 200){
            echo 'There was an error.';
        } else{
            $searchresults = $result->content;
            echo "<h2>Search Results for ".$_GET["name"].":</h2>";
            foreach($searchresults as $user){
                echo("<h2><a href='".ALT_ROOT.'profile.php?id='.strval($user->entry_id)."'>".$user->name."</a></h2>");
            }
        }
    }
}
?>
</html>