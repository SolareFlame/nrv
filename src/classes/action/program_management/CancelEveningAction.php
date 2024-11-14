<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\repository\NrvRepository;

class CancelEveningAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        try {
            if (!NrvAuthnProvider::hasPermission(50)) {
                throw new Exception("Permission refusée : seul un organisateur peut annuler une soirée.");
            }

            $eveningUuid = $_POST["eveningUuid"] ?? null;
            if (!$eveningUuid) {
                throw new Exception("Identifiant de la soirée non fourni");
            }

            $repo = NrvRepository::getInstance();
            $repo->cancelEvening($eveningUuid);

            return "La soirée a bien été annulé.";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
        return "";
    }
}