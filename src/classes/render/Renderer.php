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
     * @param bool $isPrivate vrai si la playlist appartient à un user
     * @return string le rendu
     */
    public function render(int $selector, $index = null): string;

}