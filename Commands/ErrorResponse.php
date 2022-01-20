<?php
require_once 'Command.php';
require_once __DIR__.'/../Database/UserData.php';
require_once 'MenuSrc/buttons.php';

class ErrorResponse extends Command
{
    public function selectStageDialog($userResponse) {
        $this->endDialog('Такой команды нет!');
    }
}