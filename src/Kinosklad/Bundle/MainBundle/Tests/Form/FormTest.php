<?php

namespace Kinosklad\Bundle\MainBundle\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

abstract class FormTest extends WebTestCase
{
    protected function assertHasViolation(ConstraintViolationList $violations, $path, $message = '')
    {
        $this->assertContains("$path:\n    $message", (string) $violations, (string) $violations);
    }

    protected function assertHasNoViolation(ConstraintViolationList $violations, $path, $message = '')
    {
        $this->assertNotContains("$path:\n    $message", (string) $violations, (string) $violations);
    }

    protected function getContainer()
    {
        if (null === static::$kernel) {
            static::createClient();
        }

        static::$kernel->boot();

        return static::$kernel->getContainer();
    }
}
