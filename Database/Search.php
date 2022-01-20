<?php
require_once 'Database.php';
require_once 'UserData.php';
require_once 'UserSearchParam.php';

class Search extends UserData {
    protected $searchParam = null;
    protected $peer_id = null;

     public function __construct($peer_id) {
         $this->peer_id = $peer_id;
         $this->NAME_TABLE = 'user_search_param';
         $this->sql_command = array (
            'SQL_SEARCH_IN_USER_DATA' => 'SELECT `peer_id` FROM `user_data` WHERE `location` LIKE ? AND `interest`=?',
             'SQL_CHECK_SEARCH_RESULTS' => 'SELECT `search_result` FROM `user_search_param` WHERE `peer_id` = ?',
             'SQL_SELECT_SEARCH_PARAM' => "SELECT `location`,`interest` FROM `user_search_param` WHERE peer_id = ?",
            'SQL_SELECT_SEARCH_RESULT' => "SELECT `search_result` FROM `user_search_param` WHERE peer_id = ?",
             'SQL_UPDATE_RESULT_SEARCH' => 'UPDATE `user_search_param` SET `search_result`=? WHERE peer_id = ?'
        );

        $Connector = new Connector($this->NAME_TABLE);
        $this->database = $Connector -> getDatabase();
        $this->searchParam = $this->getSearchParam($this -> peer_id);
    }

    protected function getSearchParam($peer_id) {
        $result = $this->getUserData(array($peer_id), $this->sql_command['SQL_SELECT_SEARCH_PARAM']);
        return $result;
    }

    public function checkSearchResult(){
        $responseStr = $this->getUserData(array($this->peer_id), $this->sql_command['SQL_SELECT_SEARCH_RESULT']);
        $resultSearch = $this->search();
        if(count($resultSearch) == 0) {return false;}
        else {
            if(is_null($responseStr['search_result'])) {
                $this->setResultSearch($this->peer_id, $resultSearch);
            }
            return true;
        }

    }

    protected function search() {
        $this->response =  $this->getUserData(array('%'.$this->searchParam['location'].'%',$this->searchParam['interest']),
            $this->sql_command['SQL_SEARCH_IN_USER_DATA'], PDO::FETCH_NUM, true);
        return $this->response;
    } // Поиск по user_data

    protected function setResultSearch($peer_id, $resultSearch) {
        $this->setDataInTable(array(serialize($resultSearch),$peer_id), $this->sql_command['SQL_UPDATE_RESULT_SEARCH']);
    } // Загрузить результаты поиска в БД

    protected function getSearchResult($peer_id) {
        $responseStr = $this->getUserData(array($peer_id), $this->sql_command['SQL_SELECT_SEARCH_RESULT']);
        $responseObj = unserialize($responseStr['search_result']);
        return $responseObj;
    } // Получить результаты поиска из БД

    public function getElementResult() {
         $resultArr = $this->getSearchResult($this->peer_id);
         $user = array_shift($resultArr);
         if(count($resultArr) == 0) {
             $this->setResultSearch($this->peer_id, $this->search());
         } else {
             $this->setResultSearch($this->peer_id, $resultArr);
         }
         return $user[0];
     }
}