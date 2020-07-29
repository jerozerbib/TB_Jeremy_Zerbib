<?php
function connect($pseudo, $passwd){

    $db_connection = pg_connect("host=localhost dbname=DBNAME user=postgres");
    $result = pg_query($db_connection, "SELECT * FROM users");
    $row = $rows->fetchArray();
    $db->close();
    return  $numRows;
}

?>