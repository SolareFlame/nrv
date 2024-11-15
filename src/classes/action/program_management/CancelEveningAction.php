<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\exception\AccessControlException;
use iutnc\nrv\repository\NrvRepository;

class CancelEveningAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        try {
            if (!Authz::checkRole(Authz::STAFF)) {
                throw new AccessControlException("Error 403 Permission refusée : seul un organisateur peut annuler une soirée.");
            }

            $eveningUuid = $_POST["id"] ?? null;
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