<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\exception\RepositoryException;
use iutnc\nrv\object\Show;
use iutnc\nrv\repository\NrvRepository;
use Ramsey\Uuid\Uuid;

/**
 * Ajouter un spectacle à une soirée
 */
class AddShowToEveningAction extends Action
{

    /**
     * @inheritDoc
     * @throws \DateMalformedStringException
     */
    public function executePost(): string
    {
        $repo = NrvRepository::getInstance();
        $spectacleId = $_POST['spectacle'];
        $show = $repo->findShowById($spectacleId);
        $evening = $repo->findEveningById($_GET['id']);
        $repo->addShowToEvening($show, $evening);
        return "L'opération est un succes";
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
        } catch (\Exception $e){
           return "Pas de spectacle à ajouter, veuillez en creer avant. : " . <<<HTML
  <a href="?action=add-show" class="btn btn-primary m-5">Ajouter un Spectacle</a>
HTML;
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