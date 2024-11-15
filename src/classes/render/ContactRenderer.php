<?php

namespace iutnc\nrv\render;

class ContactRenderer
{
    public static function render(string $title, array $contactInfo): string
    {
        $html = "<div style='max-width: 800px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";

        $html .= "<h1 style='text-align: center; color: #009688; font-size: 2em; margin-bottom: 20px;'>{$title}</h1>";

        $html .= "<h2 style='text-align: center; color: #555;'>Informations de Contact</h2>";

        $html .= "<p style='font-size: 1.1em; color: #666; text-align: center;'>Email: <a href='mailto:{$contactInfo['email']}' style='color: #009688;'>{$contactInfo['email']}</a></p>";
        $html .= "<p style='font-size: 1.1em; color: #666; text-align: center;'>Téléphone: <a href='tel:{$contactInfo['phone']}' style='color: #009688;'>{$contactInfo['phone']}</a></p>";
        $html .= "<p style='font-size: 1.1em; color: #666; text-align: center;'>Adresse: {$contactInfo['address']}</p>";
        $html .= "<p style='font-size: 1.1em; color: #666; text-align: center;'>Heures d'ouverture: {$contactInfo['opening_hours']}</p>";

        if (!empty($contactInfo['social_media'])) {
            $html .= "<h3 style='color: #555; text-align: center;'>Suivez-nous sur les réseaux sociaux</h3>";
            $html .= "<div class='social-icons' style='display: flex; justify-content: center; gap: 20px;'>";

            foreach ($contactInfo['social_media'] as $platform => $url) {
                $iconClass = "";
                switch ($platform) {
                    case 'Facebook':
                        $iconClass = 'bi bi-facebook';
                        break;
                    case 'TikTok':
                        $iconClass = 'bi bi-tiktok';
                        break;
                    case 'Instagram':
                        $iconClass = 'bi bi-instagram';
                        break;
                    case 'YouTube':
                        $iconClass = 'bi bi-youtube';
                        break;
                    case 'Twitter':
                        $iconClass = 'bi bi-twitter';
                        break;
                }

                if ($iconClass) {
                    $html .= "<a href='{$url}' target='_blank' class='social-icon' style='text-decoration: none;'>";
                    $html .= "<i class='{$iconClass}' style='font-size: 30px; color: #009688;'></i>";
                    $html .= "</a>";
                }
            }

            $html .= "</div>";
        }

        $html .= "</div>";

        return $html;
    }
}
