<?php

function connect_database(){
    $con = pg_connect("host = localhost dbname = users user = jeremy password = Polopolo123+");
    if(!$con) {
        echo "Accès refusé !\n";
    } else {
        return $con;
    }
}

function get_password_from_db($pseudo){
    $db = connect_database();

    $ps1 = pg_prepare($db, "ps1", "SELECT password FROM u_user WHERE username = \$1");
    $res = pg_execute($db, "ps1", array($pseudo));

    if (!$res) {
        echo "erreur";
    }

    $pass = pg_fetch_array($res);
    pg_close($db);
    return $pass[0];
}
echo get_password_from_db('jeremy.zerbib');
?>
