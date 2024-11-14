<?php

namespace iutnc\nrv\render;

use iutnc\nrv\object\Show;

class ShowEditRenderer extends DetailsRender
{
    private Show $show;

    public function __construct(Show $sh)
    {
        $this->show = $sh;
    }

    // À n'appeler qu'avec un ArrayRenderer ou dans un div row pour un affichage correct
    public function renderCompact($index = null): string
    {
        $id = $this->show->id;

        // Check if the show is in the user's favorites
        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }

        $heart = !in_array($id, $_SESSION['favorites'])
            ? "<a href='?action=addShow2Fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_void.png' alt='not liked'></a>"
            : "<a href='?action=delShow2fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_full.png' alt='liked'></a>";


        $extensions = ['jpg', 'gif', 'png'];
        $img = "res/background/show_default.jpg";

        foreach ($extensions as $ext) {
            $filePath = "res/images/shows/{$this->show->id}.$ext";
            if (file_exists($filePath)) {
                $img = $filePath;
                break;
            }
        }


        return <<<HTML
<div class="col">
    <div class="card bg-dark text-light hover-effect" style="border-radius: 30px">
        <div class="position-relative" style="height: 0; padding-top: 100%; overflow: hidden; border-radius: 30px;">
            <a href="?action=showDetails&id={$this->show->id}" class="text-decoration-none">
                <div class="card-img" style="background-image: url('{$img}');"></div>
            </a>

            <div class="position-absolute top-0 start-0 p-2" style="z-index: 2;">
                <div class="text-start">
                    <p class="mb-0">{$this->show->startDate->format('d M Y')}</p>
                    <p class="mb-0">{$this->show->startDate->format('H:i')}</p>
                </div>
            </div>

            <div class="position-absolute top-0 end-0 p-2" style="z-index: 2;">
                {$heart}
            </div>
        </div>
        <a href="?action=showDetails&id={$this->show->id}" class="text-reset text-decoration-none">
            <div class="card-body text-center" style="position: absolute; bottom: 0; width: 100%; padding: 10px;">
                <h5 class="card-title">{$this->show->title}</h5>
                <p class="card-text">{$this->show->description}</p>
            </div>
        </a>
    </div>
</div>


HTML;
    }

    public function renderLong($index = null): string
    {
        if ($this->show->duration < 59) {
            $heures = 0;
            $minutes = $this->show->duration;
        } else {
            $heures = (int)$this->show->duration % 59;
            $minutes = $this->show->duration - $heures * 60;
        }
        if ($minutes == 0) {
            $minutes = "00";
        }

        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }

        $id = $this->show->id;

        $heart = !in_array($id, $_SESSION['favorites'])
            ? "<a href='?action=addShow2Fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_void.png' alt='not liked'></a>"
            : "<a href='?action=delShow2fav&id={$id}' class='favorite-icon'><img src='res/icons/heart_full.png' alt='liked'></a>";

        $carrousel = "";
        foreach ($this->show->artists as $artist) {
            $rendererArtist = new ArtistRenderer($artist);
            $render = $rendererArtist->render(Renderer::LONG);
            $carrousel .= <<<HTML
                    <div class="carousel-item active">
                        <div class="carousel-box mx-auto p-4" style="background-color: #fff2e1; border-radius: 30px; width: 70%;">         
                        $render                       
                        </div>
                    </div>
                HTML;
        }

        $extensions = ['jpg', 'gif', 'png'];
        $img = "res/background/show_default.jpg";

        foreach ($extensions as $ext) {
            $filePath = "res/images/shows/{$this->show->id}.$ext";
            if (file_exists($filePath)) {
                $img = $filePath;
                break;
            }
        }


        $html = <<<HTML
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-4">
                        <img src={$img} alt="Show Image" class="show-image">
                    </div>
            
                    <div class="col-md-8 position-relative">
                    
                        <div class="position-absolute top-0 end-0 me-4 mt-3">
                        <a href="?action=edit-show&id={$id}" class="btn btn-sm btn-outline-primary ms-2">Edit</a>
                            {$heart}
                        </div>
                        
                        <div class="show-long-render">
                            <h2 class="show-long-render-title">{$this->show->title}</h2>
                            
                            <p><i class="fas fa-calendar-alt info-icon me-2"></i>{$this->show->startDate->format('d M Y \à H:i')}</p>
                            <p><i class="fas fa-clock info-icon me-2"></i>{$heures}h{$minutes}</p>
                            <p><i class="fas fa-star info-icon me-2"></i>%soirée%</p>
                            <p><i class="fas fa-tags info-icon me-2"></i>{$this->show->style}</p>
                            <p><i class="fas fa-comment info-icon me-2"></i>Description</p>
            
                            <p>{$this->show->description}</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-center my-4 px-4">
                    <div class="mx-2 title-border" style="background-color: #FF9F1C"></div>
                    <h1 class="text-center mx-3">ARTISTES</h1>
                    <div class="mx-2 title-border" style="background-color: #FF9F1C"></div>
                </div>
                
                
                <div id="customCarousel" class="carousel slide my-5" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        {$carrousel}
                    </div>
                    <a class="carousel-control-prev custom-carousel-control" href="#customCarousel" role="button" data-bs-slide="prev">
                        <img src="res/icons/left-arrow.png" alt="Previous" class="carousel-control-prev-icon" aria-hidden="true">
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next custom-carousel-control" href="#customCarousel" role="button" data-bs-slide="next">
                        <img src="res/icons/right-arrow.png" alt="Next" class="carousel-control-next-icon" aria-hidden="true">
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            
            </div>
            HTML;

        return $html;
    }

}