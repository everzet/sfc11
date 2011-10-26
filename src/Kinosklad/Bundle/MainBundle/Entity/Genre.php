<?php

namespace Kinosklad\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translator\ObjectTranslator;

/**
 * Kinosklad\Bundle\MainBundle\Entity\Genre
 *
 * @ORM\Entity(repositoryClass="Kinosklad\Bundle\MainBundle\Entity\GenreRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Genre
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
     * @var string $name
     *
     * @Assert\NotBlank(message="Name should not be blank")
     *
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(length=128, unique=true)
     *
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Film", mappedBy="genres")
     */
    private $films;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(type="date")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(
     *     targetEntity="GenreTranslation",
     *     mappedBy="translatable",
     *     cascade={"persist", "update", "remove"}
     * )
     */
    private $translations;

    private $translator;

    public function __construct()
    {
        $this->translations = new ArrayCollection();

        $this->translate();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getFilms()
    {
        return $this->films;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /** @ORM\PrePersist */
    public function translate($locale = null)
    {
        if (null === $this->translator) {
            $this->translator = new ObjectTranslator($this,
                array('name'),
                'Kinosklad\Bundle\MainBundle\Entity\GenreTranslation',
                $this->translations
            );
        }

        return $this->translator->translate($locale);
    }
}
