<?php

require_once 'Config/config_vk_api.php';
require_once __DIR__ . '/../Database/UserAction.php';

class Listener
{
    protected $command_name = array (
        'Создать анкету.' => 'Registrar' ,
        'Отредактировать анкету.' => 'Registrar' ,
        'Поиск.' => 'SearchEngine' ,
        'Настроить поиск.' => 'SearchParam' ,
        'Задать параметры поиска.' => 'SearchParam' ,
        'Начать' => 'Start',
        'Поиск' => 'SearchEngine',
    );
    protected $userAction = null;
    protected $command = null;

    function __construct($event) {

        switch ($event['type']) {
            // Подтверждение сервера
            case CALLBACK_API_EVENT_CONFIRMATION:
                $this->giveResponseToConformation();
                break;
            // Получение нового сообщения
            case CALLBACK_API_EVENT_MESSAGE_NEW:
                $this->userAction = new UserAction();
                $this->chekAction($event['object']);
                break;
            default:
                $this->giveResponseToUnsupportedEvent();
                break;
        }

    }

    protected function giveResponseToConformation() {
        echo(CALLBACK_API_CONFIRMATION_TOKEN);
    }

    protected function chekAction($event){
        $cmd = null;
        $response_bd = $this->userAction->checkAction($event['peer_id']);
        if($response_bd) {
            $cmd = $response_bd;
        }
        else {
            $cmd = array(
                'peer_id' => $event['peer_id'],
                'command' => isset($this->command_name[$event['text']]) ? $this->command_name[$event['text']] : 'Error',
                'iterator' => 0,
                'action' => false,
            );
        }
        $this->setCommandForm($cmd);
    }

    protected function setCommandForm($message) {
        $this->command = $message;
    }

    public function getCommandForm() {
        return $this->command;
    }

    protected function giveResponseToUnsupportedEvent() {
        echo('Unsupported event!');
    }

}