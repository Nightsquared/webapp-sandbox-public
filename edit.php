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
if(!empty($_GET['name']) && !empty($_GET['entry_id'])){
    $APIObj = new API_Call();
    $results = $APIObj->api_get('edituser.php', $_GET);
    //print response if verbose mode is on
    $results = json_decode($results);
    if($results->response == 201){
        echo "User updated";
    } else {
        echo "Could not update the user.";
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
//section for update form (i.e. what shows when a user first comes to the page)
if(isset($_SESSION['login'])){
    $APIObj = new API_Call();
    $result = $APIObj->api_get('getuserbyid.php', array('id' => $_SESSION['login']));
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
            #print edit form
            echo "<h2> Edit Profile for ".$userInfoObj->name."</h2>";
            ?>
            <p>Leave password field blank to not change it.</p>
            <form method="get" action = "edit.php">
                <input type="hidden" name="entry_id" id="entry_id" value="<?php echo $_SESSION['login'];?>"></input>
                <label for="name">Name</label>
                <br>
                <input type="text" name="name" id="name" value="<?php echo $userInfoObj->name; ?>" required></input>
                <br>
                <label for="password">Password</label>
                <br>
                <input type="password" name="password" id="password"></input>
                <br>
                <label for="name">Public Description</label>
                <br>
                <textarea id="publicdesc" name="publicdesc" rows="4" cols="50"><?php echo $userInfoObj->publicdesc; ?></textarea>
                <br>
                <label for="name">Private Description</label>
                <br>
                <textarea id="privatedesc" name="privatedesc" rows="4" cols="50"><?php echo $userInfoObj->privatedesc; ?></textarea>
                <br>
                <button type="submit">Submit</button>
            </form>
            <?php
        }
    }
} else {
    print('Not logged in as any user.');
}
?>
</html>