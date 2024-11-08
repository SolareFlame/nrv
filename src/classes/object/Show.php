<?php


namespace iutnc\nrv\object;

use DateTime;
use iutnc\nrv\exception\InvalidPropertyNameException;
use iutnc\nrv\render\ShowRenderer;

class Show
{
    private string $id;
    private string $title;
    private string $description;
    private string $startDate;
    private DateTime $duration;
    private Style $style;
    private string $url;
    private array $artists = [];

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $startDate
     * @param DateTime $duration
     * @param string $style
     * @param string $url
     * @param array $artists
     */
    public function __construct(string $id, string $title, string $description, string $startDate, DateTime $duration, Style $style, string $url)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->duration = $duration;
        $this->style = $style;
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

    public function getRenderer(int $option): string
    {
        $sr = new ShowRenderer($this);
        return $sr->render($option);
    }
}