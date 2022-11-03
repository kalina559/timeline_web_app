<?php
include __DIR__ . '/../account/AccountService.php';

abstract class BaseController
{
    private $repository;

    function __construct($validateUserLoggedIn = false)
    {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Creating $className");

        $this->repository = new AccountService();

        if ($validateUserLoggedIn && !$this->repository->userIsLoggedIn()) {
            throw new Exception("User is not logged in.");
        }

        return $this->execute();
    }

    function __destruct()
    {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Deleting $className");
        unset($this->repository);
    }

    abstract function execute();
}
