<?php

namespace iutnc\nrv\render;

class NewsRenderer
{
    public static function render(array $aboutInfo): string
    {
        $html = "<div style='max-width: 1300px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";

        // Titre principal
        $html .= "<h1 style='text-align: center; color: #009688; font-size: 2em; margin-bottom: 20px;'>À propos du projet</h1>";

        // Description du projet
        $html .= "<h2 style='color: #555;'>Description du projet</h2>";
        $html .= "<p style='font-size: 1.1em; color: #666;'>{$aboutInfo['projet']}</p>";

        // Langages et technologies
        $html .= "<br><h2 style='color: #555;'>Langages et technologies utilisés</h2>";
        $html .= "<p style='font-size: 1.1em; color: #666;'>{$aboutInfo['langages']}</p>";

        // Membres de l'équipe
        $html .= "<br><h2 style='color: #555;'>L'équipe</h2>";
        $html .= "<ul style='font-size: 1.1em; color: #666;'>";
        foreach ($aboutInfo['membre'] as $membre) {
            $html .= $membre;
        }
        $html .= "</ul>";

        // Lien GitHub
        $html .= "<h2 style='color: #555;'>Les liens du projet</h2>";
        $html .= "<p style='font-size: 1.1em; color: #666;'><a href='{$aboutInfo['github']}' target='_blank' style='color: #009688;'>Accéder au repository GitHub</a>, <br></p>";
        $html .= "<p style='font-size: 1.1em; color: #666;'><a href='{$aboutInfo['rapport']}' target='_blank' style='color: #009688;'>Accéder au rapport du projet</a>, <br></p>";
        $html .= "<p style='font-size: 1.1em; color: #666;'><a href='{$aboutInfo['modele']}' target='_blank' style='color: #009688;'>Accéder au modèle du projet réalisé sur Figma</a>, </p>";

        // Défis rencontrés
        $html .= "<br><h2 style='color: #555;'>Défis rencontrés</h2>";
        $html .= "<p style='font-size: 1.1em; color: #666;'>{$aboutInfo['defis']}</p>";

        // Fin du container
        $html .= "</div>";

        return $html;
    }
}
