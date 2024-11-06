<?php

namespace iutnc\goodfood\action;

abstract class Action
{
    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct()
    {
        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Exécute l'action en fonction de la méthode HTTP.
     *
     * @return string Le résultat de l'exécution de l'action.
     */
    public function execute() : string
    {
        switch ($this->http_method){
            case "GET":
                return $this->get();
            case  "POST" :
                return $this->post();
            default:
                return "Methode non autorisé";
        }
    }

    /**
     * Méthode abstraite pour gérer les requêtes GET.
     *
     * @return string Le résultat de la gestion de la requête GET.
     */
    public abstract function get();

    /**
     * Méthode abstraite pour gérer les requêtes POST.
     *
     * @return string Le résultat de la gestion de la requête POST.
     */
    public abstract function post();
}