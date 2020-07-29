<?php
function connect_database(){
	$con = pg_connect("host = localhost dbname = users 
	user = jeremy password = <Password>");
	if(!$con) {
		echo "Accès refusé !\n";
	} else {
		return $con;
	}	   
}
?>
