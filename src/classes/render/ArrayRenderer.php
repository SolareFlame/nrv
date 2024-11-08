<?php

namespace iutnc\nrv\render;
class ArrayRenderer
{
    /**
     * @param array $liste liste d'entités à afficher
     * @param int $option 1 for long, 2 for preview
     * @param bool $isSerial vrai si la liste d'options est sérialisée
     * @return string le rendu
     */
    public static function render(array $liste, int $option, bool $isSerial): string
    {
        $res = '';
        foreach ($liste as $entite) {
            $entite = $isSerial ? unserialize($entite) : $entite;
            $res .= $entite->getRenderer($option);
            $res .= "<br>";
        }

        return $res;
    }
}