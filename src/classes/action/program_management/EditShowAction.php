<?php

namespace iutnc\nrv\action\program_management;

use DateTime;
use iutnc\nrv\action\Action;
use iutnc\nrv\exception\RepositoryException;
use iutnc\nrv\object\User;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Modifier un spectacle existant
 */
class EditShowAction extends Action
{

    /**
     * @inheritDoc
     * @throws RepositoryException
     * @throws \DateMalformedStringException
     */
    public function executePost(): string
    {
        // Initialisation d'un tableau pour stocker les modifications
        $updates = [];
        $repo = NrvRepository::getInstance();
        $show = unserialize($_SESSION["show"]);
        $message = "";
        // Vérification et filtrage de chaque champ du formulaire seulement s'il est défini et non vide
        if (isset($_POST['title']) && !empty(trim($_POST['title']))) {
            $title = filter_var(trim($_POST['title']), FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                $repo->updateShowColumn($show->id, "title", $title);
            } catch (RepositoryException $e) {
                $message = $e->getMessage();
            }
            $updates['title'] = $title;
        }

        if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
            $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_SPECIAL_CHARS);
            $repo->updateShowColumn($show->id, "description", $description);
            $updates['description'] = $description;
        }

        if (!empty($_POST['startDate'])) {
            $startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_SPECIAL_CHARS);
            $dateObject = DateTime::createFromFormat('Y-m-d\TH:i', $startDate);
            if ($dateObject) {
                $repo->updateShowColumn($show->id, "date", $dateObject->format('Y-m-d H:i:s'));
                $updates['startDate'] = $dateObject->format('Y-m-d H:i:s');
            }
        }

        if (!empty($_POST['duration'])) {
            $duration = filter_var($_POST['duration'], FILTER_SANITIZE_NUMBER_INT, ["options" => ["min_range" => 1]]);
            if ($duration !== false) {
                $repo->updateShowColumn($show->id, "duration", $duration);
                $updates['duration'] = $duration;
            }
        }

        if (isset($_POST['style']) && !empty(trim($_POST['style']))) {
            $style = filter_var(trim($_POST['style']), FILTER_SANITIZE_SPECIAL_CHARS);
            $repo->updateShowColumn($show->id, "style", $style);
            $updates['style'] = $style;
        }

        if (!empty($_POST['url'])) {
            $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
            if ($url) {
                $repo->updateShowColumn($show->id, "url", $url);
                $updates['url'] = $url;
            }
        }

        // Si des champs sont à modifier
        if (!empty($updates)) {
            $info = implode(", ", array_keys($updates));
            $message = <<<HTML
                    <br>
                     <div class="alert alert-success" role="alert">
                        Les champs suivants ont été mis à jour : $info
                    </div>
                    HTML;
        } else {
            $message = "<p'>Aucun champ à mettre à jour.</p>";
        }
        return $this->executeGet() . $message;
    }

    /**
     * @inheritDoc
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    public function executeGet(): string
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];

        $repository = NrvRepository::getInstance();
        $id = filter_var($_GET['id'],FILTER_SANITIZE_SPECIAL_CHARS);
        $show = $repository->findShowById($id);

        $artists = $repository->findAllArtistsByShow($show->id);
        $show->setListeArtiste($artists);
        $displayShow = $show->getRender(Renderer::LONG);
        $_SESSION['show'] = serialize($show);
        $formModif = <<<HTML
        <br>
        <div class="form-container mt-100">
            <h2 class="text-center">Éditer un Show</h2>
            <form action="?action=edit-show&id={$id}" method="post" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                    <div class="invalid-feedback">Veuillez entrer un titre.</div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    <div class="invalid-feedback">Veuillez entrer une description.</div>
                </div>
                <div class="form-group">
                    <label for="startDate">Date de début</label>
                    <input type="datetime-local" class="form-control" id="startDate" name="startDate" required>
                    <div class="invalid-feedback">Veuillez entrer une date de début valide.</div>
                </div>
                <div class="form-group">
                    <label for="duration">Durée (minutes)</label>
                    <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                    <div class="invalid-feedback">Veuillez entrer une durée en minutes.</div>
                </div>
                <div class="form-group">
                    <label for="style">Style</label>
                    <input type="text" class="form-control" id="style" name="style" required>
                    <div class="invalid-feedback">Veuillez entrer un style.</div>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" class="form-control" id="url" name="url" required>
                    <div class="invalid-feedback">Veuillez entrer une URL valide.</div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
        
        HTML;
        return $displayShow . $formModif;
    }
}