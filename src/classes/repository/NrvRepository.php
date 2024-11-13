<?php

namespace iutnc\nrv\repository;

use DateTime;
use Exception;
use iutnc\nrv\exception\AuthnException;
use iutnc\nrv\exception\RepositoryException;
use iutnc\nrv\object\Artist;
use iutnc\nrv\object\Evening;
use iutnc\nrv\object\Location;
use iutnc\nrv\object\Show;
use iutnc\nrv\object\Style;
use iutnc\nrv\object\User;
use PDO;
use PDOStatement;

class NrvRepository
{
    //ATTRIBUTS
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


    //CONFIG DE LA BASE DE DONNEES

    /**
     * Définit la configuration de la base de données.
     *
     * @param string $file Chemin vers le fichier de configuration.
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
     * @return ?NrvRepository
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



    //SHOW

    /**
     * Affichage de la liste des spectacles
     * @return array|string[]
     * @throws Exception
     */
    function findAllShows(): array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_date, show_duration, show_style_id, show_url from nrv_show";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, 'Show');
    }

    /**
     * Filtrage de la liste des spectacles par date
     * @param string $date
     * @return array|string[]
     * @throws Exception
     */
    function findShowsByDate(string $date): array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_date, 
       show_duration, show_style_id, show_url from nrv_show where DATE(show_start_date) = :date";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['date' => $date]);

        return $this->createArrayFromStmt($stmt, 'Show');
    }

    /**
     * Filtrage de la liste des spectacles par style de musique
     * @param string $style
     * @return array|string[]
     * @throws Exception
     */
    function findShowsByStyle(string $style): array
    {
        $query = "Select show_uuid, show_title, show_description, show_start_date, 
       show_duration, show_style_id, show_url from nrv_show where show_style_id = :style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['style' => $style]);

        return $this->createArrayFromStmt($stmt, 'Show');
    }

    /**
     * Filtrage de la liste des spectacles par lieu
     * @param string $location
     * @return array|string[]
     * @throws Exception
     */
    function findShowsByLocation(string $location): array
    {
        $query = "Select s.show_uuid, show_title, show_description, show_start_date, 
            show_duration, show_style_id, show_url, show_programmed
            from nrv_show s INNER JOIN nrv_evening2show es ON s.show_uuid = es.show_uuid
            INNER JOIN nrv_evening e ON es.evening_uuid = e.evening_uuid WHERE e.evening_location_id = :location";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['location' => $location]);

        return $this->createArrayFromStmt($stmt, 'Show');
    }

    /**
     * Affichage détaillé d’un spectacle
     * @param string $uuid
     * @return Show
     * @throws Exception
     */
    function findShowDetails(string $uuid): Show
    {
        $query = "Select show_uuid, show_title, show_description, show_start_date, 
       show_duration, show_style_id, show_url from nrv_show where show_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        return $this->createArrayFromStmt($stmt, 'Show')[0];
    }

    /**
     * Récupération des spectacles d'une soirée
     * @param string $id
     * @return array|string[]
     * @throws Exception
     */
    function findShowsInEvening(string $id): array
    {
        $query = "SELECT s.show_uuid, show_title, show_description, 
              show_start_date, show_duration, show_style_id, show_url 
              FROM nrv_show s
              INNER JOIN nrv_evening2show es ON s.show_uuid = es.show_uuid
              WHERE es.evening_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $id]);

        // Retourne les spectacles associés sous forme de tableau d'objets Show
        return $this->createArrayFromStmt($stmt, 'Show');
    }

    /**
     * Créer un spectacle : saisir les données et les valider
     * @param Show $show
     */
    function createShow(Show $show): void
    {
        $query = "INSERT INTO nrv_show (show_uuid, show_title, show_description, show_start_date, show_duration, show_style_id, show_url) 
                    values (:uuid, :title, :description, :start, :duration, :style, :url)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':uuid' => $show->id,
            ':title' => $show->title,
            ':description' => $show->description,
            ':start' => $show->startDate,
            ':duration' => $show->duration,
            ':style' => $show->style,
            ':url' => $show->url
        ]);

        //TODO relier les artistes correspondant au show
    }

    /**
     * Créer une soirée : saisir les données et les valider
     * @param Evening $evening
     */
    function createEvening(Evening $evening): void
    {
        $query = "INSERT INTO nrv_evening (evening_uuid, evening_title, evening_theme, evening_date, evening_location_id, evening_description, evening_price) 
                    values (:uuid, :title, :theme, :date, :location, :description, :price)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':uuid' => $evening->id,
            ':title' => $evening->title,
            ':theme' => $evening->theme,
            ':date' => $evening->date,
            ':location' => $evening->location->id,
            ':description' => $evening->description,
            ':price' => $evening->eveningPrice
        ]);
    }

    /**
     * Ajouter un spectacle à une soirée
     * @param Show $show
     * @param Evening $evening
     */
    function addShowToEvening(Show $show, Evening $evening): void
    {
        $query = "Insert into nrv_evening2show (evening_uuid, show_uuid) values (:evening_uuid, :show_uuid)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':evening_uuid' => $evening->id,
            ':show_uuid' => $show->id,
        ]);
    }

    /**
     * Annuler un spectacle : le spectacle est conservé dans les affichages mais est marqué comme annulé
     * @param String $uuid
     */
    function cancelShow(String $uuid): void
    {
        $query = "Update nrv_show set show_programmed=0 where show_uuid = :show_uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['show_uuid' => $uuid]);
    }

    /**
     * Retirer un spectacle à une soirée
     * @param Show $show
     * @param Evening $evening
     */
    function deleteShowFromEvening(Show $show, Evening $evening)
    {
        $query = "Delete from nrv_evening2show where evening_uuid = :evening_uuid and show_uuid = :show_uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':evening_uuid' => $evening->id,
            ':show_uuid' => $show->id,
        ]);
    }

    /**
     * Modifier les spectacles d’une soirée existante
     * @param string $uuid
     * @param Show $show
     */
    function updateShow(string $uuid, Show $show): void
    {
        $query = "Update nrv_show set show_title = :title, show_description = :description, show_start_date = :start_time, 
            show_duration = :duration, show_style_id = :style, show_url = :url where show_uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':title' => $show->title,
            ':description' => $show->description,
            ':show_start_date' => $show->start_date,
            ':duration' => $show->duration,
            ':style' => $show->style,
            ':url' => $show->url,
            ':uuid' => $uuid
        ]);
    }

    /**
     * @param string $idFav : id du show à récupérer
     * @return Show : show correspondant à l'id
     * @throws \DateMalformedStringException
     */
    public function findShowById(string $idFav): Show
    {
        $query = "Select * from nrv_show where show_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $idFav]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $date =  $row['show_start_date'];
        $datetime = new DateTime($date);
        return new Show($row['show_uuid'], $row['show_title'], $row['show_description'],$datetime,
            $row['show_duration'],
            $row['show_style_id'], $row['show_url']);
    }

    /**
     * @param string $uuid : id du show à vérifier
     * @return bool : true si l'id représente un show, false sinon
     */
    public function verifIdFav(string $uuid): bool
    {
        $query = "Select show_uuid from nrv_show where show_uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res)
            return true;
        else
            return false;
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowTitle(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_title = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowDescription(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_description = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowDate(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_start_date = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowDuration(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_duration = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowStyle(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_style = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    /**
     * @param string $showId
     * @param string $valeur
     * @return void
     * @throws RepositoryException
     */
    function updateShowUrl(string $showId, string $valeur) : void
    {
        $query = "UPDATE nrv_show SET show_url = :valeur WHERE show_uuid = :showId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(["valeur"=>$valeur,"showId"=>$showId]);
        $success = $stmt->rowCount();

        if (!$success){
            throw new RepositoryException("La mise à jour à échoué.");
        }
    }

    //EVENING

    /**
     * Récupération du détail d’une soirée
     * @param string $uuid
     * @return Evening
     * @throws Exception
     */
    function findEveningDetails(string $uuid): Evening
    {
        $query = "Select evening_uuid, evening_title, evening_theme, evening_date, 
       evening_location_id, evening_description, evening_price from nrv_evening where evening_uuid = :uuid";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        return unserialize($this->createArrayFromStmt($stmt, 'Evening')[0]);
    }

    /**
     * Annuler une soiree : la soiree est conservee dans les affichages mais est marqué comme annulee
     * @param Evening $evening evening
     */
    function cancelEvening(Evening $evening): void
    {
        $query = "Update nrv_evening set evening_programmed=0 where evening_uuid = :evening_uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['evening_uuid' => $evening->id]);
    }

    /**
     * Recherche tout les shows contenue dans la liste d'id de shows
     * @param array $listIdFav : liste des id des shows à récupérer
     * @return string[] : liste des shows correspondant aux id
     * @throws Exception : si la liste est vide
     */
    public function findShowsByListId(array $listIdFav): array
    {
        $placeholders = implode(", ", array_fill(0, count($listIdFav), "?"));  // créer un array de ? de la taille de listIdFa

        $query = "Select * from nrv_show where show_uuid in ({$placeholders})"; // on créer la liste des ?
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($listIdFav));  // on execute la requête avec les valeurs de listIdFav

        return $this->createArrayFromStmt($stmt, "Show");
    }

    function findAllEvenings(): array
    {
        $query = "Select evening_uuid, evening_title, evening_theme, evening_date, evening_location_id, 
       evening_description, evening_price, evening_programmed from nrv_evening";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, "Evening");
    }



    //AUTHENTIFICATION

    /**
     * S'authentifier
     * @param string $password
     * @return bool
     * @throws Exception
     */
    function authentificateUser(string $password): string
    {
        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

        $query = "Select user_uuid, password from nrv_user";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll();

        if (empty($res)) {  // BASE VIDE
            return false;
        }

        for ($i=0; $i < sizeof($res); $i++) { 
            if(password_verify($password,$res[$i]['password'])){
                return $res[$i]['user_uuid'] ;
            }
        }
        return false ;
    }

    /**
     * @throws AuthnException
     */
    function getUser($username) : User
    {
        $query = "SELECT user_name,user_uuid,password,user_role FROM nrv_user where user_name = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();

        if ($data) {
            return new User($data["user_uuid"],$data['user_role'],$data["passwd"]);
        } else {
            throw new AuthnException("Le nom d'utilisateur existe pas");
        }
    }

    /**
     * Créer un compte staff : créer un compte utilisateur permettant de gérer le programme
     * @param User $user
     */
    function createAccount(User $user): void
    {
        $query = "Insert into nrv_user (user_uuid, password, user_role) values (:uuid, :password, :role)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':uuid' => $user->id,
            ':password' => $user->password,
            ':role' => $user->role
        ]);
    }

    /**
     * Vérifie que l'user ait la permission
     * @param $uuid
     * @param $role
     * @return bool
     */
    function checkRole($uuid, $role): bool
    {
        $query = "Select user_role from nrv_user where user_uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($r && $role >= $r['user_role']);
    }

    /**
     * Fonction de création d'un tableau de Show|Evening à partir du résultat d'une requête
     * @param string $class le nom de la classe à instancier
     * @param false|PDOStatement $stmt le stmt déjà éxécuté
     * @return array le tableau de Show|Evening
     * @throws Exception si la classe n'existe pas
     */
    private function createArrayFromStmt(false|PDOStatement $stmt, string $class): array
    {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) {
            throw new Exception("La liste est vide.");
        }

        $create_path = "iutnc\\nrv\\object\\$class";
        if (!class_exists($create_path)) {
            throw new Exception("La classe $class n'existe pas.");
        }
        switch ($class) {
            case "Show":
                // pour parcourir 1 seul fois la base de données au lieu de findStyleById pour chaque show
                $liste_style = NrvRepository::getInstance()->equivalentStyleObject();
                foreach ($rows as $row) {
                    $style = $liste_style[(int)$row['show_style_id']];
                    $show = new $create_path(
                        $row['show_uuid'],
                        $row['show_title'],
                        $row['show_description'],
                        (new DateTime($row['show_start_date'])),
                        $row['show_duration'],
                        $style,
                        $row['show_url']);
                    $results[] = serialize($show);
                }
                break;
            case "Evening":
                foreach ($rows as $row) {
                    $evening = new $create_path($row['evening_uuid'], $row['evening_title'], $row['evening_theme'],
                        $row['evening_date'],$this->findLocationById($row['evening_location_id']) , $row['evening_description'], $row['evening_price']);
                    $results[] = serialize($evening);
                }
                break;
            case "Style":
                $results = array_column($rows, "style
                break;_name");
                break;
            case "Location":
                foreach ($rows as $row) {
                    $location = new $create_path($row['location_id'], $row['location_place_number'], $row['location_name'], $row['location_address'], $row['location_url']);
                    $results[] = serialize($location);
                }
                break;
            case "Artist":
                foreach ($rows as $row) {
                    $artist = new $create_path($row['artist_uuid'], $row['artist_name'], $row['artist_description'], $row['artist_url']);
                    $results[] = serialize($artist);
                }
                break;
            default:
                return [];
        }
        return $results;
    }



    //LOCATION

    /**
     * Retourne toutes les locations
     * @return array
     * @throws Exception
     */
    function findAllLocations(): array
    {
        $query = "Select location_id, location_name, location_place_number, location_address, location_url from nrv_location";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, "Location");
    }


    /**
     * Retourne une location à partir d'un id
     * @param int $locationId
     * @return Location
     */
    function findLocationById(int $locationId): Location
    {
        $query = "Select * from nrv_location where location_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $locationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Location($row['location_id'], $row['location_place_number'], $row['location_name'], $row['location_address'], $row['location_url']);
    }



    //ARTIST

    /**
     * Retourne un artistà partir d'un id
     * @param string $artistUuid
     * @return Artist
     */
    function findArtistById(string $artistUuid): Artist
    {
        $query = "Select * from nrv_artist where artist_uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $artistUuid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Artist($row['artist_uuid'], $row['artist_name'], $row['artist_description'], $row['artist_url']);
    }

    /**
     * Retourne tous les artistes
     * @return array
     * @throws Exception
     */
    function findAllArtists(): array
    {
        $query = "Select artist_uuid, artist_name, artist_description, artist_url from nrv_artist";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, "Artist");
    }

    /**
     * Retourne tous les artistes
     * @return array
     * @throws Exception
     */
    function findAllArtistsID_Name(): array
    {
        $query = "Select artist_uuid, artist_name from nrv_artist";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }



    //STYLE

    /**
     * Retourne la une location à partir d'un id
     * @param int $styleId
     * @return Style
     */
    function findStyleById(int $styleId): Style
    {
        $query = "Select * from nrv_style where style_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $styleId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['style_name'];
    }

    /**
     * Retourne tous les noms de styles
     * @return array
     * @throws Exception
     */
    function findAllStyles(): array
    {
        $query = "Select style_name from nrv_style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, "Style");
    }

    /**
     * Retourne tous les noms de styles
     * @return array
     * @throws Exception
     */
    function findAllStylesNameRAW(): array
    {
        $query = "Select style_name from nrv_style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $this->createArrayFromStmt($stmt, "Style");
    }

    /**
     * Retourne tous les styles
     * @return array
     * @throws Exception
     */
    function findAllStylesRAW(): array
    {
        $query = "Select * from nrv_style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    function equivalentStyleObject(): array
    {
        $query = "Select * from nrv_style";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $results = [];
        foreach ($rows as $row) {
            $results[$row['style_id']] = $row['style_name'];
        }
        return $results;
    }
}