<?php

namespace iutnc\nrv\render;
use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\auth\Authz;
use iutnc\nrv\object\User;

class ArrayRenderer
{
    /**
     * @param array $liste liste d'entités à afficher
     * @param int $option 1 for compact, 2 for long
     * @param bool $isSerial vrai si la liste d'options est sérialisée
     * @return string le rendu
     */
    public static function render(array $liste, int $option, bool $isSerial): string
    {
        $res = '';
        if ($option == 1) {
            $res = '<div class="container"><div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">';
        }

        foreach ($liste as $entite) {

            if ($option == 1) {
                $res .= '<div class="col">';
            }

            $entite = $isSerial ? unserialize($entite) : $entite;
            $res .= $entite->getRender($option);

            if ($option == 1) {
                $res .= '</div>';
            }

        }

        if ($option == 1) {
            $res .= '</div></div>';
        }

        return $res;
    }
}