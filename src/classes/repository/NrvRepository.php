<?php

namespace iutnc\nrv\repository;
use PDO;

class NrvRepository
{
    private PDO $pdo;
    private static ?NrvRepository $instance = null;
    private static array $configuration = [];

    /**
     * Constructeur de NrvRepository.
     *
     * @param array $configuration Configuration pour la connexion à la base de données.
     */
    private function __construct(array $configuration)
    {
        $this->pdo = new PDO($configuration['dsn'], $configuration["user"], $configuration['pass'],
            [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        $this->pdo->prepare('SET NAMES \'UTF8\'')->execute();
    }

    /**
     * Définit la configuration de la base de données.
     *
     * @param string $fichier Chemin vers le fichier de configuration.
     * @throws Exception Si le fichier de configuration ne peut pas être lu.
     */
    private static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new Exception("Error reading configuration file");
        }
        $port = '';  // pour ryan
        if (isset($conf['port'])) {
            $port = ';port=' . $conf['port'];
        }
        $dsn = "{$conf['driver']}:host={$conf['host']} . $port . ;dbname={$conf['database']}";
        self::$configuration = ['dsn' => $dsn, 'user' => $conf['username'], 'pass' => $conf['password']];
    }

    /**
     * Obtient l'instance unique de NrvRepository.
     * @return NrvRepository
     */
    public static function getInstance(): ?NrvRepository
    {
        if (is_null(self::$instance)) {
            self::setConfig("Config.db.ini");
            self::$instance = new NrvRepository(self::$configuration);
        }
        return self::$instance;
    }

// 1. Affichage de la liste des spectacles
    function findAllShows(): array
    {
        // TODO
    }

// 2. Filtrage de la liste des spectacles par date
    function findShowsByDate(string $date): array
    {
        // TODO
    }

// 3. Filtrage de la liste des spectacles par style de musique
    function findShowsByStyle(string $style): array
    {
        // TODO
    }

// 4. Filtrage de la liste des spectacles par lieu
    function findShowsByLocation(string $location): array
    {
        // TODO
    }

// 5. Affichage détaillé d’un spectacle
    function findShowDetails(int $showId): array
    {
        // TODO
    }

// 6. Affichage du détail d’une soirée
    function findEventDetails(int $eventId): array
    {
        // TODO
    }

// 7. Affichage du détail de la soirée correspondante en cliquant sur un spectacle
    function findEventByShow(int $showId): array
    {
        // TODO
    }

// 8. Accès aux spectacles du même lieu en lien avec un spectacle
    function findShowsBySameLocation(int $showId): array
    {
        // TODO
    }

// 9. Accès aux spectacles du même style en lien avec un spectacle
    function findShowsBySameStyle(int $showId): array
    {
        // TODO
    }

// 10. Accès aux spectacles à la même date en lien avec un spectacle
    function findShowsBySameDate(int $showId): array
    {
        // TODO
    }

// 11. Ajouter un spectacle dans la liste de préférences
    function addShowToPreferences(int $showId): bool
    {
        // TODO
    }

// 12. Afficher la liste de préférences
    function findUserPreferences(): array
    {
        // TODO
    }

    // 13. S'authentifier
    function authenticateUser(string $username, string $password): bool
    {
        // TODO
    }

// 14. Créer un spectacle : saisir les données et les valider
    function createShow(array $showData): int
    {
        // TODO : retourne l'ID du spectacle créé ?
    }

// 15. Créer une soirée : saisir les données et les valider
    function createEvent(array $eventData): int
    {
        // TODO : retourne l'ID de la soirée créée ?
    }

// 16. Ajouter un spectacle à une soirée
    function addShowToEvent(int $showId, int $eventId): bool
    {
        // TODO
    }

// 17. Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué comme annulé
    function cancelShow(int $showId): bool
    {
        // TODO
    }

// 18. Modifier un spectacle existant
    function updateShow(int $showId, array $newShowData): bool
    {
        // TODO
    }

// 19. Modifier les spectacles d’une soirée existante
    function updateEventShows(int $eventId, array $showIds): bool
    {
        // TODO
    }

    // Créer un compte staff : créer un compte utilisateur permettant de gérer le programme
    function createStaffAccount(string $username, string $password, array $staffData): int
    {
        // TODO : retourne l'ID du compte staff créé ?
    }


}