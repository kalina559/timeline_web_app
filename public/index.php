<html> 
<title>HTML with PHP</title>
<body>
<h1>My Example</h1>

<?php
include '../config.php';
//Get Heroku ClearDB connection information
//$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = DBHOST;
$cleardb_username = DBUSER;
$cleardb_password = DBPWD;
$cleardb_db = DBNAME;

$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
if(function_exists('mysqli_connect')){
	echo "Rozszerzenie MySQLi zainstalowane
	poprawnie";
	}else{
	echo "Niestety MySQLi nie działa";
	}

$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

$con->query("SET NAMES 'utf8'");
$query = "SELECT * from users";
$wynik = $con->query($query);
if($wynik === FALSE){
	echo "Wystąpil problem podczas wykonywania
	zapytania<br>";
}
else
{
	while(($wiersz = $wynik->fetch_assoc())!=NULL)
	{
		echo '<pre>'; print_r($wiersz); echo '</pre>';
	}
}
	$con->close();
	
	echo "Essa"
	
?>
 </body>
 </html>