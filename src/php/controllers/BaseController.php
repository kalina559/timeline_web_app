<?php
include __DIR__ . '/../account/AccountService.php';
include_once __DIR__ . '/InputField.php';

abstract class BaseController
{
    private $repository;
    protected $response;
    protected $output = array();


    function __construct($requiresArguments = false, $validateUserLoggedIn = false)
    {
        session_start();

        $className = get_class($this);
        log_message(LogModes::Info->name, "Creating $className");

        $this->repository = new AccountService();

        if ($requiresArguments && !isset($_POST['arguments'])) {
            throw new Exception("No request arguments were provided.");
        }

        if ($validateUserLoggedIn && !$this->repository->userIsLoggedIn()) {
            throw new Exception("User is not logged in.");
        }

        header('Content-Type: application/json');

        try {
            $this->execute();
            echo json_encode($this->response);
            http_response_code(200);
        } catch (Exception $e) {
            log_message(LogModes::Error->name, "{$e->getMessage()}: {$e->getTraceAsString()}" );
            
            $output = array();
            $output['errorMessage'] = $e->getMessage();
            $output['stackTrace'] = $e->getTraceAsString();

            echo json_encode($output);
            http_response_code(500);
        }
    }

    function __destruct()
    {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Deleting $className");


        unset($this->repository);
    }

    abstract function execute();
}
