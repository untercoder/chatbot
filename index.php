<?php
require_once 'Config/config_vk_api.php';
require_once 'Factory/Listener.php';
require_once 'Factory/CommandCreator.php';

ini_set('error_reporting', E_ALL);
ini_set('ignore_repeated_errors', TRUE); //always use TRUE
ini_set('display_errors', FALSE); //Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment
ini_set('log_errors', TRUE); //Error/Exception file logging engine.
ini_set('error_log', __DIR__ . '/doc/log/error_log'); //Logging file path

$event = json_decode(file_get_contents('php://input'), true);
echo 'ok';
$userResponse = $event['object'];

$listener = new Listener($event);
$commandForm = $listener->getCommandForm();
$commandCreator = new CommandCreator($commandForm);
$commandObj = $commandCreator -> getCommandObj();
if(isset($commandObj)) {
    $commandObj -> selectStageDialog($userResponse);
}



