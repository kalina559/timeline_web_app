<?php
include '../config.php';

class LoginHandler
{  
    public function tryLogin($login, $password) {
        echo 'Trying to login';


        $hashedPassword = hash('sha256', $password);

        $con = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);

        $con->query("SET NAMES 'utf8'");
        $query = "SELECT * FROM users WHERE login = '$login' AND password = '$hashedPassword'";

        echo "Query: $query";

        $result = $con->query($query);
        if($result->num_rows === 1){
            echo "USER FOUND";
            return TRUE;

        }
        
            $con->close();
            echo "NO USER FOUND";

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
?>