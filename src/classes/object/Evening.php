<?php 

namespace iutnc\nrv\object ;

use iutnc\nrv\exception\InvalidPropertyNameException;
use iutnc\nrv\render\EveningRenderer;

class Evening {
    private string $id;
    private string $title;
    private string $theme;
    private string $date;
    private Location $location;
    private string $description;
    private float $eveningPrice;
    private array $shows;

    /**
     * @param string $id
     * @param string $title
     * @param string $theme
     * @param string $date
     * @param Location $location
     * @param string $description
     * @param float $eveningPrice
     */
    public function __construct(string $id, string $title, string $theme, string $date, Location $location, string $description, float $eveningPrice)
    {
        $this->id = $id;
        $this->title = $title;
        $this->theme = $theme;
        $this->date = $date;
        $this->location = $location;
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

    public function addShow(Show $show)
    {
        $this->shows[] = $show;
    }

    public function deleteShow(Show $show)
    {
        unset($this->shows,$show);
    }

    public function addShows(array $shows)
    {
        foreach ($shows as $show){
            $this->shows[] = unserialize($show);
        }
    }

    public function getRender(int $option): string
    {
        $renderer = new EveningRenderer($this);
        return $renderer->render($option);
    }

}
