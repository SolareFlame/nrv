<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;

class LogoutAction extends Action
{

    /**
     * @inheritDoc
     */
    function executePost(): string
    {
        session_destroy();
        header('Location: index.php');
        return "";
    }

    /**
     * @inheritDoc
     */
    function executeGet(): string
    {
        return $this->executePost();
    }
}



