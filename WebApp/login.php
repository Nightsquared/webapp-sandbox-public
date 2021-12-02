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
<?php
if(!empty($_SESSION['login'])){
    ?>
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'edit.php'?>'" value="Continue as Current User" />
    <br>
    <?php
}
?>
<body>
    <h2> Login to Edit User </h2>
    <form method="get" action = "login.php">
        <label for="name">Name</label>
        <br>
        <input type="text" name="name" id="name" required></input>
        <br>
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" id="password" required></input>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
<?php
if(!empty($_GET['name']) && !empty($_GET['password'])){
    $APIObj = new API_Call();
    $results = $APIObj->api_get('verify.php', $_GET);
    $results = json_decode($results);
    $responsecode = $results->response;
    if($_SESSION['verbose'] == true){
        print('<br>');
        echo(strval($responsecode)."<br>");
        echo($results->message."<br>");
        if(isset($results->sqlstring)){
            echo($results->sqlstring);
        }
    }
    if($responsecode != 200){
        echo('<br> Login invalid.');
    } else{
        $_SESSION['login'] = $results->content[0]->entry_id;
        header('Location: edit.php');
    }
}
?>
</html>