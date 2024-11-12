<?php

namespace iutnc\nrv\render;

use iutnc\nrv\object\Evening;

class EveningRenderer extends DetailsRender
{
    public Evening $evening;

    function __construct(Evening $evening)
    {
        $this->evening = $evening;
    }

    public function renderCompact($index): string
    {
        return <<<HTML
<div>
{$this->evening->title} - {$this->evening->description}
</div>
HTML;

    }

    public function renderLong($index): string
    {
        $location = $this->evening->location;
        $renderEvening = <<< HTML
<div>
    <h2>{$this->evening->title}</h2>
    <p>Theme: {$this->evening->theme}</p>
    <p>Date: {$this->evening->date}</p>
    <p>Location: {$location->address}</p>
    <p>Description: {$this->evening->description}</p>
    <p>Price: {$this->evening->eveningPrice} €</p>
    <h3>Shows</h3>
HTML;
        foreach ($this->evening->shows as $show){
            $renderShow = new ShowRenderer($show);
            $shows = $renderShow->render(Renderer::LONG);
        }
        return <<<HTML
$renderEvening
$shows
</div>
HTML;;
    }
}