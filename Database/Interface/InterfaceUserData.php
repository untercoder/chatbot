<?php

interface InterfaceUserData {
    public function setName($userResponse, $peer_id);
    public function setAge($userResponse, $peer_id);
    public function setLocation($userResponse, $peer_id);
    public function setInterest($userResponse, $peer_id);
    public function setComment($userResponse, $peer_id);
    public function setPhoto($userResponse, $peer_id);
    public function setAudio($userResponse, $peer_id);
    public function checkForm($peer_id);
    public function getRegStatus($peer_id);
    public function setRegStatus($peer_id, $status);
    public function getUserData($peer_id, $queryStr);
}