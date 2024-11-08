<?php

namespace iutnc\nrv\object;
use iutnc\nrv\exception\InvalidPropertyNameException;

class Style
{
    private string $id;
    private string $name;

    /**
     * Show constructor.
     * @param string $name
     * @param int $id
     */
    public function __construct(string $name, int $id)
    {
        $this->name = $name;
        $this->id = $id;
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