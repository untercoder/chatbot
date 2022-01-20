<?php

require_once 'Command.php';
require_once __DIR__.'/../Database/UserAction.php';
require_once  __DIR__.'/../Database/UserData.php';
require_once  __DIR__.'/../Database/UserSearchParam.php';
require_once 'RegisterSrc/text.php';
require_once 'RegisterSrc/buttons.php';
require_once __DIR__ . '/../Config/config_vk_api.php';
require_once 'Tools/UserProfileView.php';
require_once 'MenuSrc/buttons.php';

class Registrar extends Command {

    protected $userActionData = null;
    protected $instruments = array('1' => 'Акустическая гитара',
        '2' => 'Электрогитара', '3' => 'Басгитара', '4' => 'Барабаны', '5' => 'Вокал');
    protected $newUser = null;
    protected $userProfileView = null;

    public function __construct($id, $actionIterator, $action) {
        parent::__construct($id, $actionIterator, $action);
        $this->userActionData = new UserAction();
        $this -> userActualData = new UserData();
        $this ->userActualData->checkForm($this->peer_id);
        $this -> newUser = $this -> userActualData -> getRegStatus($this->peer_id);
        $this -> userProfileView = new UserProfileView();
        $this->userAncillaryData = new UserSearchParam();
        $this -> userAncillaryData -> checkParam($this->peer_id);
    }


    protected function AskNoButton($textAsk) {
        if($this->newUser == false) {
            $this -> button = new NoWriteButton();
            $this ->send_messageAndButton($this->peer_id, $textAsk, json_encode($this->button->button));
        } else {
            $this ->send_messageAndButton($this->peer_id, $textAsk);
        }

    }

    protected function AskButton($button, $textAsk) {
        $this->button = $button;
        if($this->newUser == false) {
            $this->button->pushNewButton(NoWriteButton::$noElement);
        }
        $this ->send_messageAndButton($this->peer_id, $textAsk, json_encode($this->button->button));
    }

    protected function viewUserProfile() {
        $this->button = new YesOrNoButtons(NO_SAVE, YES_SAVE);
        $view = $this->userProfileView ->getProfileView($this->peer_id);
        $this->send_messageAndButton($this->peer_id, $view['viewStr'], json_encode($this->button->button), $view['attachmentsView']);
    }

    protected function getDataFromVk() {
        $param = array(
                        'user_ids' => $this->peer_id,
                        'fields' => 'photo_200',
                        'name_case' => 'Nom',
                        'access_token' => VK_API_ACCESS_TOKEN,
                        'v' => VK_API_VERSION
            );

        return $this -> sendRequestToVK('users.get', $param);
    }


    protected function setUserData ($userResponse) {
        if ($userResponse['text'] != NO_WRITE) {
            switch ($this->actionIterator) {
                case 1:
                    $name = null;
                    if($userResponse['text'] == GET_NAME_FROM_VK) {
                        $data = $this -> getDataFromVk();
                        $name = $data[0]['first_name'];
                    } else {
                        $name = $userResponse['text'];
                    }
                    $this->userActualData->setName($name, $this->peer_id);
                    break;
                case 2:
                    $this->userActualData->setAge($userResponse, $this->peer_id);
                    break;
                case 3:
                    $this->userActualData->setLocation($userResponse, $this->peer_id);
                    break;
                case 4:
                    $this->userActualData->setInterest($this->instruments[$userResponse['text']], $this->peer_id);
                    break;
                case 5:
                    $this->userActualData->setComment($userResponse, $this->peer_id);
                    break;
                case 6:
                    $this->userActualData->setPhoto($userResponse, $this->peer_id);
                    break;
                case 7:
                    $this->userActualData->setAudio($userResponse, $this->peer_id);
                    break;
            }
        }

        $this->addActionIterator();
        $this->userActionData -> updateAction($this->getStateCommand());
    }

    public function selectStageDialog($userResponse) {
        switch ($this->actionIterator) {
            case 0 :
                $this->AskButton(new getNameFromVKButton(), ASK_USER_NAME_TXT);
                $this->addActionIterator();
                if($this->Action == false) {
                    $this ->Action = true;
                    $this -> userActionData ->createAction($this->getStateCommand());
                }
                $this -> userActionData -> updateAction($this->getStateCommand());
                break;
            case 1 :
                $this->setUserData($userResponse);
                $this->AskButton(new UserAgeButton(), ASK_USER_AGE_TXT);
                break;
            case 2 :
                $this->setUserData($userResponse);
                $this->AskButton(new UserLocationButton(), ASK_USER_LOCATION_TXT);
                break;
            case 3 :
                $this->setUserData($userResponse);
                $this->AskButton(new ButtonSelectInstruments(count($this->instruments)), ASK_USER_INTEREST_TXT);
                break;
            case 4 :
                if(array_key_exists($userResponse['text'], $this->instruments) or $userResponse['text'] == NO_WRITE) {
                    $this->setUserData($userResponse);
                    $this->AskNoButton(ASK_USER_COMMENT_TXT);
                } else {
                    $this -> AskNoButton(ERROR_INPUT_INSTRUMENTS);
                    $this->AskButton(new ButtonSelectInstruments(count($this->instruments)), ASK_USER_INTEREST_TXT);
                }
                break;
            case 5 :
                $this->setUserData($userResponse);
                $this->AskNoButton(ASK_USER_PHOTO_TXT);
                break;
            case 6 :
                $this->setUserData($userResponse);
                $this->AskNoButton(ASK_USER_AUDIO_TXT);
                break;
            case 7 :
                $this->setUserData($userResponse);
                $this->userActionData -> updateAction($this->getStateCommand());
                $this -> selectStageDialog($userResponse);
                break;
            case 8:
                $this->viewUserProfile();
                $this->addActionIterator();
                $this -> userActionData -> updateAction($this->getStateCommand());
                break;
            case 9:
                if($userResponse['text'] == YES_SAVE) {
                    $this->addActionIterator();
                    $this->selectStageDialog($userResponse);
                } elseif($userResponse['text'] == NO_SAVE) {
                    $this -> actionIterator = 0;
                    $this->selectStageDialog($userResponse);
                } else {
                    $this->AskNoButton( ERROR_YES_OR_NO_INPUT);
                    $this->viewUserProfile();
                }
                $this -> userActionData -> updateAction($this->getStateCommand());

                break;
            default:
                $this->userActualData -> setRegStatus($this->peer_id, false);
                $this->endDialog('Анкета создана.');
                $this->userActionData->destroyAction($this->getStateCommand());
                break;
        }
    }
}