<?php

namespace iutnc\nrv\action;


/**
 * Class DefaultAction
 */
class DefaultAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executeGet() :string
    {
        header('Location: index.php?action=evening');
        return "";
    }

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "<h1>SITE NRV</h1>" ;
    }
}