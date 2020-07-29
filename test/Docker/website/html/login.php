<?php

require('utils.php');
session_start(); 

$pseudo = $_POST['pseudo'];
$passwd = $_POST['passwd'];


if(isset($pseudo) && isset($passwd)){
    if(connect($pseudo, $passwd) == 1){    
	echo "je passe ca";    
    set_cookie($pseudo, $passwd);
    } else {
    	header('Location: index.php');
    }
} else {
	header('Location: index.php');
}

?>
