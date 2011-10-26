<?php

namespace Kinosklad\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KinoskladUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
