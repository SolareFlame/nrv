<?php

namespace iutnc\nrv\render;

use iutnc\nrv\object\Artist;

class ArtistRenderer implements Renderer
{


    private Artist $artist;

    /**
     * @param Artist $artist
     */
    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }


    public function render(int $selector, $index = null): string
    {
        return <<<HTML
        <div class="container my-4">
          <div class="d-flex align-items-start">
            <img src="res/background/artist_default.jpg" alt="Cute cat" class="custom-image">
            <div class="text-content">
              <h5 class="fw-bold">{$this->artist->name}</h5>
              <p class="text-content">{$this->artist->description}</p>
            </div>
          </div>
        </div>
HTML;
    }
}