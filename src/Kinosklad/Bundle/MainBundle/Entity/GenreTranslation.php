<?php

namespace Kinosklad\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translator\Entity\Translation;

/**
 * @ORM\Table(
 *     indexes={@ORM\index(name="genre_translations_lookup_idx", columns={
 *         "locale", "translatable_id"
 *     })},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="genre_lookup_unique_idx", columns={
 *         "locale", "translatable_id", "property"
 *     })}
 * )
 * @ORM\Entity
 */
class GenreTranslation extends Translation
{
    /**
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="translations")
     */
    protected $translatable;
}
