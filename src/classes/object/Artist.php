<?php 


namespace iutnc\nrv\object ;

use iutnc\nrv\exception\InvalidPropertyNameException;

class Artist {

    private string $id;
    private int $idArtist;

    /**
     * @param string $id
     * @param int $idArtist
     */
    public function __construct(string $id, int $idArtist)
    {
        $this->id = $id;
        $this->idArtist = $idArtist;
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



?>