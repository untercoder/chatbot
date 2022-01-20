<?php

require_once 'Command.php';
require_once __DIR__.'/../Database/UserData.php';
require_once __DIR__.'/../Database/UserSearchParam.php';
require_once 'MenuSrc/buttons.php';

class Start extends Command
{
    public function selectStageDialog($userResponse) {
        $this->endDialog("Привет масленок! Чтобы начать использовать бота тебе нужно : \n 1.Создать свою анкету. \n 2.Задать параметры поиска(указать кого ты хочешь найти). ");
    }
}