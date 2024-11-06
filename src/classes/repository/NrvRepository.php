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
}