<?php

namespace iutnc\nrv\render;
class ArrayRenderer
{
    private array $liste;

    public function __construct(array $liste)
    {
        $this->liste = $liste;
    }

    /**
     * @param int $option 1 for long, 2 for preview
     * @param bool $isSerial vrai si la liste d'options est sérialisée
     * @return string
     */
    public function render(int $option, bool $isSerial): string
    {
        $res = '';
        foreach ($this->liste as $entite) {
            $entite = $isSerial ? unserialize($entite) : $entite;
            $res .= $entite->getRenderer($option);
            $res .= "<br>";
        }

        return $res;
    }
}