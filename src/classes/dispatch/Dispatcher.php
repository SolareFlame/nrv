<?php

namespace iutnc\nrv\dispatch;

use iutnc\nrv\action\DefaultAction;


class Dispatcher
{
    private $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void{
        switch($this->action){
            default :
                $html = (new DefaultAction)->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html) : void {
        echo $html ;
    }
}