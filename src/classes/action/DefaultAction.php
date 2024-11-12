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
    public function executeGet()
    {
        return "<h1>SITE NRV</h1>" ;
    }

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "<h1>SITE NRV</h1>" ;
    }
}