<?php

namespace Kinosklad\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translator\ObjectTranslator;

/**
 * Kinosklad\Bundle\MainBundle\Entity\Film
 *
 * @ORM\Entity(repositoryClass="Kinosklad\Bundle\MainBundle\Entity\FilmRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Film
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
     * @Assert\MinLength(limit=3, message="Title should be more than {{limit}} letters in length")
     * @Assert\MaxLength(limit=255, message="Title should be less than {{limit}} letters in length")
     *
     * @ORM\Column(name="name", type="string", length=128)
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
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var smallint $length
     *
     * @Assert\NotBlank(message="Specify film length")
     * @Assert\Min(limit=0, message="Film length should be more than {{limit}} minutes")
     *
     * @ORM\Column(name="length", type="smallint")
     */
    private $length;

    /**
     * @var string $country
     *
     * @Assert\NotBlank(message="Specify film country")
     * @Assert\Country
     *
     * @ORM\Column(name="country", type="string", length=128)
     */
    private $country;

    /**
     * @var date $premiere
     *
     * @Assert\NotBlank(message="Specify film premiere date")
     * @Assert\Date
     *
     * @ORM\Column(name="premiere", type="date")
     */
    private $premiere;

    /**
     * @var string $description
     *
     * @Assert\NotBlank(message="Description should not be blank")
     * @Assert\MaxLength(
     *     limit=1000,
     *     message="Description should be less than {{limit}} letters in length"
     * )
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Genre")
     */
    private $genres;

    /**
     * @var array $links
     *
     * @ORM\Column(name="links", type="array")
     */
    private $links = array();

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
     *     targetEntity="FilmTranslation",
     *     mappedBy="translatable",
     *     cascade={"persist", "update", "remove"}
     * )
     */
    private $translations;

    private $translator;

    public function __construct()
    {
        $this->genres       = new ArrayCollection();
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

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @param Boolean $absolute
     *
     * @return string
     */
    public function getImage($absolute = false)
    {
        if (null === $this->image) {
            return;
        }

        return self::getImagesPath($absolute).'/'.$this->image;
    }

    /**
     * Get images storage path
     *
     * @param Boolean $absolute
     *
     * @return string
     */
    public static function getImagesPath($absolute = false)
    {
        $path = '';
        if ($absolute) {
            $path = realpath(__DIR__.'/../../../../../web').'/';
        }

        return $path.'uploads/films';
    }

    /**
     * Set length
     *
     * @param smallint $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Get length
     *
     * @return smallint
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set premiere
     *
     * @param DateTime $premiere
     */
    public function setPremiere(\DateTime $premiere)
    {
        $this->premiere = $premiere;
    }

    /**
     * Get premiere
     *
     * @return date
     */
    public function getPremiere()
    {
        return $this->premiere;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add genre to film
     *
     * @param Genre $genre
     */
    public function addGenre(Genre $genre)
    {
        $this->getGenres()->add($genre);
    }

    /**
     * Get film genres
     *
     * @return ArrayCollection
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Set links
     *
     * @param array $links
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    /**
     * Add link
     *
     * @param string $link
     */
    public function addLink($link)
    {
        $this->links[] = $link;
    }

    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
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
                array('name', 'description'),
                'Kinosklad\Bundle\MainBundle\Entity\FilmTranslation',
                $this->translations
            );
        }

        return $this->translator->translate($locale);
    }
}
