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
     * @BeforeScenario
     */
    public function cleanDatabase()
    {
        foreach (array('GenreTranslation', 'Genre', 'FilmTranslation', 'Film', 'User') as $entity) {
            $this->getEntityManager()->getRepository("KinoskladMainBundle:$entity")
                ->createQueryBuilder('e')
                ->delete()
                ->getQuery()
                ->execute();
        }
    }

    /**
     * @Given /^на сайте зарегистрированы:$/
     */
    public function naSaitieZarieghistrirovany(TableNode $table)
    {
        $em = $this->getEntityManager();
        $um = $this->getUserManager();

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
        $em = $this->getEntityManager();

        foreach ($table->getHash() as $genreHash) {
            $genre = new \Kinosklad\Bundle\MainBundle\Entity\Genre();
            $genre->setName($genreHash['name']);

            $em->persist($genre);
        }
        $em->flush();
    }

    /**
     * @Given /^на сайт добавлены фильмы:$/
     */
    public function naSaitDobavlienyFilMy(TableNode $table)
    {
        $em = $this->getEntityManager();

        foreach ($table->getHash() as $filmHash) {
            $film = new \Kinosklad\Bundle\MainBundle\Entity\Film();
            $film->setName($filmHash['name']);
            $film->setLength(intval($filmHash['length']));
            $film->setCountry($filmHash['country']);
            $film->setPremiere(new \DateTime($filmHash['premiere']));
            $film->setDescription($filmHash['description']);

            $author = $em->getRepository('KinoskladMainBundle:User')
                ->findOneByUsername($filmHash['author']);

            $film->setAuthor($author);

            $em->persist($film);
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
