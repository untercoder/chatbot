<?php
require_once __DIR__.'/../Database/UserSearchParam.php';
require_once __DIR__.'/Registrar.php';
require_once __DIR__.'/RegisterSrc/buttons.php';
require_once __DIR__.'/RegisterSrc/text.php';
require_once __DIR__.'/../Database/UserData.php';

class SearchParam extends Registrar {
    public function __construct($id, $actionIterator, $action) {
        $this ->peer_id = $id;
        $this ->actionIterator = $actionIterator;
        $this -> Action = $action;
        $this->userActionData = new UserAction();
        $this->userActualData = new UserSearchParam();
        $this->userActualData -> checkParam($this -> peer_id);
        $this -> newUser = $this -> userActualData -> getRegStatus($this->peer_id);
        $this->userAncillaryData = new UserData();
        $this -> userAncillaryData -> checkForm($this->peer_id);
    }

    public function selectStageDialog($userResponse) {
        switch ($this->actionIterator) {
            case 0 :
                $this->AskButton(new ButtonSelectInstruments(count($this->instruments)), SEARCH_INTEREST_USER);
                $this->addActionIterator();
                $this->userActionData -> createAction($this->getStateCommand());
                break;
            case 1 :
                $this->setUserData($userResponse);
                $this->AskButton(new UserLocationButton(), SEARCH_LOCATION_USER);
                break;
            case 2 :
                $this->setUserData($userResponse);
                $this->selectStageDialog($userResponse);
                break;
            default :
                $this->userActualData -> setRegStatus($this->peer_id, false);
                $this->userActualData->clearResultSearch($this->peer_id);
                $this->endDialog('Параметры поиска заданы!');
                $this->userActionData->destroyAction($this->getStateCommand());
                break;
        }
    }

    protected function setUserData($userResponse) {
        if($userResponse['text'] != NO_WRITE) {
            switch ($this->actionIterator) {
                case 1:
                    $this->userActualData -> setInterest($this->instruments[$userResponse['text']], $this->peer_id);
                    break;
                case 2:
                    $this->userActualData -> setLocation($userResponse, $this->peer_id);
                    break;
            }
        }
        $this->addActionIterator();
        $this->userActionData -> updateAction($this->getStateCommand());
    }


}