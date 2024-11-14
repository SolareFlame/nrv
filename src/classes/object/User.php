<?php

namespace iutnc\nrv\object;

use iutnc\nrv\exception\InvalidPropertyNameException;


class User
{
    const ROLE_ADMIN = 100;
    const ROLE_ORGA = 50;
    private string $id;
    private string $username;
    private string $password;
    private int $role;

    /**
     * @param string $id
     * @param int $role
     * @param string $password hashé
     */
    public function __construct(string $id, int $role, string $password)
    {
        $this->id = $id;
        $this->username = "Pas fait";
        $this->role = $role;
        $this->password = $password;
    }


    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new InvalidPropertyNameException("La propriété $property n'existe pas.");
    }

    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

}
