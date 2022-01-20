<?php
require_once __DIR__.'/../../Database/UserData.php';

class UserProfileView {

    protected $userDataOutput = null;

    public function __construct() {
        $this -> userDataOutput = new UserData();
    }

    public function getProfileView($peer_id) {
        $userData = $this->userDataOutput -> getUserData(array($peer_id), $this->userDataOutput->sql_command['SQL_SELECT_ALL_USER_DATA']);
        $viewStr = $userData['name'].", ".$userData['age']." лет.\n"
            .$userData['location'].".\n Увлечение: ".$userData['interest']."\n".$userData['comment']."\n".$userData['user_vk_id'];
        $photo = unserialize($userData['photo']);
        $audio = unserialize($userData['audio']);
        $attachmentsView = $photo['type'].$photo['photo']['owner_id'].'_'.$photo['photo']['id'].'_'.$photo['photo']['access_key'].
            ','.$audio['type'].$audio['audio']['owner_id'].'_'.$audio['audio']['id'];

        return array('viewStr' => $viewStr, 'attachmentsView' => $attachmentsView);
    }
}