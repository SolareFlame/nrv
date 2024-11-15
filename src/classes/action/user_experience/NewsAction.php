<?php

namespace iutnc\nrv\action\user_experience;

use iutnc\nrv\action\Action;
use iutnc\nrv\render\NewsRenderer;

class NewsAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {
        $aPropos = [
            'projet' => 'NRV (Nancy Rock Vibration) est une plateforme interactive réalisé dans le cadre
            de la SAE: Développer une application web sécurisée en PHP. L\'application web présente un festival
            de rock permettant de voir toutes les informations utiles sur les soirées, les spectacles, et
            tous les évènements proposés par l\'association.',
            'langages' => 'PHP, MySQL, JavaScript, HTML/CSS ainsi que d\'autres outils tels que Bootstrap',
            'membre' => [
                '<li>Zacharie Heuertz: Responsable de la base de données, du design du site et de la gestion de la conception.<br></li>',
                '<li>Valentin Knorst: Développeur principal des actions, renderers, vérifications de la qualité de code, et de la moitié du repository.<br></li>',
                '<li>Ryan Korban: Développeur principal des actions, des renders et des objets...<br></li>',
                '<li>Nathan Eyer: Développeur principal du repository, de quelques Actions et des documents de rendus.<br></li>',
                '<li>Mathieu Baudoin: Développeur principal de l\'authentification et création de diagramme de classe pour orienter la conception<br></li>',
            ],
            'github' => 'https://github.com/SolareFlame/nrv',
            'rapport' => 'https://docs.google.com/document/d/1Fe9P6jMfbPgu8Lw17rwUlW4qZHi4TBuGmFblQI0LpXQ/edit?usp=sharing',
            'modele' => 'https://www.figma.com/design/QFygDixtX64ms0Lx8thDSF/NRV-SAE?node-id=0-1&t=EUUUPDU7tQyVR02c-1',
            'defis' => 'Un des principal défi a été de se coordonner en équipe, surtout au niveau de la programmation. Il y a eu quelques
            tensions au sein de l\'équipe, compensé par notre volonté de réaliser un projet complet. Le projet a demandé beaucoup de temps 
            pendant les heures alloués dans notre emploi du temps, mais aussi en dehors. Le temps était compté et nous cherchions à 
            rendre une application web fonctionnelle et visuellement attrayante. Nous sommes finalement satisfait du résultat de cette SAE.',
        ];
        return NewsRenderer::render($aPropos);
    }
}