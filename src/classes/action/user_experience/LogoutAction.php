<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\repository\NrvRepository;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\action\Action ;

class LogoutAction extends Action {


    /**
     * @inheritDoc
     */
    function executePost()
    {
        NrvAuthnProvider::logout();
        return "Vous etes deconnecté";
    }

    /**
     * @inheritDoc
     */
    function executeGet()
    {
        return $this->executePost() ;
    }
}



