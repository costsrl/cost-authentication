<?php

namespace CostAuthentication\Fixture;

use CostAuthentication\Entity\Language;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CostAuthentication\Container\ContainerAwareTrait;
use CostAuthentication\Container\ContainerAwareInterface;


class LanguageFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    use ContainerAwareTrait;


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $oLanguage = new Language();
        $oLanguage->setCode(1);
        $oLanguage->setName("Italian");
        $oLanguage->setDefault(1);
        $manager->persist($oLanguage);
        $this->addReference("language-it", $oLanguage);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}

