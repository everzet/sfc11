<?php

namespace Kinosklad\Bundle\MainBundle\Tests\Form;

use Kinosklad\Bundle\MainBundle\Entity\Film;
use Kinosklad\Bundle\MainBundle\Form\Proxy\FilmProxy;

class FilmProxyTest extends FormTest
{
    public function testValidation()
    {
        $proxy     = new FilmProxy(new Film());
        $validator = $this->getContainer()->get('validator');

        $violations = $validator->validate($proxy);
        $this->assertHasViolation($violations, 'nameEn', 'Name should not be blank');
        $this->assertHasViolation($violations, 'descriptionEn', 'Description should not be blank');
        $this->assertHasViolation($violations, 'film.premiere');
        $this->assertHasViolation($violations, 'film.country');
        $this->assertHasViolation($violations, 'film.length');

        $proxy->setNameEn('so');

        $violations = $validator->validate($proxy);
        $this->assertHasViolation($violations, 'nameEn', 'Title should be more than');
        $this->assertHasViolation($violations, 'descriptionEn');
        $this->assertHasViolation($violations, 'film.premiere');
        $this->assertHasViolation($violations, 'film.country');
        $this->assertHasViolation($violations, 'film.length');

        $proxy->setNameEn('some name');
        $proxy->setDescriptionEn('some desc');

        $violations = $validator->validate($proxy);
        $this->assertHasNoViolation($violations, 'nameEn', 'Title should be more than');
        $this->assertHasNoViolation($violations, 'descriptionEn');
        $this->assertHasViolation($violations, 'film.premiere');
        $this->assertHasViolation($violations, 'film.country');
        $this->assertHasViolation($violations, 'film.length');
    }
}
