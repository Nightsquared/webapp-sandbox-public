<?php
$title = basename($_SERVER['PHP_SELF']);
if($title == 'index.php'){
    $title = end(explode('/',getcwd()));
}
$title = explode('.', $title)[0];
$title = ucfirst(str_replace('_', ' ', $title));

?>
<title><?php echo $title ?></title>
<link rel="icon" href=<?php echo ALT_ROOT."images/logos/".LOGO ?>>
