<?php

namespace iutnc\nrv\action\program_management;

use DateTime;
use Error;
use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\object\Show;
use iutnc\nrv\repository\NrvRepository;
use Ramsey\Uuid\Uuid;

/**
 * Créer une soirée : saisir les données et les valider
 */
class CreateShowAction extends Action
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

        $uuid = Uuid::uuid4();

        $show = new Show(
            $uuid,
            $_POST['titre'],
            $_POST['description'],
            new DateTime($_POST['date']),
            $_POST['duree'],
            $_POST['style'],
            $_POST['url'],
        );

        foreach ($_POST['artists'] as $artist) {
            $show->ajouterArtiste(NrvRepository::getInstance()->findArtistById($artist));
        }

        NrvRepository::getInstance()->createShow($show);

        return "Le spectacle a bien été créée";
    }

    /**
     * @throws Exception
     */
    public function executeGet(): string
    {
        $inst = NrvRepository::getInstance();
        $styles = $inst->findAllStylesRAW();
        $artists = $inst->findAllArtistsID_Name();
        $uuid_TEST = Uuid::uuid4();

        $style_options = "";
        foreach ($styles as $style) {
            $style_options .= "<option value='{$style['style_id']}'>{$style['style_name']}</option>";
        }

        $artists_options = "";
        foreach ($artists as $artist) {
            $artists_options .= <<<HTML
                            <label>
                                 <input type='checkbox' name='artists[]' value='{$artist['artist_uuid']}'>
                                    {$artist['artist_name']}<br>
                            </label><br>
                            HTML;
        }

        return <<<HTML
            <div class="content_form">
                <h4>Créer un spectacle</h4> <br>
                {$uuid_TEST}
                <form method="post">
                <label for="titre">Titre du spectacle</label>
                <input type="text" name="titre" id="titre" required><br><br>
                
                <label for="description">Description du spectacle</label>
                <textarea name="description" id="description" required></textarea><br><br>
                
                <label for="date">Date du spectacle </label>
                <input type="datetime-local" name="date" id="date" required><br><br>
                    
                <label for="duree">Durée du spectacle</label>
                <input type="number" name="duree" id="duree" required><br><br>
                
                <label for="style">Style de musique</label>
                <select name="style" id="style" required>
                    $style_options
                </select> <br><br>
                
                <p>Artistes participants :</p>
                $artists_options <br>
                
                <label for="url">Lien de la vidéo</label>
                <input type="url" name="url" id="url"><br><br>

                <button type="submit">Créer le spectacle</button>
                </form>
            </div>
            HTML;

    }

    /**
     * Fonction permettant de vérifier que les données sont bien renseignées et de les nettoyer
     * @return void
     * @throws Exception si une donnée n'est pas renseignée
     */
    public function sanitize(): void
    {
        $_POST['style'] = !empty($_POST['style']) ? filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Error("Style non renseigné");

        if (!NrvRepository::getInstance()->VerifExistStyle($_POST['style'])){
            throw new Error("Style non reconnu");
        }

        var_dump($_POST['artists']);
        $_POST['artists'] = !empty($_POST['artists']) ? $_POST['artists'] : throw new Exception("Artiste non renseigné");
        $_POST['date'] = !empty($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Date non renseignée");
        $_POST['titre'] = !empty($_POST['titre']) ? filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Titre non renseigné");
        $_POST['duree'] = !empty($_POST['duree']) ? filter_var($_POST['duree'],  FILTER_SANITIZE_NUMBER_FLOAT) : throw new Exception("Durée non renseigné");
        $_POST['url'] = !empty($_POST['url']) ? filter_var($_POST['url'], FILTER_SANITIZE_URL) : throw new Exception("URL non renseignée");
        $_POST['description'] = !empty($_POST['description']) ? filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS) : throw new Exception("Description non renseignée");

        if (empty($_POST['artists']))
            throw new Exception("Aucun artiste n'a été sélectionné");

        foreach ($_POST['artists'] as $artist) {
            $artist = filter_var($artist, FILTER_SANITIZE_SPECIAL_CHARS);
            if (!NrvRepository::getInstance()->VerifArtistById($artist))
                throw new Error("Artiste non reconnu");
        }
    }
}