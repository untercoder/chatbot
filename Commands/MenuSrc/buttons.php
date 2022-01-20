<?php
require_once  __DIR__.'/../../Database/UserData.php';
require_once  __DIR__.'/../../Database/UserSearchParam.php';

class MenuButtons {
    public $button = null;
    protected $UserData = null;
    protected $UserSearchParam = null;
    protected $regStatusUserData = null;
    protected $regStatusUserSearchParam = null;

    public function __construct($peer_id) {
        $this->UserData = new UserData();
        $this->UserData -> checkForm($peer_id);
        $this -> UserSearchParam = new UserSearchParam();
        $this->UserSearchParam -> checkParam($peer_id);
        $this->regStatusUserData = $this->UserData->getRegStatus($peer_id);
        $this->regStatusUserSearchParam = $this -> UserSearchParam -> getRegStatus($peer_id);

        if($this->regStatusUserData == false and $this->regStatusUserSearchParam == false) {
            $this->button = $this->button = array(
                'one_time' => true,
                'buttons' => array(array(array('action' => array('type' => 'text',
                    'label' => 'Отредактировать анкету.'),
                    'color' => "secondary"),array('action' => array('type' => 'text',
                    'label' => 'Настроить поиск.'),
                    'color' => "secondary")),array(array('action' => array('type' => 'text',
                    'label' => 'Поиск.'),'color' => "positive"))));
        }
        else {
            $this -> button = array(
                'one_time' => true,
                'buttons' => array(array(array('action' => array('type' => 'text',
                    'label' => 'Создать анкету.'),
                    'color' => "positive")),array(array('action' => array('type' => 'text',
                    'label' => 'Задать параметры поиска.'),
                    'color' => "positive"))));

            if($this->regStatusUserData == false) {
                $this -> button['buttons'][0][0]['action']['label'] = 'Отредактировать анкету.';
                $this -> button['buttons'][0][0]['color'] = 'secondary';
            }
            if($this->regStatusUserSearchParam == false) {
                $this -> button['buttons'][1][0]['action']['label'] = 'Настроить поиск.';
                $this -> button['buttons'][1][0]['color'] = 'secondary';
            }
        }
    }
}


