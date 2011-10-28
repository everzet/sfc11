<?php

namespace Kinosklad\Bundle\MainBundle\Tests\Form;

use Kinosklad\Bundle\MainBundle\Entity\Genre;
use Kinosklad\Bundle\MainBundle\Form\Proxy\GenreProxy;

class GenreProxyTest extends FormTest
{
    public function testValidation()
    {
        $proxy     = new GenreProxy(new Genre());
        $validator = $this->getContainer()->get('validator');

        $violations = $validator->validate($proxy);
        $this->assertHasViolation($violations, 'nameEn', 'Name should not be blank');

        $proxy->setNameEn('so');

        $violations = $validator->validate($proxy);
        $this->assertHasNoViolation($violations, 'nameEn');
    }
}
