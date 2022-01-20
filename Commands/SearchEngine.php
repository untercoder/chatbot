<?php

require_once 'Command.php';
require_once 'Tools/UserProfileView.php';
require_once 'SearchEngineSrc/buttons.php';
require_once __DIR__.'/../Database/Search.php';
require_once __DIR__.'/../Database/UserAction.php';
require_once __DIR__.'/../Database/Statistics.php';

class SearchEngine extends Command {

    protected $searchObj = null;
    protected $userProfileViewObj = null;
    protected $userAction = null;
    protected $responseSearch = null;
    protected $statistic = null;

    public function __construct($id, $actionIterator, $action) {
        parent::__construct($id, $actionIterator, $action);
        $this->searchObj = new Search($this->peer_id);
        $this->responseSearch = $this->searchObj -> checkSearchResult();
        $this->userProfileViewObj = new UserProfileView();
        $this->userAction = new UserAction();
        if($this->Action == false) {
            $this->userAction->createAction($this->getStateCommand());
        }
    }

    protected function showProfile($peer_id) {
        $this -> button = new SearchMenuButton();
        $view  = $this->userProfileViewObj->getProfileView($peer_id);
        $this->send_messageAndButton($this->peer_id, $view['viewStr'],
            json_encode($this->button->button), $view['attachmentsView']);
    }

    public function selectStageDialog($userResponse) {
        if($this->responseSearch) {
            if($userResponse['text'] == 'Закончить поиск.') {
                $this->userAction->destroyAction($this->getStateCommand());
                $this->endDialog('Конец');
            }
            elseif ($userResponse['text'] == 'Статистика по городу.') {
                $this->statistic = new Statistics($this->peer_id);
                $statisticStr = $this->statistic->getStatistic();
                $this->button = new SearchMenuButton();
                $this->send_messageAndButton($this->peer_id, $statisticStr,
                    json_encode($this->button->button));
            }
            else {
                $user_id = $this->searchObj->getElementResult();
                $this->showProfile($user_id);
            }
        } else {
            $this->userAction->destroyAction($this->getStateCommand());
            $this->statistic = new Statistics($this->peer_id);
            $statisticStr = $this->statistic->getStatistic();
            $this->endDialog("В твоем городе по данным параметрам никого не нашли :("."\n".$statisticStr);
        }

    }
}