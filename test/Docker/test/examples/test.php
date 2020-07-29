//  Configure DB Parameters
$host = "localhost";
$dbname = "users";
$dbuser = "jeremy";
$userpass = "Polopolo123+";

$con = pg_connect("host=$host
                                  dbname=$dbname
                                  user=$dbuser
                                  password=$userpass
                                  ");


if (!$con) {
        die('Could not connect');
}
else {
        echo ("Connected to local DB");
}
