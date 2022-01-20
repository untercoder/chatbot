<?php


interface InterfaceUserAction {
    public function checkAction($peer_id);
    public function createAction($commandState);
    public function destroyAction($commandState);
    public function updateAction($commandSate);
}