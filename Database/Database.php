<?php
require_once 'Connector.php';

abstract class Database {
    protected $database = null;
    protected $response = null;
    protected $NAME_TABLE = null;
    public $sql_command = null;

    public function __construct() {
        $Connector = new Connector($this->NAME_TABLE);
        $this->database = $Connector -> getDatabase();
    }

    protected function log($massage) {
        $log = date('Y-m-d H:i:s') . ' ' .$massage.' '.implode(' ' , $this->response);
        file_put_contents(__DIR__ . '/../doc/log/log.txt', $log . PHP_EOL, FILE_APPEND);
    }
}