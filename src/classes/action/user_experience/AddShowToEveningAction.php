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
        $title = filter_var(trim($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_SPECIAL_CHARS);
        $startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_SPECIAL_CHARS);
        $duration = filter_var($_POST['duration'], FILTER_SANITIZE_NUMBER_INT, ["options" => ["min_range" => 1]]);
        $style = filter_var(trim($_POST['style']), FILTER_SANITIZE_SPECIAL_CHARS);
        $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
        $uuid = Uuid::uuid4();
        $show = new Show($uuid, $title, $description, new \DateTime($startDate), intval($duration), $style, $url);
        $repo->createShow($show);
        return "";
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
// Définition des options pour les comboboxes
        $soirees = ["Soirée Étudiante", "Soirée Disco", "Soirée Années 80"];
        $spectacles = ["Spectacle de Magie", "Spectacle de Danse", "Concert de Jazz"];

// Génère les options pour la combobox des soirées
        $soireeOptions = '';
        foreach ($soirees as $soiree) {
            $soireeOptions .= "<option value=\"" . htmlspecialchars($soiree) . "\">" . htmlspecialchars($soiree) . "</option>\n";
        }

// Génère les options pour la combobox des spectacles
        $spectacleOptions = '';
        foreach ($spectacles as $spectacle) {
            $spectacleOptions .= "<option value=\"" . htmlspecialchars($spectacle) . "\">" . htmlspecialchars($spectacle) . "</option>\n";
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
  <form method="post" action="traitement.php">
    <!-- Combobox pour le choix de la soirée -->
    <div class="mb-3">
      <label for="soireeSelect" class="form-label">Choisissez une Soirée</label>
      <select id="soireeSelect" name="soiree" class="form-select">
        $soireeOptions
      </select>
    </div>

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

        HTML;

    }
}