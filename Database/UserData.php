<?php
require_once 'Database.php';
require_once 'Interface/InterfaceUserData.php';

class UserData extends Database implements InterfaceUserData {

    public function __construct() {
        $this->NAME_TABLE = 'user_data';
        $this->sql_command = array (
            'SQL_SELECT_ALL_USER_DATA' => "SELECT * FROM `user_data` WHERE peer_id = ?",
            'SQL_INSERT_INTO_USERDATA_NEW' => "INSERT INTO `user_data` (`peer_id`,`first_reg`,`user_vk_id`) VALUES (?,?,?);",
            'SQL_SELECT_REG_STATUS' => "SELECT `first_reg` FROM `user_data` WHERE peer_id = ?",
            'SQL_UPDATE_REG' => 'UPDATE `user_data` SET `first_reg`=? WHERE peer_id = ?',
            'SQL_UPDATE_NAME' => 'UPDATE `user_data` SET `name`=? WHERE peer_id = ?',
            'SQL_UPDATE_AGE' => 'UPDATE `user_data` SET `age`=? WHERE peer_id = ?',
            'SQL_UPDATE_LOCATION' => 'UPDATE `user_data` SET `location`=? WHERE peer_id = ?',
            'SQL_UPDATE_INTEREST' => 'UPDATE `user_data` SET `interest`=? WHERE peer_id = ?',
            'SQL_UPDATE_COMMENT' => 'UPDATE `user_data` SET `comment`=? WHERE peer_id = ?',
            'SQL_UPDATE_PHOTO' => 'UPDATE `user_data` SET `photo`=? WHERE peer_id = ?',
            'SQL_UPDATE_AUDIO' => 'UPDATE `user_data` SET `audio`=? WHERE peer_id = ?'
        );

        parent::__construct();
    }

    public function checkForm($peer_id) {
        if($this->getUserData(array($peer_id), $this->sql_command['SQL_SELECT_ALL_USER_DATA'])) {
//          $this -> log('This user set '.$peer_id);
        }
        else {
            try{
                $query = $this->database->prepare($this->sql_command['SQL_INSERT_INTO_USERDATA_NEW']);
                $query ->execute(array($peer_id, true, 'https://vk.com/id'.$peer_id));
                $this->log('Create user '.$peer_id);
            } catch (PDOException $e) {
                error_log("Error in UserData/checkForm: Error INSERT".$e->getMessage());
            }
        }
    }

    public function getRegStatus($peer_id) {
        $regStatus = null;
        try {
            $query = $this->database->prepare($this->sql_command['SQL_SELECT_REG_STATUS']);
            $query -> execute(array($peer_id));
            $this->response = $query->fetch(PDO::FETCH_ASSOC);
            $regStatus = $this->response['first_reg'];
        } catch (PDOException $e) {
            error_log("Error in UserData/getRegStatus(): Error SELECT".$e->getMessage());
        }

        return $regStatus;
    }

    public function getUserData($arrParam, $queryStr, $paramOutput = PDO::FETCH_ASSOC, $fetchAll = false) {
        try {
            $query = $this->database->prepare($queryStr);
            $query -> execute($arrParam);
            if($fetchAll) {
                $this->response = $query->fetchAll($paramOutput);
            } else {
                $this ->response = $query->fetch($paramOutput);
            }

        } catch (PDOException $e) {
            error_log("Error in UserData/getAllUserData(): Error SELECT".$e->getMessage());
        }

        return $this->response;
    }


    public function setRegStatus($peer_id, $status) {
        $this->setDataInTable(array($status, $peer_id), $this->sql_command['SQL_UPDATE_REG']);
    }

    protected function setDataInTable($userResponse, $queryStr) {
        try{
            $query = $this->database->prepare($queryStr);
            $query ->execute($userResponse);
        } catch (PDOException $e) {
            error_log("Error in UserData/setDataInTable: Error INSERT".$e->getMessage());
        }
    }

    public function setName($userResponse, $peer_id) {
        $this->setDataInTable(array($userResponse,$peer_id), $this->sql_command['SQL_UPDATE_NAME']);
    }

    public function setAge($userResponse, $peer_id) {
        $responseStr = $userResponse['text'];
        $this->setDataInTable(array($responseStr,$peer_id), $this->sql_command['SQL_UPDATE_AGE']);
    }

    public function setLocation($userResponse, $peer_id) {
        $responseStr = (isset($userResponse['geo']['place']['title'])) ? $userResponse['geo']['place']['title'] : $userResponse['text'];
        $this->setDataInTable(array($responseStr, $peer_id ),$this->sql_command['SQL_UPDATE_LOCATION']);
    }

    public function setInterest($userResponse, $peer_id) {
        $this->setDataInTable(array($userResponse,$peer_id ), $this->sql_command['SQL_UPDATE_INTEREST']);
    }

    public function setComment($userResponse, $peer_id) {
        $responseStr = $userResponse['text'];
        $this->setDataInTable(array($responseStr,$peer_id), $this->sql_command['SQL_UPDATE_COMMENT']);
    }

    public function setPhoto($userResponse, $peer_id) {
        $responseStr = serialize($userResponse['attachments'][0]);
        $this->setDataInTable(array($responseStr,$peer_id), $this->sql_command['SQL_UPDATE_PHOTO']);
    }

    public function setAudio($userResponse, $peer_id) {
        $responseStr = serialize($userResponse['attachments'][0]);
        $this->setDataInTable(array($responseStr,$peer_id), $this->sql_command['SQL_UPDATE_AUDIO']);
    }
}