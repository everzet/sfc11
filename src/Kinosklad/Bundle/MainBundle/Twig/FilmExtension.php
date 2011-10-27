<?php

namespace Kinosklad\Bundle\MainBundle\Twig;

use Kinosklad\Bundle\MainBundle\FilmProvider;

class FilmExtension extends \Twig_Extension
{
    private $provider;

    public function __construct(FilmProvider $provider)
    {
        $this->provider = $provider;
    }

    public function getFunctions()
    {
        return array(
            'film_search_string' => new \Twig_Function_Method($this, 'getSearchString'),
        );
    }

    public function getSearchString()
    {
        return $this->provider->getSearchString();
    }

    public function getName()
    {
        return 'film';
    }
}
