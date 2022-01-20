<?php
spl_autoload_register(function ($class) {
    $path = __DIR__."/../Commands/{$class}.php";
    if(is_readable($path)){
        require_once $path;
    }
    else {
        error_log("Class {$class} not found!");
    }
});

class CommandCreator {
    protected $command = null;

    public function __construct($command) {
        switch ($command['command']) {
            case 'Registrar':
                $this->command = new Registrar($command['peer_id'],$command['iterator'], $command['action']);
                break;
            case 'SearchEngine':
                $this->command = new SearchEngine($command['peer_id'],$command['iterator'], $command['action']);
                break;
            case 'Start':
                $this -> command = new Start($command['peer_id'],$command['iterator'], $command['action']);
                break;
            case 'SearchParam':
                $this -> command = new SearchParam($command['peer_id'],$command['iterator'], $command['action']);
                break;
            case 'Error':
                $this ->command = new ErrorResponse($command['peer_id'],$command['iterator'], $command['action']);
                break;
        }
    }

    public function getCommandObj() {
        return $this -> command;
    }

}