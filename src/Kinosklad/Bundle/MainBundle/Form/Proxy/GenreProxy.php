<?php

namespace Kinosklad\Bundle\MainBundle\Form\Proxy;

use Kinosklad\Bundle\MainBundle\Entity\Genre;

class GenreProxy
{
    private $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    /**
     * @Assert\Valid
     */
    public function getGenre()
    {
        return $this->genre->translate();
    }

    /**
     * @Assert\NotBlank(message="Name should not be blank")
     */
    public function getNameEn()
    {
        return $this->genre->translate()->getName();
    }

    public function setNameEn($name)
    {
        $this->genre->translate()->setName($name);
    }

    public function getNameRu()
    {
        return $this->genre->translate('ru')->getName();
    }

    public function setNameRu($name)
    {
        $this->genre->translate('ru')->setName($name);
    }
}
