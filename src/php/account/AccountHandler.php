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
            $row = $result->fetch_row();
            $userId = $row[0] ?? false;

            $this->clearPreviousUserLogins($con, $userId);
            $this->markUserAsLoggedIn($con, $userId);

            return TRUE;
        }
        
            $con->close();

            return FALSE;

    }

    public function markUserAsLoggedIn($con, $userId) {
        $session_id = session_id();

        $smtp = $con->prepare("INSERT INTO  users_logged_in (user_id, session_id, date_created) VALUES (?, ?, CURRENT_TIMESTAMP())");
        $smtp->bind_param('ss', $userId, $session_id);

        $smtp->execute();
    }

    public function clearPreviousUserLogins($con, $userId) {
        $smtp = $con->prepare("DELETE FROM  users_logged_in WHERE user_id = ?");
        $smtp->bind_param('s', $userId);

        $smtp->execute();
    }

    public function logout(){
        $session_id = session_id();

        $con = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);
        $con->query("SET NAMES 'utf8'");        
        $smtp = $con->prepare("DELETE FROM users_logged_in WHERE session_id = ?");
        $smtp->bind_param('s', $session_id);

        $smtp->execute();
    }

    public static function checkIfUserLoggedIn(){
        $session_id = session_id();

        $con = mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);
        $con->query("SET NAMES 'utf8'");        
        $smtp = $con->prepare("SELECT * FROM users_logged_in WHERE session_id = ?");
        $smtp->bind_param('s', $session_id);

        $smtp->execute();

        $result = $smtp->get_result();
        if($result->num_rows > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
