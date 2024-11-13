<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\repository\NrvRepository;

/**
 * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué
 * comme annulé,
 */
class CancelShowAction extends Action
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executePost(): string
    {
        try{
            if (!NrvAuthnProvider::asPermission(50)) {
                throw new Exception("Permission refusée : seul un organisateur peut annuler un spectacle.");
            }

            $showUuid = $_POST["showUuid"] ?? null;
            if(!$showUuid) {
                throw new \Exception("Identifiant du spectacle non fourni");
            }

            $repo = NrvRepository::getInstance();
            $repo->cancelShow($showUuid);

            return "Le spectacle a bien été annulé.";
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
        return "";
        // TODO: Implement post() method.
    }
}