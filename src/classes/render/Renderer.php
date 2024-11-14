<?php

namespace iutnc\nrv\render;

/**
 * Interface Renderer.
 * Elle permet de représenter un rendu.
 */
interface Renderer
{
    const COMPACT = 1;
    const LONG = 2;

    /**
     * @param int $selector 1 for compact, 2 for long
     * @param null $index l'index de l'entité à afficher (facultatif)
     * @return string le rendu
     */
    public function render(int $selector, $index = null): string;


}