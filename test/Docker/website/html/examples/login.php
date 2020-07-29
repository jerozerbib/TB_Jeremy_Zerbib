<?php
require('utils.php');
session_start();

$pseudo = $_POST['pseudo'];
$passwd = $_POST['passwd'];


// if (!empty($_POST['csrf_token'])) {
    // if (checkToken($_POST['csrf_token'], 'login')) {
      if(isset($pseudo) && isset($passwd)){
        if(connect($pseudo, $passwd)){
          set_cookie($pseudo, base64_encode(password_hash($passwd, PASSWORD_BCRYPT)));
          $_SESSION['connec'] = true;
          header('Location: dashboard.php');
          } else {
          	header('Location: index.php');
          }
      } else {
      	header('Location: index.php');
      }
  //   } else {
  //     header('Location: index.php');
  //   }
  // } else {
  // header('Location: index.php');
// }
?>
