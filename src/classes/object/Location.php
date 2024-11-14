<?php


namespace iutnc\nrv\object;

use iutnc\nrv\exception\InvalidPropertyNameException;

class Location
{

    private string $id;
    private string $name;
    private string $placeNumber;
    private string $address;
    private string $url;

    /**
     * @param string $id
     * @param string $placeNumber
     * @param string $name
     * @param string $address
     * @param string $url
     */
    public function __construct(string $id, string $placeNumber, string $name, string $address, string $url)
    {
        $this->id = $id;
        $this->placeNumber = $placeNumber;
        $this->name = $name;
        $this->address = $address;
        $this->url = $url;
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
