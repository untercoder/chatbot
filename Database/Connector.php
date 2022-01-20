<?php
require_once __DIR__.'/../Config/database_config.php';

class Connector {

    protected $database = null;
    protected $response = null;

    public function __construct($tableStr) {
        try {
            $this->database = new PDO(DATABASE_HOST.DATABASE_NAME, DATABASE_USER, DATABASE_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            error_log('Ошибка Connector/__construct() не удалось подключиться к базе данных:'. $e->getMessage());
        }
        
        $this->checkTable($tableStr);

    }

    public function getDatabase() {
        return $this->database;
    }

    private function checkTable($tableStr) {
        
        $queryStr = "SHOW TABLES LIKE ?";
        $query = $this->database->prepare($queryStr);
        $prepareStr = '%'.$tableStr.'%';
        $query ->execute(array($prepareStr));
        $this ->response = $query->fetch(PDO::FETCH_ASSOC);
        
        if(!$this->response) {
            switch ($tableStr) {
                case 'user_action':
                    $queryStr = CREATE_USER_ACTION;    
                    break;
                case 'user_data':
                    $queryStr = CREATE_USER_DATA;
                    break;
                case 'user_search_param':
                    $queryStr = CREATE_USER_SEARCH_PARAM;
                    break;
            }
            
            $queryCreate = $this->database;
            $queryCreate->exec($queryStr);
            $log = date('Y-m-d H:i:s') . ' ' .'table user_action created';
            file_put_contents(__DIR__ . '/../doc/log/log.txt', $log . PHP_EOL, FILE_APPEND);
        }
    }


}