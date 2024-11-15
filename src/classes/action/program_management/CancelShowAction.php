<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\exception\AccessControlException;
use iutnc\nrv\repository\NrvRepository;

/**
 * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué
 * comme annulé,
 */
class CancelShowAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        try {
            if (!Authz::checkRole(50)) {
                throw new AccessControlException("Permission refusée : seul un organisateur peut annuler un spectacle.");
            }

            $showUuid = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

            if (!$showUuid) {
                throw new Exception("Identifiant du spectacle non fourni");
            }
            $repo = NrvRepository::getInstance();
            $repo->cancelShow($showUuid);

            return "Le spectacle a bien été annulé.";
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
        // TODO: Implement post() method.
    }
}