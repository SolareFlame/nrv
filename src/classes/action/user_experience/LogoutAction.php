<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\repository\NrvRepository;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\action\Action ;

class LogoutAction extends Action {


    /**
     * @inheritDoc
     */
    function executePost(): string
    {
        NrvAuthnProvider::logout();
        return "Vous etes deconnecté";
    }

    /**
     * @inheritDoc
     */
    function executeGet(): string
    {
        return $this->executePost() ;
    }
}



