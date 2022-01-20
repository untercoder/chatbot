<?php

require_once __DIR__ . '/../Config/config_vk_api.php';
require_once  __DIR__.'/../Database/UserData.php';
require_once  __DIR__.'/../Database/UserSearchParam.php';
require_once 'MenuSrc/buttons.php';


abstract class Command
{
    protected $peer_id = null;
    protected $actionIterator = null;
    protected $Action = null;
    protected $button = null;
    protected $userActualData = null;
    protected $userAncillaryData = null;

    public function __construct($id, $actionIterator, $action){
        $this ->peer_id = $id;
        $this ->actionIterator = $actionIterator;
        $this -> Action = $action;
        $this->userActualData = new UserData();
        $this ->userActualData->checkForm($this->peer_id);
        $this->userAncillaryData = new UserSearchParam();
        $this -> userAncillaryData -> checkParam($this->peer_id);
    }

    //Методы цепочки диалога:(требуют реализации в классах наследниках)

   protected function responseMenu($id, $message, $buttonMenu) {
        $this->button = $buttonMenu;
        $this->send_messageAndButton($id, $message, json_encode($this->button));

    }

   protected function endDialog($message) {
        //Написать логику MenuNoReg
       $menuButtonsObj = new MenuButtons($this -> peer_id);
       $this->button = $menuButtonsObj -> button;
       $this->responseMenu($this->peer_id, $message, $this->button);
       $this->Action = false;
    }

    //Методы маршрутизаци по цепочкам диалога:(требуют реализации в классах наследниках)

    abstract public function selectStageDialog($userResponse);

    protected function addActionIterator($num = 1) {
        $this->actionIterator += $num;
    }

    //Методы для отслеживания состояния обьекта:

    protected function getStateCommand() {
        return array('peer_id' => $this -> peer_id,
            'iterator' => $this ->actionIterator,
            'action' => $this -> Action, 'command' => get_class($this));
    } //(требует реализации в классах наследниках)

    //Методы загрузки данных в БД:(требуют реализации в классах наследниках)

    //Методы формирования и отправки сообщений:

   protected function send_messageAndButton($peer_id, $message, $button = null, $attachments = null) {
        if($button == null and $attachments == null) {
            $this -> sendRequestToVK('messages.send', array(
                'peer_id' => $peer_id,
                'message' => $message,
                'access_token' => VK_API_ACCESS_TOKEN,
                'v' => VK_API_VERSION,
            ));
            } elseif($button != null and $attachments == null) {
                $this -> sendRequestToVK('messages.send', array(
                    'peer_id' => $peer_id,
                    'message' => $message,
                    'keyboard' => $button,
                    'access_token' => VK_API_ACCESS_TOKEN,
                    'v' => VK_API_VERSION,
                ));
            } elseif ($button == null and $attachments != null) {
            $this -> sendRequestToVK('messages.send', array(
                'peer_id' => $peer_id,
                'message' => $message,
                'attachment' => $attachments,
                'access_token' => VK_API_ACCESS_TOKEN,
                'v' => VK_API_VERSION,
            ));
            } elseif ($button != null and $attachments != null) {
            $this -> sendRequestToVK('messages.send', array(
                'peer_id' => $peer_id,
                'message' => $message,
                'keyboard' => $button,
                'attachment' => $attachments,
                'access_token' => VK_API_ACCESS_TOKEN,
                'v' => VK_API_VERSION,
            ));
             }
        }

   protected function sendRequestToVK($method, $params) {
        $query = http_build_query($params);
        $url = VK_API_ENDPOINT . $method . '?' . $query;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $error = curl_error($curl);
        if ($error) {
            error_log($error);
            throw new Exception("Failed {$method} request");
        }
        curl_close($curl);
        $response = json_decode($json, true);
        if (!$response || !isset($response['response'])) {
            error_log($json);
            throw new Exception("Invalid response for {$method} request");
        }

        return $response['response'];
    }

}