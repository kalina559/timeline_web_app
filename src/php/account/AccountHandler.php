<?php
include '../../../config.php';

class AccountHandler
{  
    public function tryLogin($login, $password) {

        $hashedPassword = hash('sha256', $password);

        $con = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);

        $con->query("SET NAMES 'utf8'");        
        $smtp = $con->prepare("SELECT * FROM users WHERE login = ? AND password = ?");
        $smtp->bind_param('ss', $login, $hashedPassword);   // binding params to prevent sql injection

        $smtp->execute();

        $result = $smtp->get_result();
        if($result->num_rows === 1){
            return TRUE;

        }
        
            $con->close();

            return FALSE;

    }

    public function markUserAsLoggedIn($userId) {
        // Add user to the loggedInUsersTable
        
        //Add (userId, sessionId, dateLoggedIn, dateLoggedOut) record to db
    }

    public function logout($userId){
        //set dateLoggedOut to now
    }

    public function checkIfUserLoggedIn($userId){
        //check if user is logged in in current session
    }
}
