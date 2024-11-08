<?php

namespace iutnc\nrv\action\program_management;

use iutnc\nrv\action\Action;
use iutnc\nrv\repository\NrvRepository;

/**
 * Créer une soirée : saisir les données et les valider
 */
class CreateShowAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost()
    {
        if(isset($_POST['name']) &&
            isset($_POST['description']) &&
            isset($_POST['date']) &&
            isset($_POST['duration']) &&
            isset($_POST['style']) &&
            isset($_POST['url']) &&
            isset($_POST['artists[]'])) {

            $artists = [];
            foreach ($_POST['artists[]'] as $artist) {
                $artists[] = NrvRepository::getInstance()->findArtistById($artist);
            }

            $instance = NrvRepository::getInstance();
            $uuid = Uuid::uuid4();

            $show = new Show(
                $uuid,
                $_POST['name'],
                $_POST['description'],
                $_POST['date'],
                $_POST['duration'],
                $instance->findStyleById($_POST['style']),
                $_POST['url'],
                $artists
            );
            $instance->createShow($show);

            return "Spetacle créé";
        } else {
            return "Erreur lors de la création du spectacle";
        }
    }

    /**
     * @inheritDoc
     */
    public function executeGet()
    {
        $instance = NrvRepository::getInstance();
        $styles = $instance->findAllStyles();
        $artists = $instance->findAllArtists();

        if(empty($styles)) {
            return "Aucun style de musique n'a été trouvé. Contactez un nancy.rock.vibration.sae@gmail.com pour contacter un administrateur.";
        }

        $form = <<<HTML
    <form method="post">
    <label for="name">Nom du spectacle</label>
    <input type="text" name="name" id="name" required>
    
    <label for="description">Description du spectacle</label>
    <textarea name="description" id="description" required></textarea>
    
    <label for="date">Date du spectacle </label>
    <input type="date" name="date" id="date" required>
        
    <label for="duration">Durée du spectacle</label>
    <input type="number" name="duration" id="duration" required>
    
    <label for="style">Style de musique</label>
    <select name="style" id="style" required>

HTML;
        foreach ($styles as $style) {
            $style = unserialize($style);
            $form .= "<option value='{$style->id}'>{$style->name}</option>";
        }
        $form .= <<<HTML

    <label for="url">Lien de la vidéo</label>
    <input type="url" name="url" id="url">
    
HTML;
        foreach ($artists as $artist) {
            $artist = unserialize($artist);
            $form .= "<input type='checkbox' name='artists[]' value='{$artist->id}'>{$artist->name}<br>";
        }
        $form .= <<<HTML
    <button type="submit">Créer la soirée</button>
HTML;
        return $form;
    }
}