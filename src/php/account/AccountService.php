<?php
include 'AccountRepository.php';

class AccountService
{
    private $repository;

    function __construct() {
        $this->repository = new AccountRepository();
    }

    function __destruct() {
        unset($this->repository);
    }

    public function login($login, $password)
    {
        return $this->repository->login($login, $password);
    }

    public function logout()
    {
        return $this->repository->logout();
    }

    public function validateUserLoggedIn()
    {
        return $this->repository->validateUserLoggedIn();
    }

    public function getLoggedInUser()
    {
        return $this->repository->getLoggedInUser();
    }

    public function updateUsersPassword($oldPassword, $newPassword)
    {
        return $this->repository->updateUsersPassword($oldPassword, $newPassword);
    }
}
