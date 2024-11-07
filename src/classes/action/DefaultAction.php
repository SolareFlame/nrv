<?php

namespace iutnc\nrv\action;

use iutnc\nrv\action\Action;

/**
 * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué
 * comme annulé,
 */
class DefaultAction extends Action
{

    /**
     * @inheritDoc
     */
    public function get()
    {
        return "<h1>SITE NRV</h1>" ;
    }

    /**
     * @inheritDoc
     */
    public function post()
    {
        return "<h1>SITE NRV</h1>" ;
    }
}