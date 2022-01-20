<?php

require_once 'Database.php';
require_once 'UserData.php';

class Statistics extends UserData {

    protected $peer_id = null;
    protected $location = null;
    protected $interest = array('Акустическая гитара' => null,
        'Электрогитара' => null, 'Басгитара' => null,  'Барабаны' => null, 'Вокал' => null);

    public function __construct($peer_id) {
        $this->peer_id = $peer_id;
        $this->NAME_TABLE = 'user_data';
        $this->sql_command = array (
            'SQL_SEARCH_IN_USER_DATA' => 'SELECT `peer_id` FROM `user_data` WHERE `location` LIKE ? AND `interest`=?',
            'SQL_SELECT_SEARCH_PARAM' => "SELECT `location` FROM `user_search_param` WHERE peer_id = ?",
        );

        $Connector = new Connector($this->NAME_TABLE);
        $this->database = $Connector -> getDatabase();
        $this->location = $this -> getLocation();
    }

    protected function getLocation () {
        $response = $this->getUserData(array($this->peer_id), $this->sql_command['SQL_SELECT_SEARCH_PARAM']);
        return $response['location'];
    }

    public function getStatistic() {
        foreach ($this->interest as $key => $value) {
            $this -> interest[$key] = count($this->getUserData(array('%'.$this->location.'%', $key),
                $this->sql_command['SQL_SEARCH_IN_USER_DATA'], PDO::FETCH_NUM, true));
        }

        $resultArr = [];

        foreach ($this->interest as $key => $value) {
            array_push($resultArr, $key." -- ".$value.".");
        }

        array_unshift($resultArr, 'Колличество пользователей в твоем городе, которые указали следующие интересы : ');

        return implode("\n", $resultArr);

    }



}