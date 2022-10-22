<?php
//include '../../../config.php';
include '../common/CommonFunctions.php';

class AccountHandler
{
    public function tryLogin($login, $password)
    {

        log_message(LogModes::Info->name, "Trying to log in");
        $hashedPassword = hash('sha256', $password);

        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT * FROM users WHERE login = ? AND password = ?",
            'ss',
            $login,
            $hashedPassword
        );

        if ($result->num_rows === 1) {
            $row = $result->fetch_row();
            $userId = $row[0] ?? false;

            $this->clearPreviousUserLogins($con, $userId);
            $this->markUserAsLoggedIn($con, $userId);

            return TRUE;
        }

        $con->close();

        return FALSE;
    }

    public function markUserAsLoggedIn($con, $userId)
    {
        $session_id = session_id();

        executeQuery(
            $con,
            "INSERT INTO  users_logged_in (user_id, session_id, date_created) VALUES (?, ?, CURRENT_TIMESTAMP())",
            'ss',
            $userId,
            $session_id
        );
    }

    public function clearPreviousUserLogins($con, $userId)
    {
        executeQuery(
            $con,
            "DELETE FROM  users_logged_in WHERE user_id = ?",
            's',
            $userId
        );
    }

    public function logout()
    {
        $session_id = session_id();
        $con = getDbConnection();

        executeQuery(
            $con,
            "DELETE FROM users_logged_in WHERE session_id = ?",
            's',
            $session_id
        );
        $con->close();
    }

    public static function checkIfUserLoggedIn()
    {
        $session_id = session_id();

        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT * FROM users_logged_in WHERE session_id = ?",
            's',
            $session_id
        );

        $con->close();

        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
