<?php
//include '../../../config.php';
include '../common/CommonFunctions.php';

class AccountHandler
{
    static function tryLogin($login, $password)
    {

        log_message(LogModes::Info->name, "Trying to log in");
        $hashedPassword = hash('sha256', $password);

        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT id FROM users WHERE login = ? AND password = ?",
            'ss',
            $login,
            $hashedPassword
        );

        if ($result->num_rows === 1) {
            $row = $result->fetch_row();
            $userId = $row[0] ?? false;
            
            AccountHandler::markUserAsLoggedIn($con, $userId);
            AccountHandler::clearExpiredUserLogins($con, $userId);

            $con->close();
            return TRUE;
        }
        
        $con->close();

        return FALSE;
    }

    static function markUserAsLoggedIn($con, $userId)
    {
        $session_id = session_id();

        $now = new DateTime();
        $now->add(new DateInterval('PT' . LOGIN_SESSION_DURATION_MINUTES . 'M'));

        executeQuery(
            $con,
            "INSERT INTO  users_logged_in (user_id, session_id, valid_until) VALUES (?, ?, ?)",
            'sss',
            $userId,
            $session_id, $now->format('Y-m-d H:i:s')
        );
    }

    static function clearExpiredUserLogins($con)
    {
        $now = new DateTime();

        executeQuery(
            $con,
            "DELETE FROM  users_logged_in WHERE valid_until < ?",
            's',
            $now->format('Y-m-d H:i:s')
        );
    }

    static function logout()
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

    static function validateUserLoggedIn()
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
            return array(TRUE);
        } else {
            return FALSE;
        }
    }

    static function getLoggedInUser()
    {
        $session_id = session_id();
        $now = new DateTime();

        $con = getDbConnection();

        $result = executeQuery(
            $con,
            "SELECT login FROM users_logged_in
            JOIN users ON users_logged_in.user_id = users.id
            WHERE session_id = ? AND valid_until > ?",
            'ss',
            $session_id, $now->format('Y-m-d H:i:s')
        );

        $con->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $login = $row[0] ?? false;
            return array($login);
        } else {
            return NULL;
        }
    }
}
