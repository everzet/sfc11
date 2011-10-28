<?php

namespace Kinosklad\Bundle\MainBundle\Form\Proxy;

use Symfony\Component\Validator\Constraints as Assert;

class FilmLinkProxy
{
    /**
     * @Assert\NotBlank(message="Url should not be blank")
     * @Assert\Url
     */
    public $url;
}
