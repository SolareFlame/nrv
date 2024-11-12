<?php

namespace iutnc\nrv\action\program_management;

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
     */
    public function executePost(): string
    {
        if(isset($_POST['name']) &&
            isset($_POST['theme']) &&
            isset($_POST['date']) &&
            isset($_POST['location']) &&
            isset($_POST['description']) &&
            isset($_POST['price'])) {

            $instance = NrvRepository::getInstance();
            $uuid = Uuid::uuid4();

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
     */
    public function executeGet(): string
    {
        $instance = NrvRepository::getInstance();
        $locations = $instance->findAllLocations();

        $form = <<<HTML
    <form method="post">
    <label for="name">Nom de la soirée</label>
    <input type="text" name="name" id="name" required>
    
    <label for="theme">Theme de la soirée</label>
    <input type="text" name="theme" id="theme" required>
    
    <label for="date">Date de la soirée</label>
    <input type="date" name="date" id="date" required>
        
    <label for="location">Lieu de la soirée</label>
    <select name="location" id="location" required>
HTML;
        foreach ($locations as $location) {
            $location = unserialize($location);
            $form .= "<option value='{$location->id}'>{$location->name}</option>";
        }

        $form .= <<<HTML
    <label for="description">Description de la soirée</label>
    <textarea name="description" id="description" required></textarea>
    
    <label for="price">Prix de la soirée</label>
    <input type="number" name="price" id="price" required>
    
    <button type="submit">Créer la soirée</button>
HTML;
        return $form;
    }
}