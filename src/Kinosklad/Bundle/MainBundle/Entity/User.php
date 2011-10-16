<?php

namespace Kinosklad\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Kinosklad\Bundle\MainBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array $viewed
     *
     * @ORM\ManyToMany(targetEntity="Film")
     * @ORM\JoinTable(name="user_viewed",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="film_id", referencedColumnName="id")}
     * )
     */
    private $viewed;

    /**
     * @var array $favorites
     *
     * @ORM\ManyToMany(targetEntity="Film")
     * @ORM\JoinTable(name="user_favorite",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="film_id", referencedColumnName="id")}
     * )
     */
    private $favorites;

    public function __construct()
    {
        $this->viewed    = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * View film
     *
     * @param Film $film
     */
    public function addViewed(Film $film)
    {
        $this->getViewed()->add($film);
    }

    /**
     * Get viewed films
     *
     * @return ArrayCollection
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set favorite
     *
     * @param Film $favorite
     */
    public function addFavorite(Film $film)
    {
        $this->getFavorites()->add($film);
    }

    /**
     * Get favorites
     *
     * @return ArrayCollection 
     */
    public function getFavorites()
    {
        return $this->favorites;
    }
}