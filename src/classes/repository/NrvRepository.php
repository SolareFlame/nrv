<?php

namespace iutnc\nrv\repository;

class NrvRepository
{
    private \PDO $pdo;
    private static ?NrvRepository $instance = null;
    private static array $configuration = [];

    /**
     * Constructeur de NrvRepository.
     *
     * @param array $configuration Configuration pour la connexion à la base de données.
     */
    private function __construct(array $configuration)
    {
        $this->pdo = new \PDO($configuration['dsn'], $configuration["user"], $configuration['pass'], [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    /**
     * Définit la configuration de la base de données.
     *
     * @param string $fichier Chemin vers le fichier de configuration.
     * @throws \Exception Si le fichier de configuration ne peut pas être lu.
     */
    public static function setConfig(string $fichier)
    {
        $config = parse_ini_file($fichier);
        if ($config === false) {
            throw new \Exception("Erreur dans la lecture du fichier de configuration");
        }
        self::$configuration = $config;
    }

    /**
     * Obtient l'instance unique de NrvRepository.
     *
     * @return NrvRepository
     */
    public static function getInstance(): NrvRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new NrvRepository(self::$configuration);
        }
        return self::$instance;
    }

// 1. Affichage de la liste des spectacles
    function findAllShows() : array
    {
        // TODO
    }

// 2. Filtrage de la liste des spectacles par date
    function findShowsByDate(string $date) : array
    {
        // TODO
    }

// 3. Filtrage de la liste des spectacles par style de musique
    function findShowsByStyle(string $style) : array
    {
        // TODO
    }

// 4. Filtrage de la liste des spectacles par lieu
    function findShowsByLocation(string $location) : array
    {
        // TODO
    }

// 5. Affichage détaillé d’un spectacle
    function findShowDetails(int $showId) : array
    {
        // TODO
    }

// 6. Affichage du détail d’une soirée
    function findEventDetails(int $eventId) : array
    {
        // TODO
    }

// 7. Affichage du détail de la soirée correspondante en cliquant sur un spectacle
    function findEventByShow(int $showId) : array
    {
        // TODO
    }

// 8. Accès aux spectacles du même lieu en lien avec un spectacle
    function findShowsBySameLocation(int $showId) : array
    {
        // TODO
    }

// 9. Accès aux spectacles du même style en lien avec un spectacle
    function findShowsBySameStyle(int $showId) : array
    {
        // TODO
    }

// 10. Accès aux spectacles à la même date en lien avec un spectacle
    function findShowsBySameDate(int $showId) : array
    {
        // TODO
    }

// 11. Ajouter un spectacle dans la liste de préférences
    function addShowToPreferences(int $showId) : bool
    {
        // TODO
    }

// 12. Afficher la liste de préférences
    function findUserPreferences() : array
    {
        // TODO
    }


}