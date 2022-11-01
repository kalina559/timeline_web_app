<?php

class AccountHandler
{
    static function tryLogin($login, $password)
    {

        log_message(LogModes::Info->name, "Trying to log in");
        $hashedPassword = hash('sha256', $password);

        $con = getDbConnection();

        $result = executeQueryWithParams(
            $con,
            "SELECT id FROM users WHERE login = ? AND password = ?",
            'ss',
            $login,
            $hashedPassword
        );

        if ($result->num_rows === 1) {
            $row = $result->fetch_row();
            $userId = $row[0] ?? false;

            AccountHandler::clearExpiredUserLogins($con, $userId);
            AccountHandler::markUserAsLoggedIn($con, $userId);

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

        executeQueryWithParams(
            $con,
            "INSERT INTO  users_logged_in (user_id, session_id, valid_until) VALUES (?, ?, ?)",
            'sss',
            $userId,
            $session_id,
            $now->format('Y-m-d H:i:s')
        );
    }

    static function clearExpiredUserLogins($con)
    {
        $now = new DateTime();
        $session_id = session_id();

        executeQueryWithParams(
            $con,
            "DELETE FROM  users_logged_in WHERE valid_until < ? OR session_id = ?",
            'ss',
            $now->format('Y-m-d H:i:s'),
            $session_id
        );
    }

    static function logout()
    {
        $session_id = session_id();
        $con = getDbConnection();

        executeQueryWithParams(
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

        $result = executeQueryWithParams(
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

        $result = executeQueryWithParams(
            $con,
            "SELECT login FROM users_logged_in
            JOIN users ON users_logged_in.user_id = users.id
            WHERE session_id = ? AND valid_until > ?",
            'ss',
            $session_id,
            $now->format('Y-m-d H:i:s')
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

    static function updateUsersPassword($oldPassword, $newPassword)
    {
        $con = getDbConnection();
        $currentUser = AccountHandler::getCurrentUser($con);
        if ($currentUser->num_rows == 1) {
            $row = $currentUser->fetch_row();
            $userId = $row[0];
            $isOldPasswordCorrect = AccountHandler::validateOldPassword($con, $userId, $oldPassword);

            if ($isOldPasswordCorrect->num_rows == 1) {
                $row = $isOldPasswordCorrect->fetch_row();
                AccountHandler::updatePassword($con, $userId, $newPassword);
                $con->close();
                return "success";
            } else {
                $con->close();
                return "old password incorrect";
            }
        } else {
            $con->close();
            return "no user found";
        }
    }

    static function getCurrentUser($con)
    {
        $session_id = session_id();

        return executeQueryWithParams(
            $con,
            "SELECT user_id FROM users_logged_in
            WHERE session_id = ?",
            's',
            $session_id
        );
    }

    static function validateOldPassword($con, $userId, $oldPassword)
    {
        $hashedOldPassword = hash('sha256', $oldPassword);

        // check if the provided old password is correct
        return executeQueryWithParams(
            $con,
            "SELECT id FROM users
        WHERE id = ? AND password = ?",
            'ss',
            $userId,
            $hashedOldPassword
        );
    }

    static function updatePassword($con, $userId, $newPassword)
    {
        $hashedNewPassword = hash('sha256', $newPassword);

        // update the password
        executeQueryWithParams(
            $con,
            "UPDATE users
            SET password = ?
            WHERE id = ?",
            'ss',
            $hashedNewPassword,
            $userId
        );
    }
}
