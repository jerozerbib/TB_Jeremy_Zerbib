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

    $ps1 = pg_prepare($db, "ps1", "SELECT * FROM u_user WHERE username = \$1");
    $res = pg_execute($db, "ps1", array($pseudo));

    if (!$res) {
        echo "erreur";
    }


    $row = pg_num_rows($res);
    if ($row == 1) {
      $arr = pg_fetch_array($res);
      if (password_verify($passwd, $arr['password'])) {
        pg_close($db);
        return true;
      }
    }
    pg_close($db);
    return false;
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


/**
 * Verify if someone is connected or not to access pages
 */
function verify(){
    if (!isset($_SESSION['connec']) && $_SESSION['connec'] != true){
	    header('Location: index.php');
    }
}

function set_cookie($pseudo, $passwd){
    $cookie_name = "conn";
    $cookie_value = $pseudo.":".$passwd;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

}

function delete_cookie($cookie_name){
  if(isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach ($cookies as $cookie) {
      $parts = explode('=', $cookie);
      $name = trim($parts[0]);
      setcookie($ame, '', time()-1000);
      setcookie($ame, '', time()-1000, '/');
    }
  }
}

function get_user_from_cookie(){
  $val = $_COOKIE['conn'];
  $delim = ':';
  return explode($delim, $val)[0];
}

function get_password_from_cookie(){
  $val = $_COOKIE['conn'];
  $delim = ':';
  return explode($delim, $val)[1];
}

function get_id_from_username(){
  $name = get_user_from_cookie();
  $db = connect_database();

  $ps1 = pg_prepare($db, "ps1", "SELECT user_id FROM u_user WHERE username = \$1");
  $res = pg_execute($db, "ps1", array($name));

  $ids = pg_fetch_row($res);
  $id = $ids[0];

  pg_close($db);
  return $id;
}

function get_softs() {
  $db = connect_database();
  $pseudo = get_user_from_cookie();

  $ps1 = pg_prepare($db, "ps1", "SELECT array_to_string(array_agg(a.name), ',') AS Applications FROM u_user u INNER JOIN role r ON u.role_id = r.role_id INNER JOIN orientation o ON u.orientation_id = o.orientation_id INNER JOIN application a ON a.orientation_id = o.orientation_id WHERE username = \$1 GROUP BY u.username, u.password, r.role_name");
  $res = pg_execute($db, "ps1", array($pseudo));

  $row = pg_fetch_row($res);
  $apps = $row[0];
  $delim = ',';

  pg_close($db);
  return explode($delim, $apps);
}

function write_conf($app){
    $ip = "127.0.0.1";
    $user = get_user_from_cookie();
    $passwd = get_password_from_db($user);

    $connection = ssh2_connect($ip, 22);
    ssh2_auth_password($connection, $user, $passwd);
    $shell = ssh2_exec($connection, 'sed -i "6d" /home/'.$user.'/.vnc/xstartup');
    stream_set_blocking($shell, true);
    $shell = ssh2_exec($connection, 'sed -i "6i/usr/bin/xterm -e "/home/'.$user.'/"'.$app.'/launch.sh" /home/'.$user.'/.vnc/xstartup');
    stream_set_blocking($shell, true);
}

function kill_vncserver(){
  $ip = "127.0.0.1";
  $user = get_user_from_cookie();
  $passwd = get_password_from_db($user);

  $connection = ssh2_connect($ip, 22);

  ssh2_auth_password($connection, $user, $passwd);
  $id = get_id_from_username($user);
  $shell = ssh2_exec($connection, '/usr/bin/vncserver -kill :'.$id);
  stream_set_blocking($shell, true);
}

function launch_vncserver(){
  $ip = "127.0.0.1";
  $user = get_user_from_cookie();
  $passwd = get_password_from_db($user);

  $connection = ssh2_connect($ip, 22);

  ssh2_auth_password($connection, $user, $passwd);
  $id = get_id_from_username($user);
  $shell = ssh2_exec($connection, '/usr/bin/vncserver :'.$id);
  stream_set_blocking($shell, true);
}

function kill_docker(){
  $ip = "127.0.0.1";
  $user = get_user_from_cookie();
  $passwd = get_password_from_db($user);

  $connection = ssh2_connect($ip, 22);

  ssh2_auth_password($connection, $user, $passwd);
  $shell = ssh2_exec($connection, 'docker kill $(docker ps -aq)');
  stream_set_blocking($shell, true);
}

function rm_docker() {
  $ip = "127.0.0.1";
  $user = get_user_from_cookie();
  $passwd = get_password_from_db($user);

  $connection = ssh2_connect($ip, 22);

  ssh2_auth_password($connection, $user, $passwd);
  $shell = ssh2_exec($connection, 'docker rm $(docker ps -aq)');
  stream_set_blocking($shell, true);
}

// function get_total_disk_space(){
//   $ip = "127.0.0.1";
//   $user = get_user_from_cookie();
//   $passwd = get_password_from_db($user);
//
//   $connection = ssh2_connect($ip, 22);
//
//   ssh2_auth_password($connection, $user, $passwd);
//   $shell = ssh2_exec($connection, 'df /home/'.$user.' -H | cut -d" " -f4');
//   stream_set_blocking($stream, true);
//   return stream_get_contents($shell);
// }


?>
