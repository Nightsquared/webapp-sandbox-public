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
    <h2> Welcome to David's Web App Attack Sandbox! </h2>
    <p> This web app has some simple features that can allow you to test some attacks on web applications. </p>
    <p> I primary focused on SQL Injection and Cross-Site Scripting attacks. </p>
    <p> It's recommended that you reset the database when you first start using this app so previous attacks are erased. </p>
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'index.php?reset=2'?>'" value="Reset database" />
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'index.php?reset=1'?>'" value="Reset database with entries" />
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'index.php?verbose=1'?>'" value="toggle verbose mode" />
    <br>
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'search.php'?>'" value="User search page" />
    <br>
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'register.php'?>'" value="Register a new user" />
    <br>
    <input type="button" onclick="location.href='<?php echo ALT_ROOT.'login.php'?>'" value="Edit profile of existing user" />
</body>
<?php
if(isset($_GET['verbose'])){
    $_SESSION['verbose'] = !$_SESSION['verbose'];
    if($_SESSION['verbose']){
        print('<br>verbose mode is on');
    }else{
        print('<br>verbose mode is off');
    }
}
if(isset($_GET['reset'])){
    unset($_SESSION['login']);
    $APIObj = new API_Call();
    //api call to reset database
    $result = $APIObj->api_get('resetdb.php', array('createusertable' => '1'));
    //print response if verbose mode is on
    if($_SESSION['verbose'] == true){
        print('<br>');
        echo(strval(json_decode($result)->response)."<br>");
        echo(json_decode($result)->message);
    }
    if($_GET['reset'] == 1){
        //if reset is one, add default users
        $dataarray = array(
            array(
                'name' => 'Anubis Hades',
                'password' => 'password1',
                'publicdesc' => "Hi! I am Anubis, CEO of After Solutions, Inc, a cloud-based soul transportation firm that serves millions of users every year.",
                'privatedesc' => 'Contact me at anubish@afterinc.com for revival consultation rates.'
            ),
            array(
                'name' => 'Ryley Robinson',
                'password' => 'leviathanssuck',
                'publicdesc' => "Recent escapee of 4546B, getting used to not being constantly assulted by sea monsters.",
                'privatedesc' => 'Alterra debt remaining: 967,257,000,000 credits'
            ),
            array(
                'name' => 'Elon Musk',
                'password' => 'mars>earth',
                'publicdesc' => "Now the richest person in the world. Suck it, Jeff.",
                'privatedesc' => 'Tesla model R will be announced in January 2022'
            ),
            array(
                'name' => 'Hamlet',
                'password' => 'tobeornottobe',
                'publicdesc' => "I have heard of your paintings too, well enough. God hath given you one face, and you make yourselves another.",
                'privatedesc' => 'Seems, madam! nay it is; I know not &rsquo;seems.&rsquo;
                &rsquo;Tis not alone my inky cloak, good mother,
                Nor customary suits of solemn black,
                Nor windy suspiration of forced breath,
                No, nor the fruitful river in the eye,
                Nor the dejected &rsquo;havior of the visage,
                Together with all forms, moods, shapes of grief,
                That can denote me truly: these indeed seem,
                For they are actions that a man might play:
                But I have that within which passeth show;
                These but the trappings and the suits of woe.'
            ),
        );
        //add each user (entries in data array)
        foreach ($dataarray as $userarray){
            $result = $APIObj->api_get('adduser.php', $userarray);
            //print response if verbose mode is on
            if($_SESSION['verbose'] == true){
                print('<br>');
                echo(strval(json_decode($result)->response)."<br>");
                echo(json_decode($result)->message);
            }
        }
    }
}
?>
</html>