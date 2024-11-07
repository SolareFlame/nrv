<?php 

namespace iutnc\nrv\object ;

use iutnc\nrv\exception\InvalidPropertyNameException;

class Evening {
    private string $id;
    private string $title;
    private string $theme;
    private string $date;
    private int $idLocation;
    private string $description;
    private float $eveningPrice;

    /**
     * @param string $id
     * @param string $title
     * @param string $theme
     * @param string $date
     * @param int $idLocation
     * @param string $description
     * @param float $eveningPrice
     */
    public function __construct(string $id, string $title, string $theme, string $date, int $idLocation, string $description, float $eveningPrice)
    {
        $this->id = $id;
        $this->title = $title;
        $this->theme = $theme;
        $this->date = $date;
        $this->idLocation = $idLocation;
        $this->description = $description;
        $this->eveningPrice = $eveningPrice;
    }



    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new InvalidPropertyNameException("La propriété '$property' n'existe pas.");
    }






}




?>