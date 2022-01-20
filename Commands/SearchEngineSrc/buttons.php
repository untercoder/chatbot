<?php
class SearchMenuButton {

    public $button = null;

    public function __construct() {
        $this->button = array(
            'one_time' => true,
            'buttons' => array(array(array('action' => array('type' => 'text',
                'label' => 'Статистика по городу.'),
                'color' => "secondary"),array('action' => array('type' => 'text',
                'label' => 'Закончить поиск.'),
                'color' => "negative")),array(array('action' => array('type' => 'text',
                'label' => 'Показать следующию анкету.'),'color' => "positive"))));
    }
}