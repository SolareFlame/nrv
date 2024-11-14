<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;

class ContactAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
        return "<strong>Action à définir</strong>";
    }
}