<?php

namespace iutnc\nrv\action\program_navigation;


use Exception;
use iutnc\nrv\action\Action;
use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\render\ArrayRenderer;
use iutnc\nrv\render\Renderer;
use iutnc\nrv\repository\NrvRepository;

/**
 * Affichage de la liste des spectacles(titre, date, horaire, image)
 */
class DisplayAllShowsAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executeGet(): string
    {
        $_SESSION['previous'] = $_SERVER['REQUEST_URI'];
        $repository = NrvRepository::getInstance();
        $shows = $repository->findAllShows();

        // GESTION DES STYLES
        $styles = $repository->findAllStylesRAW();
        $style_options = "";
        foreach ($styles as $style) {
            $style_options .= "<a href='index.php?actions=FILTRESTYLE&id={$style['style_id']}' class='filter-btn'>{$style['style_name']}</a>";
        }

        // GESTION DES LOCATIONS
        $locations = $repository->findAllLocations();
        $location_options = "";
        foreach ($locations as $location) {
            $location = unserialize($location);
            $location_options .= "<a href='index.php?actions=FILTRELOC&id={$location->id}' class='filter-btn'>{$location->name}</a>";
        }

        // GESTION DES DATES
        $dates_options = "";
        for ($i = 1; $i <= 15; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $dates_options .= "<a href='index.php?actions=FILTREDATE&id=2025-07-{$day}' class='filter-btn'>{$day} juillet</a>";
        }

        $html = <<<HTML
                <div class="d-flex align-items-center justify-content-center my-4 px-4">
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                    <h1 class="text-center mx-3">RECHERCHER</h1>
                    <div class="mx-2 title-border" style="background-color: #2ec5b6"></div>
                </div>
                
                <div class="d-flex justify-content-center gap-2">
                    <p>Filtrez vos résultats par</p>
                </div>
                

                <div class="d-flex justify-content-center gap-2">
                <button class="filter-btn" onclick="showOptions('style')">STYLE</button>
                <button class="filter-btn" onclick="showOptions('date')">DATE</button>
                <button class="filter-btn" onclick="showOptions('location')">LIEU</button>
                </div>
                
                <div class="d-flex justify-content-center gap-2 my-4" id="filter-options">
                <div id="style" class="filter-options" style="display: none;">
                    {$style_options}
                </div>
                <div id="date" class="filter-options" style="display: none;">
                    {$dates_options}
                </div>
                <div id="location" class="filter-options" style="display: none;">
                    {$location_options}
                </div> 
                </div>
                
                <script src="src/js/filter.js"></script>
HTML;
        $user = AuthnProvider::getSignedInUser();
        $boutonAjouter = "";
        if ($user["role"] >= Authz::STAFF) {
            $boutonAjouter = <<<HTML
            <a href="?action=add-show" class="btn btn-primary m-5">Ajouter un Spectacle</a>
            HTML;
        }

        $html .= ArrayRenderer::render($shows, Renderer::COMPACT, true). $boutonAjouter;

        return $html;
    }
}