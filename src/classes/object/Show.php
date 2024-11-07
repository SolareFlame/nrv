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
     * Show constructor.
     * @param string $url
     * @param string $style
     * @param int $duration
     * @param string $startDate
     * @param string $description
     * @param string $title
     * @param string $id
     */
    public function __construct(string $url, string $style, int $duration,
                                string $startDate, string $description,
                                string $title, string $id)
    {
        $this->url = $url;
        $this->style = $style;
        $this->duration = $duration;
        $this->startDate = $startDate;
        $this->description = $description;
        $this->title = $title;
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