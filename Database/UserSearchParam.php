<?php

require_once 'Database.php';
require_once 'UserData.php';
require_once 'Connector.php';

class UserSearchParam extends UserData {

    public function __construct() {
        $this->NAME_TABLE = 'user_search_param';
        $Connector = new Connector($this->NAME_TABLE);
        $this->database = $Connector -> getDatabase();
        $this ->sql_command = array (
            'SQL_SELECT_ALL_USER_DATA' => "SELECT * FROM `user_search_param` WHERE peer_id = ?",
            'SQL_SELECT_REG_STATUS' => "SELECT `first_reg` FROM `user_search_param` WHERE peer_id = ?",
            'SQL_UPDATE_REG' => 'UPDATE `user_search_param` SET `first_reg`=? WHERE peer_id = ?',
            'SQL_UPDATE_LOCATION' => 'UPDATE `user_search_param` SET `location`=? WHERE peer_id = ?',
            'SQL_UPDATE_INTEREST' => 'UPDATE `user_search_param` SET `interest`=? WHERE peer_id = ?',
            'SQL_INSERT_INTO_USERDATA_NEW' => "INSERT INTO `user_search_param` (`peer_id`,`first_reg`) VALUES (?,?);",
            'SQL_UPDATE_RESULT_SEARCH' => 'UPDATE `user_search_param` SET `search_result`=? WHERE peer_id = ?'
        );
    }

    public function clearResultSearch($peer_id) {
        $this->setDataInTable(array(NULL, $peer_id), $this->sql_command['SQL_UPDATE_RESULT_SEARCH']);
    }

    public function checkParam($peer_id) {
        if($this->getUserData(array($peer_id), $this -> sql_command['SQL_SELECT_ALL_USER_DATA'])) {
//            $this -> log('This user set '.$peer_id);
        }
        else {
            try{
                $query = $this->database->prepare($this->sql_command['SQL_INSERT_INTO_USERDATA_NEW']);
                $query ->execute(array($peer_id, true));
                $this->log('Create user param '.$peer_id);
            } catch (PDOException $e) {
                error_log("Error in UserSearchParam/checkParam: Error INSERT".$e->getMessage());
            }
        }
    }
}