<?php

namespace iutnc\nrv\object;

use DateTime;
use iutnc\nrv\authn\NrvAuthnProvider;
use iutnc\nrv\exception\InvalidPropertyNameException;
use iutnc\nrv\render\ShowEditRenderer;
use iutnc\nrv\render\ShowRenderer;
use iutnc\nrv\repository\NrvRepository;

class Show
{
    private string $id;
    private string $title;
    private string $description;
    private DateTime $startDate;
    private int $duration;
    private string $style;
    private string $url;
    private array $artists = [];

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param DateTime $startDate
     * @param int $duration
     * @param string $style
     * @param string $url
     * @param array $artists
     */
    public function __construct(string $id, string $title, string $description, DateTime $startDate, int $duration, string $style, string $url)
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

    public function setListeArtiste(array $listArtists): void
    {
        foreach ($listArtists as $artist){
            $this->artists[] = unserialize($artist);
        }

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

    /**
     * Permet d'avoir directement le rendu d'un objet Show
     * @param int $option 0 : affichage simple, 1: affichage détaillé
     * @return string le rendu de l'objet Show
     */
    public function getRender(int $option): string
    {
        $sr = new ShowRenderer($this);
        return $sr->render($option);
    }
}