<?php

namespace iutnc\nrv\repository;
use Exception;
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
        $dsn = "{$conf['driver']}:host={$conf['host']}" . " $port " . ";dbname={$conf['database']}";
        echo $dsn;
        self::$configuration = ['dsn' => $dsn, 'user' => $conf['username'], 'pass' => $conf['password']];
    }

    /**
     * Obtient l'instance unique de NrvRepository.
     * @return NrvRepository
     * @throws Exception
     */
    public static function getInstance(): ?NrvRepository
    {
        if (is_null(self::$instance)) {
            self::setConfig("Config.db.ini");
            self::$instance = new NrvRepository(self::$configuration);
        }
        return self::$instance;
    }

    /**
     * Affichage de la liste des spectacles
     * @return array
     */
    function findAllShows() : array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_time, show_duration, show_style, show_url from show";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayShows($stmt);
    }

    /**
     * Filtrage de la liste des spectacles par date
     * @param string $date
     * @return array
     */
    function findShowsByDate(string $date) : array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_time, 
       show_duration, show_style, show_url from show where DATE(show_start_time) = :date";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['date' => $date]);

        return $this->createArrayShows($stmt);
    }

    /**
     * Filtrage de la liste des spectacles par style de musique
     * @param string $style
     * @return array
     */
    function findShowsByStyle(string $style) : array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_time, 
       show_duration, show_style, show_url from show where DATE(show_style) = :style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['style' => $style]);

        return $this->createArrayShows($stmt);
    }

    /**
     * Filtrage de la liste des spectacles par lieu
     * @param string $location
     * @return array
     */
    function findShowsByLocation(string $location) : array
    {
        $query = "Select evening_location, show_uuid, show_title, show_description, show_start_time, 
            show_duration, show_style, show_url 
            from show INNER JOIN evening2show es ON s.show_uuid = es.show_uuid
            INNER JOIN evening e ON es.evening_uuid = e.evening_uuid WHERE e.evening_location = :location";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['location' => $location]);

        return $this->createArrayShows($stmt);
    }

    /**
     * Affichage détaillé d’un spectacle
     * @param int $uuid
     * @return Show
     */
    function findShowDetails(int $uuid) : Show
    {
        $query = "Select show_uuid, show_title, show_description, show_start_time, 
       show_duration, show_style, show_url from show where show_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        return $this->createArrayShows($stmt)[0];
    }

    /**
     * Affichage du détail d’une soirée
     * @param int $eventId
     * @return array
     */
    function findEveningDetails(int $eventId) : array
    {
        // TODO
    }

    /**
     * Affichage du détail de la soirée correspondante en cliquant sur un spectacle
     * @param int $showId
     * @return array
     */
    function findEveningByShow(int $showId) : array
    {
        // TODO
    }

    /**
     * Accès aux spectacles du même lieu en lien avec un spectacle
     * @param int $showId
     * @return array
     */
    function findShowsBySameLocation(int $showId) : array
    {
        // TODO
    }

    /**
     * Accès aux spectacles du même style en lien avec un spectacle
     * @param int $showId
     * @return array
     */
    function findShowsBySameStyle(int $showId) : array
    {
        // TODO
    }

    /**
     * Accès aux spectacles à la même date en lien avec un spectacle
     * @param int $showId
     * @return array
     */
    function findShowsBySameDate(int $showId) : array
    {
        // TODO
    }

    /**
     * Ajouter un spectacle dans la liste de préférences
     * @param int $showId
     * @return bool
     */
    function addShowToPreferences(int $showId) : bool
    {
        // TODO
    }

    /**
     * Afficher la liste de préférences
     * @return array
     */
    function findUserPreferences() : array
    {
        // TODO
    }

    /**
     * S'authentifier
     * @param string $username
     * @param string $password
     * @return bool
     */
    function authenticateUser(string $username, string $password) : bool
    {
        // TODO
    }

    /**
     * Créer un spectacle : saisir les données et les valider
     * @param array $showData
     * @return int
     */
    function createShow(array $showData) : int
    {
        // TODO : retourne l'ID du spectacle créé ?
    }

    /**
     * Créer une soirée : saisir les données et les valider
     * @param array $eventData
     * @return int
     */
    function createEvent(array $eventData) : int
    {
        // TODO : retourne l'ID de la soirée créée ?
    }

    /**
     * Ajouter un spectacle à une soirée
     * @param int $showId
     * @param int $eventId
     * @return bool
     */
    function addShowToEvent(int $showId, int $eventId) : bool
    {
        // TODO
    }

    /**
     * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué comme annulé
     * @param int $showId
     * @return bool
     */
    function cancelShow(int $showId) : bool
    {
        // TODO
    }

    /**
     * Modifier un spectacle existant
     * @param int $showId
     * @param array $newShowData
     * @return bool
     */
    function updateShow(int $showId, array $newShowData) : bool
    {
        // TODO
    }

    /**
     * Modifier les spectacles d’une soirée existante
     * @param int $eventId
     * @param array $showIds
     * @return bool
     */
    function updateEventShows(int $eventId, array $showIds) : bool
    {
        // TODO
    }

    /**
     * Créer un compte staff : créer un compte utilisateur permettant de gérer le programme
     * @param string $username
     * @param string $password
     * @param array $staffData
     * @return int
     */
    function createStaffAccount(string $username, string $password, array $staffData) : int
    {
        // TODO : retourne l'ID du compte staff créé ?
    }


    public function bonjour() :string
    {
        return "bonjour";
    }

    /**
     * Fonction de création d'un tableau de Show à partir du résultat d'une requête
     * @param $stmt
     * @return array
     */
    private function createArrayShows($stmt): array{
        $shows = [];
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($rows)){
            return [];
        }

        foreach ($rows as $row) {
            $show = new Show($row['show_uuid'], $row['show_title'], $row['show_description'],
                $row['show_start_time'], $row['show_duration'], $row['show_style'], $row['show_url']);
            $shows[] = $show;
        }
        return $shows;
    }
}