<?php

class Button {
    public $button = null;
    public function __construct() {
        $this -> button = array(
            'one_time' => true,
            'buttons' => array());
    }
    public function pushNewButton($button) {
        array_push($this->button['buttons'], $button);
    }
}

    class NoWriteButton extends Button {
        static public $noElement = array(array('action' => array('type' => 'text',
        'label' => 'Оставить как было.'),
        'color' => "positive"));
        public function __construct() {
            parent::__construct();
            array_push($this->button['buttons'], static::$noElement);
        }
    }

     class ButtonSelectInstruments extends Button {
        protected $interestElement = array('action' => array('type' => 'text',
                'label' => '1'),
                'color' => "secondary");
        public function __construct($amount) {
            $this -> button = array(
                'one_time' => true,
                'buttons' => array(array()));

            for($i = 1; $i <= $amount; $i++) {
                $this -> interestElement['action']['label'] = $i;
                array_push($this -> button['buttons'][0], $this -> interestElement);
            }

        }
    }

    class UserAgeButton extends Button {

        public function __construct(){
            $this -> button = array(
                'one_time' => true,
                'buttons' => array(array(array('action' => array('type' => 'text',
                    'label' => '18'),
                    'color' => "secondary"))));
        }
    }

    class UserLocationButton extends Button {
        public function __construct() {
            $this->button = array(
                'one_time' => true,
                'buttons' => array(array(array('action' => array('type' => 'location'),))));
        }
    }

    class getNameFromVKButton extends Button {
    protected $getVKButton = array(array('action' => array('type' => 'text',
        'label' => "Использовать мое имя в вк."),
        'color' => "positive"));
        public function __construct() {
            parent::__construct();
            $this -> pushNewButton($this->getVKButton);
        }
    }

    class YesOrNoButtons extends Button {
       protected $buttonYes = null;
       protected $buttonNo = null;
       public function __construct($NoLabel, $YesLabel) {
           $this -> button = array(
               'one_time' => true,
               'buttons' => array(array()));

           $this->buttonNo = array('action' => array('type' => 'text',
               'label' => $NoLabel),
               'color' => "negative");

           $this -> buttonYes = array('action' => array('type' => 'text',
               'label' => $YesLabel),
               'color' => "secondary");

           array_push($this -> button['buttons'][0], $this -> buttonYes);
           array_push($this -> button['buttons'][0], $this -> buttonNo);
       }
    }


