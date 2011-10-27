<?php

namespace Kinosklad\Bundle\MainBundle\Features\Context;

use Behat\BehatBundle\Context\BehatContext,
    Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException,
    Behat\Behat\Context\Step;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext
{
    /**
     * @Given /^на сайте зарегистрированы:$/
     */
    public function naSaitieZarieghistrirovany(TableNode $table)
    {
        $em   = $this->getEntityManager();
        $um   = $this->getUserManager();
        $repo = $em->getRepository('KinoskladMainBundle:User');

        foreach ($repo->findAll() as $user) {
            $em->remove($user);
        }
        $em->flush();

        foreach ($table->getHash() as $userHash) {
            $user = $um->createUser();
            $user->setUsername($userHash['user']);
            $user->setEmail($userHash['email']);
            $user->setPlainPassword($userHash['password']);
            $user->setRoles(explode(',', $userHash['roles']));
            $user->setEnabled(true);

            $um->updatePassword($user);
            $em->persist($user);
        }
        $em->flush();
    }

    /**
     * @Given /^на сайт добавлены жанры:$/
     */
    public function naSaitDobavlienyZhanry(TableNode $table)
    {
        $em   = $this->getEntityManager();
        $repo = $em->getRepository('KinoskladMainBundle:Genre');

        foreach ($repo->findAll() as $genre) {
            $em->remove($genre);
        }
        $em->flush();

        foreach ($table->getHash() as $genreHash) {
            $genre = new \Kinosklad\Bundle\MainBundle\Entity\Genre();
            $genre->setName($genreHash['name']);

            $em->persist($genre);
        }
        $em->flush();
    }

    /**
     * @Given /^я вхожу как пользователь "([^"]*)" с паролем "([^"]*)"$/
     */
    public function iaLoghiniusKakSParoliem($username, $password)
    {
        return array(
            new Step\Given('I am on "/login"'),
            new Step\When("fill in \"Username:\" with \"$username\""),
            new Step\When("fill in \"Password:\" with \"$password\""),
            new Step\When("I press \"Login\""),
        );
    }

    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    private function getUserManager()
    {
        return $this->getContainer()->get('fos_user.user_manager');
    }
}
