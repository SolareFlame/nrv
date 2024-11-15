<?php

namespace iutnc\nrv\action\user_experience;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\action\show_details\DisplayEveningDetailsAction;
use iutnc\nrv\repository\NrvRepository;

/**
 * Ajouter un spectacle à une soirée
 */
class AddShowToEveningAction extends Action
{

    /**
     * @inheritDoc
     * @throws \DateMalformedStringException
     * @throws Exception
     */
    public function executePost(): string
    {
        $repo = NrvRepository::getInstance();
        $spectacleId = filter_var($_POST['spectacle'], FILTER_SANITIZE_SPECIAL_CHARS);
        $show = $repo->findShowById($spectacleId);
        $evening = $repo->findEveningById($_GET['id']);
        $repo->addShowToEvening($show, $evening);
        $res = new DisplayEveningDetailsAction();
        return $res->executeGet();
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
// Définition des options pour les comboboxes
        $repo = NrvRepository::getInstance();
        try {
            $spectacles = $repo->findShowsNoAttributes();
        } catch (Exception) {
            return "Pas de spectacle à ajouter, veuillez en creer avant : <a href='?action=add-show' class='btn btn-primary m-5'>Ajouter un Spectacle</a>";
        }

        // Génère les options pour la combobox des spectacles
        $spectacleOptions = '';
        foreach ($spectacles as $spectacle) {
            $show = unserialize($spectacle);
            $spectacleOptions .= "<option value=\"" . "$show->id" . "\">" . htmlspecialchars($show->title) . "</option>\n";
        }

// Affichage du formulaire
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire de Choix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-4">
  <h2>Choix de Soirée et Spectacle</h2>
    <form method="post" action="?action=addShow2evening&id={$_GET['id']}">

    <!-- Combobox pour le choix du spectacle -->
    <div class="mb-3">

      <label for="spectacleSelect" class="form-label">Choisissez un Spectacle</label>
      <select id="spectacleSelect" name="spectacle" class="form-select">
        $spectacleOptions
      </select>
      
    </div>

    <button type="submit" class="btn btn-primary">Soumettre</button>
  </form>
</div>

</body>
</html>
HTML;

    }
}