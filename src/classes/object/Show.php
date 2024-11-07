<?php


namespace iutnc\nrv\object;

use iutnc\nrv\exception\InvalidPropertyNameException;
use iutnc\nrv\Render\ShowRenderer;

class Show
{
    private string $id;
    private string $title;
    private string $description;
    private string $startDate;
    private int $duration;
    private string $style;
    private string $url;
    private array $artists = [];

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $startDate
     * @param int $duration
     * @param string $style
     * @param string $url
     * @param array $artists
     */
    public function __construct(string $id, string $title, string $description, string $startDate, int $duration, string $style, string $url, array $artists)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->duration = $duration;
        $this->style = $style;
        $this->url = $url;
        $this->artists = $artists;
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

    public function ajouterArtiste(Artist $artist): void
    {
        $this->artists[] = $artist;
    }

    public function DisplayArtiste(): string
    {
        $res = "";
        foreach ($this->artists as $artist) {
            $res .= $artist->name . " | ";
        }
        return $res;
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    public function getRender(int $option): string
    {
        $sr = new ShowRenderer($this);
        return $sr->render($option);
    }


}


?>