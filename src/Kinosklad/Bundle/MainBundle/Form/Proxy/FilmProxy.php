<?php

namespace Kinosklad\Bundle\MainBundle\Form\Proxy;

use Kinosklad\Bundle\MainBundle\Entity\Film;
use Symfony\Component\Validator\Constraints as Assert;

class FilmProxy
{
    private $film;

    /**
     * @Assert\File(maxSize=6000000)
     */
    public $imageFile;

    public $removeImage = false;

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

    public function evaluateUpload()
    {
        if ($oldImage = $this->getFilm()->getImage(true) && $this->removeImage) {
            unlink($oldImage);
            $this->getFilm()->setImage($this->imageFile = $oldImage = null);
        }

        if (null === $this->imageFile) {
            return;
        }

        // remove old image
        if ($oldImage) {
            unlink($oldImage);
        }

        $imagesPath = $this->getFilm()->getImagesPath(true);
        if (!is_dir($imagesPath)) {
            mkdir($imagesPath, 0777, true);
        }

        $imageName = md5(uniqid().$this->getFilm()->getName()).'.'.$this->imageFile->guessExtension();

        $this->imageFile->move($imagesPath, $imageName);
        $this->getFilm()->setImage($imageName);
    }
}
