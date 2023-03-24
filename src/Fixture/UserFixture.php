<?php

namespace CostAuthentication\Fixture;

use CostAuthentication\Entity\User;
use CostAuthentication\Entity\Language;
use CostAuthorization\Model\Entity\Roles as UserRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CostAuthentication\Container\ContainerAwareTrait;
use CostAuthentication\Container\ContainerAwareInterface;

class UserFixture extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{


    use ContainerAwareTrait;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $aRoleFixture = $this->container->get('role-fixture');
        foreach ($aRoleFixture as $key => $role) {
            $eRole = new UserRole();
            $eRole->setName($role["name"]);
            if ($role["parent"] != '') {
                $oParent = $this->getReference($role["parent"]);
                $eRole->setParent($oParent);
            }
            $manager->persist($eRole);
            $this->addReference($role["def"], $eRole);
        }


        $language = $this->getReference("language-it");
        $role = $this->getReference("role-member");
        $oMember = new User();
        $oMember->setUsername("member")->setDisplayName("Member")
            ->setPassword("ab10717c59bd84dbded1c2a46a959514")
            ->setPasswordSalt("p)*3'-)UpSWvyudV'~U<IG4,95H{+/iz8s/>!7sqiLidBO;INo")
            ->setEmail("member@cost.it")
            ->setState(1)
            ->setLanguage($language)
            ->setEmailConfirmed(1)
            ->setRole($role);
        $manager->persist($oMember);


        $role = $this->getReference("role-admin");
        $oAdmin = new User();
        $oAdmin->setUsername("admin")->setDisplayName("Admin")
            ->setPassword("ab10717c59bd84dbded1c2a46a959514")
            ->setPasswordSalt("p)*3'-)UpSWvyudV'~U<IG4,95H{+/iz8s/>!7sqiLidBO;INo")
            ->setEmail("admim@cost.it")
            ->setState(1)
            ->setLanguage($language)
            ->setEmailConfirmed(1)
            ->setRole($role);
        $manager->persist($oAdmin);


        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}

