<?php

namespace Kinosklad\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translator\Entity\Translation;

/**
 * @ORM\Table(
 *     indexes={@ORM\index(name="film_translations_lookup_idx", columns={
 *         "locale", "translatable_id"
 *     })},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="film_lookup_unique_idx", columns={
 *         "locale", "translatable_id", "property"
 *     })}
 * )
 * @ORM\Entity
 */
class FilmTranslation extends Translation
{
    /**
     * @ORM\ManyToOne(targetEntity="Film", inversedBy="translations")
     */
    protected $translatable;
}
