<?php


namespace iutnc\nrv\object;

use iutnc\nrv\exception\InvalidPropertyNameException;

class Artist
{

    private string $id;
    private string $name;
    private string $description;
    private string $url;

    /**
     * @param string $id L'identifiant de l'artiste
     * @param string $name Le nom de l'artiste
     * @param string $description La description de l'artiste
     * @param string $url L'URL de l'artiste
     */
    public function __construct(string $id, string $name, string $description, string $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->description = $description;
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

}
