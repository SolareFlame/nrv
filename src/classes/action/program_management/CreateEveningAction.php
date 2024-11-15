<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\action\show_details\DisplayEveningDetailsAction;
use iutnc\nrv\repository\NrvRepository;
use iutnc\nrv\object\Evening;
use Ramsey\Uuid\Uuid;

/**
 * Créer une soirée : saisir les données et les valider
 */
class CreateEveningAction extends Action
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executePost(): string
    {
        try {
            $this->sanitize();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $instance = NrvRepository::getInstance();
        $uuid = Uuid::uuid4();

        //GESTION DES IMAGES
        $directory = "res/images/evenings/";
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        $destination = "$directory/$uuid.$extension";
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            throw new Exception("Échec du déplacement de l'image");
        }

        //GESTION BD
        $evening = new Evening(
            $uuid,
            $_POST['name'],
            $_POST['theme'],
            $_POST['date'],
            $instance->findLocationById($_POST['location']),
            $_POST['description'],
            $_POST['price']
        );
        $instance->createEvening($evening);

        $res = new DisplayEveningDetailsAction();
        $_GET['id'] = $uuid;
        return $res->executeGet();
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    public
    function executeGet(): string
    {
        $instance = NrvRepository::getInstance();
        $locations = $instance->findAllLocations();

        $form = <<<HTML
<form method="post" enctype="multipart/form-data" class="p-4 rounded shadow-sm" style="background-color: #f8f9fa; max-width: 600px; margin: auto; border-radius: 8px;">
    <div class="mb-3">
        <label for="name" class="form-label">Nom de la soirée</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Entrez le nom" required>
    </div>
    
    <div class="mb-3">
        <label for="theme" class="form-label">Thème de la soirée</label>
        <input type="text" name="theme" id="theme" class="form-control" placeholder="Entrez le thème" required>
    </div>
    
    <div class="mb-3">
        <label for="date" class="form-label">Date de la soirée</label>
        <input type="date" name="date" id="date" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label for="location" class="form-label">Lieu de la soirée</label>
        <select name="location" id="location" class="form-select" required>
            <option value="">Sélectionnez un lieu</option>
HTML;
        foreach ($locations as $location) {
            $location = unserialize($location);
            $form .= "<option value='{$location->id}'>{$location->name}</option>";
        }

        $form .= <<<HTML
        </select>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Description de la soirée</label>
        <textarea name="description" id="description" class="form-control" placeholder="Entrez la description" required></textarea>
    </div>
    
    <div class="mb-3">
        <label for="price" class="form-label">Prix de la soirée</label>
        <input type="number" name="price" id="price" class="form-control" placeholder="Entrez le prix" required>
    </div>
    
    <label for="image">Image du spectacle</label>
    <input type="file" name="image" id="image" accept="image/*" required><br><br>
    
    <button type="submit" class="btn btn-primary w-100" style="background-color: #007bff; border-color: #007bff;">Créer la soirée</button>
</form>

HTML;
        return $form;
    }

    /**
     * Fonction permettant de vérifier que les données sont bien renseignées et de les nettoyer
     * @return void
     * @throws Exception si une donnée n'est pas renseignée
     */
    public
    function sanitize(): void
    {
        $_POST['name'] = !empty($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Aucun nom renseigné");
        $_POST['theme'] = !empty($_POST['theme']) ? filter_var($_POST['theme'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("theme non renseignée");
        $_POST['date'] = !empty($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("date non renseigné");
        $_POST['price'] = !empty($_POST['price']) ? filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_ALLOW_FRACTION) : throw new Exception("Prix non renseigné");
        $_POST['location'] = !empty($_POST['location']) ? filter_var($_POST['location'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Localisation non renseignée");
        $_POST['description'] = !empty($_POST['description']) ? filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Description non renseignée");

        //VERIFICATION DE L'IMAGE
        $_POST['image']['name'] = filter_var($_FILES['image']['name'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors de l'upload de l'image");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Le fichier uploadé n'est pas un type d'image valide (JPEG, PNG, GIF uniquement)");
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception("Extension de fichier non autorisée");
        }

        $maxFileSize = 15 * 1024 * 1024; // 15 mo ici
        if ($_FILES['image']['size'] > $maxFileSize) {
            throw new Exception("L'image dépasse la taille maximale autorisée de 15 Mo");
        }
    }
}