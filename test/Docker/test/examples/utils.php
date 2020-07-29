<?php


function connect_database(){
    $con = pg_connect("host = localhost dbname = users user = jeremy password = Polopolo123+");
    if(!$con) {
        echo "Accès refusé !\n";
    } else {
        return $con;
    }
}


/*
* Verify if the couple pseudo password exist.
*/
function connect($pseudo, $passwd){
    $db = connect_database();
        
    $ps1 = pg_prepare($db, "ps1", "SELECT * FROM u_user WHERE username = \$1 AND password = \$2");
    $res = pg_execute($db, "ps1", array($pseudo, $passwd));

    if (!$res) {
        echo "erreur";
    }   
    
    $row = pg_num_rows($res);
     
    return $row;
}

/**
 * Verify if someone is connected or not to access pages
 */
function verify(){

    if (!isset($_SESSION['connec']) && $_SESSION['conec'] != true){
		
	    header('Location: index.php');
    }
}

function set_cookie($pseudo, $passwd){
    $cookie_name = "conn";
    $cookie_value = $pseudo.":".$passwd;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    
}

?>
