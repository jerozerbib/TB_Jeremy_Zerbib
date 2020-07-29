<?php
require('utils.php');
session_start();
session_unset();
session_destroy();
kill_vncserver();
kill_docker();
rm_docker();
delete_cookie('conn');
header('Location: index.php');

?>
