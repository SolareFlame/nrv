<?php

namespace iutnc\nrv\repository;
use Exception;
use iutnc\nrv\dispatch\Dispatcher;
use iutnc\nrv\object\Evening;
use iutnc\nrv\object\Show;
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
        $dsn = "{$conf['driver']}:host={$conf['host']}" . "$port" . ";dbname={$conf['dbname']}";
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
            self::setConfig("config.ini");
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

        return $this->createArrayShows($stmt, Show::class);
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

        return $this->createArrayShows($stmt, Show::class);
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

        return $this->createArrayShows($stmt, Show::class);
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

        return $this->createArrayShows($stmt, Show::class);
    }

    /**
     * Affichage détaillé d’un spectacle
     * @param string $uuid
     * @return Show
     */
    function findShowDetails(string $uuid) : Show
    {
        $query = "Select show_uuid, show_title, show_description, show_start_time, 
       show_duration, show_style, show_url from show where show_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        return $this->createArrayShows($stmt, Show::class)[0];
    }

    /**
     * Récupération du détail d’une soirée
     * @param string $uuid
     * @return array
     */
    function findEveningDetails(string $uuid) : array
    {
        $query = "Select evening_uuid, evening_title, evening_theme, evening_date, 
       evening_location, evening_description, evening_price from evening where evening_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        return $this->createArrayShows($stmt, Evening::class)[0];
    }

    /**
     * Récupération des spectacles d'une soirée
     * @param string $id
     * @return array
     */
    function findShowsInEvening(string $id) : array{
        $query = "SELECT show_uuid, show_title, show_description, 
              show_start_time, show_duration, show_style, show_url 
              FROM show s
              INNER JOIN evening2show es ON show.show_uuid = es.show_uuid
              WHERE es.evening_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $id]);

        // Retourne les spectacles associés sous forme de tableau d'objets Show
        return $this->createArrayShows($stmt, Show::class);
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
     * @param Show $show
     */
    function createShow(Show $show): void
    {
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)){
            $query = "INSERT INTO show (show_uuid, show_title, show_description, show_start_time, show_duration, show_style, show_url) 
                        values (:uuid, :title, :description, :start, :duration, :style, :url)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':uuid' => $show->uuid,
                ':title' => $show->title,
                ':description' => $show->description,
                ':start' => $show->start_time,
                ':duration' => $show->duration,
                ':style' => $show->style,
                ':url' => $show->url
            ]);
        }else{
            header("index.php");
        }
    }

    /**
     * Créer une soirée : saisir les données et les valider
     * @param Evening $evening
     */
    function createEvening(Evening $evening) : void
    {
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)){
            $query = "INSERT INTO show (evening_uuid, evening_title, evening_theme, evening_date, evening_location, show_description, evening_price) 
                        values (:uuid, :title, :theme, :date, :location, :description, :price)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':uuid' => $evening->uuid,
                ':title' => $evening->title,
                ':theme' => $evening->theme,
                ':date' => $evening->date,
                ':location' => $evening->location,
                ':description' => $evening->description,
                ':price' => $evening->price
            ]);
        }else header("index.php");
    }

    /**
     * Ajouter un spectacle à une soirée
     * @param Show $show
     * @param Evening $evening
     */
    function addShowToEvening(Show $show, Evening $evening)
    {
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)) {
            $query = "Insert into evening2show (evening_uuid, show_uuid) values (:evening_uuid, :show_uuid)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':evening_uuid' => $evening->id,
                ':show_uuid' => $show->id,
            ]);
        }else header("index.php");
    }

    /**
     * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué comme annulé
     * @param Show $show show
     */
    function cancelShow(Show $show) : void
    {
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)) {
            $query = "Update show set show_programmed=false where show_uuid = :show_uuid";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['show_uuid' => $show->id]);
        }else header("index.php");
    }

    /**
     * Retirer un spectacle à une soirée
     * @param Show $show
     * @param Evening $evening
     */
    function cancelShowToEvening(Show $show, Evening $evening){
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)) {
            $query = "Delete from evening2show where evening_uuid = :evening_uuid and show_uuid = :show_uuid)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':evening_uuid' => $evening->id,
                ':show_uuid' => $show->id,
            ]);
        }else header("index.php");
    }

    /**
     * Annuler une soiree : la soiree est conservee dans les affichages mais est marqué comme annulee
     * @param Evening $evening evening
     */
    function cancelEvening(Evening $evening) : void
    {
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)) {
            $query = "Update evening set evening_programmed=false where evening_uuid = :evening_uuid";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['evening_uuid' => $evening->id]);
        }else header("index.php");
    }

    /**
     * Modifier les spectacles d’une soirée existante
     * @param string $id
     * @param Show $show
     */
    function updateShow(string $id, Show $show) : void
    {
        // TODO
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 50)) {
            $query = "Update show set show_title = :title, show_description = :description, show_start_time = :start_time, 
                show_duration = :duration, show_style = :style, show_url = :url where show_uuid = :uuid";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':title' => $show->title,
                ':description' => $show->description,
                ':show_start_time' => $show->start_time,
                ':duration' => $show->duration,
                ':style' => $show->style,
                ':url' => $show->url
            ]);
        }else header("index.php");
    }

    /**
     * Créer un compte staff : créer un compte utilisateur permettant de gérer le programme
     * @param User $user
 */
    function createAccount(User $user) : void
    {
        // TODO : retourne l'ID du compte staff créé ?
        if(isset($_SESSION) && $this->checkRole($_SESSION["user_uuid"], 100)) {
            $query = "Insert into user (user_uuid, password, user_role) values (:uuid, :password, :role)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':uuid' => $user->id,
                ':password' => $user->password,
                ':role' => $user->role
            ]);
        }else header("index.php");
    }

    /**
     * Vérifie que l'user ait la permission
     * @param $uuid
     * @param $role
     * @return bool
     */
    function checkRole($uuid, $role): bool{
        $query = "Select user_role from user where user_uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $r = $stmt->fetch(\PDO::FETCH_ASSOC);
        return ($r && $role >= $r['user_role']);
    }

    /**
     * Fonction de création d'un tableau de Show à partir du résultat d'une requête
     * @param $stmt
     * @return array
     */
    private function createArrayShows($stmt, $class): array{
        $shows = [];
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if(empty($rows)){
            return [];
        }

        foreach ($rows as $row) {
            $show = new $class($row['show_uuid'], $row['show_title'], $row['show_description'],
                $row['show_start_time'], $row['show_duration'], $row['show_style'], $row['show_url']);
            $shows[] = $show;
        }
        return $shows;
    }
}