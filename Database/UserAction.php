<?php
require_once 'Connector.php';
require_once 'Database.php';
require_once 'Interface/InterfaceUserAction.php';
class UserAction extends Database implements InterfaceUserAction {

    public function __construct() {
        $this->NAME_TABLE = 'user_action';
        $this->sql_command = array
            (
            'SQL_SELECT_ALL_DATA' => 'SELECT * FROM `user_action` WHERE peer_id = ?',
            'SQL_INSERT_INTO_USER_ACTION' => 'INSERT INTO `user_action` VALUES (?,?,?,?)',
            'SQL_DELETE_ALL' => 'DELETE FROM `user_action` WHERE peer_id = ? ',
            'SQL_UPDATE_DATA' => 'UPDATE `user_action` SET `iterator`=?, `action`=?  WHERE peer_id = ?'
            );
        parent::__construct();
    }

    public function checkAction($peer_id) {
        try {
            $query = $this->database->prepare($this->sql_command['SQL_SELECT_ALL_DATA']);
            $query -> execute(array((int)$peer_id));
            $this->response = $query->fetch(PDO::FETCH_ASSOC);
//            $this->log('Check user action!');
        } catch (PDOException $e) {
            error_log('Error in UserAction/checkAction()'.$e ->getMessage());
        }
        if(isset($this->response)) {
            return $this->response;
        }
        else {
            return false;
        }
    }

    public function createAction($commandState) {
        try {
            $query = $this->database -> prepare($this->sql_command['SQL_INSERT_INTO_USER_ACTION']);
            $query->execute(array((int)$commandState['peer_id'], (int)$commandState['iterator'], $commandState['command'], (int)$commandState['action']));
        } catch (PDOException $e) {
            error_log('Error in UserAction/createAction()'.$e->getMessage());
        }
    }

    public function destroyAction($commandState) {
        try {
            $query = $this->database -> prepare($this->sql_command['SQL_DELETE_ALL']);
            $query->execute(array((int)$commandState['peer_id']));
        } catch (PDOException $e) {
            error_log('Error in UserAction/destroyAction()'.$e->getMessage());
        }

    }

    public function updateAction($commandState) {
        try {
            $query = $this->database -> prepare($this->sql_command['SQL_UPDATE_DATA']);
            $query->execute(array((int)$commandState['iterator'],(int)$commandState['action'],(int)$commandState['peer_id']));
        } catch (PDOException $e) {
            error_log('Error in UserAction/updateAction()'.$e->getMessage());
        }

    }


}