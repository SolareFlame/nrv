<?php

namespace iutnc\nrv\action\program_management;

use iutnc\nrv\action\Action;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\exception\AuthnException;
use iutnc\nrv\object\User;
use iutnc\nrv\repository\NrvRepository;

/**
 * Créer un compte staff : créer un compte utilisateur permettant de gérer le programme
 */
class CreateStaffAccountAction extends Action
{

    /**
     * @inheritDoc
     */
    public function executePost(): string
    {
        $repo = NrvRepository::getInstance();
        // Récupération et nettoyage du nom d'utilisateur
        $username = filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST['password'],FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_var($_POST['confirm_password'],FILTER_SANITIZE_SPECIAL_CHARS);

        // Vérification que le mot de passe et la confirmation sont identiques
        if ($password !== $confirm_password) {
            return $this->errorMessage("Les mots de passe ne correspondent pas. Veuillez les saisir à nouveau.");
        }

        try {
            NrvAuthnProvider::register($password,User::ROLE_ORGA);
            NrvAuthnProvider::login($password);
            header('Location: index.php');
        } catch (AuthnException $e) {
            return $this->errorMessage($e->getMessage());
        }


        return "";
    }

    /**
     * @inheritDoc
     */
    public function executeGet(): string
    {

        return <<<HTML

<form method="post" class="form-container">
    <h3 class="form-title">Créer un Compte</h3>
    
    <div class="form-group">
        <label for="username" class="form-label">Nom d'utilisateur</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="username" id="username" class="form-control" placeholder="Entrez votre nom d'utilisateur" required>
        </div>
    </div>
    
    <div class="form-group">
        <label for="password" class="form-label">Mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
        </div>
    </div>
    
    <div class="form-group">
        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmez votre mot de passe" required>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 py-2">Créer mon compte</button>
</form>
HTML;
    }


    /**
     * Crée un message d'erreur à afficher dans le formulaire d'inscription.
     *
     * @param string $message Le message d'erreur à afficher.
     * @return string Le rendu HTML du formulaire d'inscription avec le message d'erreur.
     */
    protected function errorMessage(string $message) : string
    {
        $errorMessage = <<<HTML
        <div class="alert alert-danger mt-3 text-center" role="alert">
             $message
        </div>
HTML;
        $res = $this->executeGet();
        return str_replace(
            '<button type="submit" class="btn btn-primary w-100 py-2">Créer mon compte</button>',
            '<button type="submit" class="btn btn-primary w-100 py-2">Créer mon compte</button><br>' . $errorMessage,
            $res
        );
    }
}