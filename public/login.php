<?php
include '../src/LoginHandler.php';

$loginHandler = new LoginHandler;

if(!isset($username)){
 $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
 $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
}
if($loginHandler->tryLogin($username, $password) !== TRUE){
 header('WWW-Authenticate: Basic realm="Logowanie nie powiodło się."');
 header('HTTP/1.0 401 Unauthorized');

 echo <<<HTML
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <HTML>
 <HEAD>
 <TITLE>test PHP</TITLE>
 <meta http-equiv="content-type" content="text/html; charset=UTF-8" >
 </HEAD>
 <BODY>
 <p align='center'>
 Należy się zalogowac aby uzyskać dostęp do tej strony.
 </p>
 </BODY>
 </HTML>
HTML;
 exit;
}
echo <<<HTML
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <HTML>
 <HEAD>
 <TITLE>test PHP</TITLE>
 <meta http-equiv="content-type" content="text/html; charset=UTF-8" >
 </HEAD>
 <BODY>
 <p align='center'>
 Logowanie przebiegło poprawnie.<br>
 Witaj {$_SERVER['PHP_AUTH_USER']}
 </p>
 </BODY>
 </HTML>
HTML;
?>