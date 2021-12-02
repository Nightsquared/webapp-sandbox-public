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
    <h2> New User Registration </h2>
    <form method="get" action = "register.php">
        <label for="name">Name</label>
        <br>
        <input type="text" name="name" id="name" required></input>
        <br>
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" id="password" required></input>
        <br>
        <label for="name">Public Description</label>
        <br>
        <textarea id="publicdesc" name="publicdesc" rows="4" cols="50"></textarea>
        <br>
        <label for="name">Private Description</label>
        <br>
        <textarea id="privatedesc" name="privatedesc" rows="4" cols="50"></textarea>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
<?php 
if(!empty($_GET['name']) && !empty($_GET['password'])){
    $APIObj = new API_Call();
    $results = $APIObj->api_get('adduser.php', $_GET);
    //print response if verbose mode is on
    $results = json_decode($results);
    if($results->response == 201){
        echo "User added";
    } else {
        echo "Could not add the user.";
    }
    if($_SESSION['verbose'] == true){
        print('<br>');
        echo(strval($results->response)."<br>");
        echo($results->message."<br>");
        if(isset($results->sqlstring)){
            echo($results->sqlstring);
        }
    }
}
?>
</html>