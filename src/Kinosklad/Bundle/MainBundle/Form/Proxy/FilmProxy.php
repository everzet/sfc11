<?php

namespace Kinosklad\Bundle\MainBundle\Form\Proxy;

use Kinosklad\Bundle\MainBundle\Entity\Film;

class FilmProxy
{
    private $film;

    public function __construct(Film $film)
    {
        $this->film = $film;
    }

    public function getFilm()
    {
        return $this->film;
    }

    public function getNameEn()
    {
        return $this->film->translate()->getName();
    }

    public function setNameEn($name)
    {
        $this->film->translate()->setName($name);
    }

    public function getNameRu()
    {
        return $this->film->translate('ru')->getName();
    }

    public function setNameRu($name)
    {
        $this->film->translate('ru')->setName($name);
    }

    public function getDescriptionEn()
    {
        return $this->film->translate()->getDescription();
    }

    public function setDescriptionEn($description)
    {
        $this->film->translate()->setDescription($description);
    }

    public function getDescriptionRu()
    {
        return $this->film->translate('ru')->getDescription();
    }

    public function setDescriptionRu($description)
    {
        $this->film->translate('ru')->setDescription($description);
    }
}
