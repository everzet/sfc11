<?php

namespace Kinosklad\Bundle\MainBundle\Tests\Form;

use Kinosklad\Bundle\MainBundle\Form\Proxy\FilmLinkProxy;

class FilmLinkProxyTest extends FormTest
{
    public function testValidation()
    {
        $proxy     = new FilmLinkProxy();
        $validator = $this->getContainer()->get('validator');

        $violations = $validator->validate($proxy);
        $this->assertHasViolation($violations, 'url', 'Url should not be blank');

        $proxy->url = 'so';

        $violations = $validator->validate($proxy);
        $this->assertHasViolation($violations, 'url', 'This value is not a valid URL');

        $proxy->url = 'http://everzet.com/some_subpage.html?query+string';

        $violations = $validator->validate($proxy);
        $this->assertHasNoViolation($violations, 'url');
    }
}
