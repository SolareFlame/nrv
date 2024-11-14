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

    public function renderCompact($index): string
    {
        return "";
    }

    public function renderLong($index = null): string
    {
        $heures = (int)$this->show->duration % 59;
        $minutes = $this->show->duration - $heures * 60;
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

        $html = <<<HTML
<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <img src="res/background/show_default.jpg" alt="Show Image" class="show-image">
        </div>

        <div class="col-md-8 position-relative">
        
            <div class="position-absolute top-0 end-0 me-4 mt-3">
                {$heart}
                <a href="?action=edit-show&id={$id}" class="btn btn-sm btn-outline-primary ms-2">Edit</a>
            </div>
            
            <div class="show-long-render">
                <h2 class="show-long-render-title">{$this->show->title}</h2>
                
                <p><i class="fas fa-calendar-alt info-icon me-2"></i>{$this->show->startDate->format('d M Y \à H:i')}</p>
                <p><i class="fas fa-clock info-icon me-2"></i>{$heures}h{$minutes}</p>
                <p><i class="fas fa-star info-icon me-2"></i>%soirée%</p>
                <p><i class="fas fa-tags info-icon me-2"></i>{$this->show->style}</p>
                <p><i class="fas fa-comment info-icon me-2"></i>Description</p>

                <p>{$this->show->style}</p>
            </div>
        </div>
    </div>
    
    <div id="customCarousel" class="carousel slide my-5" data-bs-ride="carousel">
    <div class="carousel-inner">

    <div class="carousel-item active">
        <div class="carousel-box mx-auto p-4" style="background-color: #fff2e1; border-radius: 30px; width: 70%;">
            <p>DISPLAY ARTISTE A FAIRE (IMAGE, NOM, DESC) R.F Maquette</p>
        </div>
    </div>
HTML;

        foreach ($this->show->artists as $artist) {
            $html .= <<<HTML
        <div class="carousel-item active">
            <div class="carousel-box mx-auto p-4" style="background-color: #fff2e1; border-radius: 30px; width: 70%;">
HTML;
            // Render artist details here.
            $html .= <<<HTML
            </div>
        </div>
    HTML;
        }

        $html .= <<<HTML
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