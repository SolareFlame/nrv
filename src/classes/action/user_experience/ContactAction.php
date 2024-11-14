<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\render\SimpleRenderer;

class ContactAction extends Action
{
    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        // Logique pour traiter un formulaire de contact, si nécessaire
        return "Votre message a été envoyé.";
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
        // Informations de contact
        $contactInfo = [
            'email' => 'contact@nrv.com',
            'phone' => '+33 6 48 17 90 43',
            'address' => '106 Grande Rue, 54000 Nancy',
            'opening_hours' => 'Lundi à Vendredi: 9h - 18h',
            'social_media' => [
                'Facebook' => 'https://www.facebook.com/nancyjazzpulsationsfestival/?locale=fr_FR',
                'TikTok' => 'https://www.tiktok.com/@nancyjazzpulsations',
                'Instagram' => 'https://www.instagram.com/nancyjazzpulsations/',
                'YouTube' => 'https://www.youtube.com/user/NancyJazzPulsations',
                'Twitter' => 'https://x.com/njpfestival',
            ],
        ];

        return SimpleRenderer::render('Comment nous contacter ?', $contactInfo);
    }
}