<?php

namespace iutnc\nrv\action\program_management;

use Exception;
use iutnc\nrv\action\Action;
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
        if (isset($_POST['name']) &&
            isset($_POST['theme']) &&
            isset($_POST['date']) &&
            isset($_POST['location']) &&
            isset($_POST['description']) &&
            isset($_POST['price'])) {

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

            return "Soirée créée";
        } else {
            return "Erreur lors de la création de la soirée";
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executeGet(): string
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
}