<?php

namespace iutnc\nrv\action;

use iutnc\nrv\action\program_navigation\DisplayAllEveningsAction;
use iutnc\nrv\action\program_navigation\DisplayAllShowsAction;
use Ramsey\Uuid\Uuid;


/**
 * Class DefaultAction
 */
class DefaultAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executeGet() : string
    {
        $action = new DisplayAllEveningsAction();
        $content = $action->executeGet();

        $news = '<h1 class="text-center my-4">ACTUALITÉS</h1>';
        $evenings = '<h1 class="text-center my-4">SOIRÉES</h1>';
        return $news . $evenings . $content;
    }


    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "<h1>SITE NRV</h1>" ;
    }
}