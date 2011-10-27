<?php

namespace Kinosklad\Bundle\MainBundle\Form\Proxy;

use Symfony\Component\Validator\Constraints as Assert;

class FilmLinkProxy
{
    /**
     * @Assert\Url
     */
    public $url;
}
