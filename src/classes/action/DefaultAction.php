<?php

namespace iutnc\nrv\action;

use Exception;
use iutnc\nrv\action\program_navigation\DisplayAllEveningsAction;

/**
 * Class DefaultAction
 */
class DefaultAction extends Action
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executeGet(): string
    {
        $action = new DisplayAllEveningsAction();
        $content = $action->executeGet();

        $news = <<<HTML
<div class="d-flex align-items-center justify-content-center my-4 px-4">
    <div class="mx-2 title-border" style="background-color: #000000"></div>
    <h1 class="text-center mx-3">ACTUALITÉS</h1>
    <div class="mx-2 title-border" style="background-color: #000000"></div>
</div>
HTML;
        $evenings = <<<HTML
<div class="d-flex align-items-center justify-content-center my-4 px-4">
    <div class="mx-2 title-border" style="background-color: #FF9F1C"></div>
    <h1 class="text-center mx-3">SOIRÉES</h1>
    <div class="mx-2 title-border" style="background-color: #FF9F1C"></div>
</div>
HTML;
        return $news . $evenings . $content;
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executePost(): string
    {
        return $this->executeGet();
    }
}