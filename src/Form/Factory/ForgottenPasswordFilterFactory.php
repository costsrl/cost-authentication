<?php
namespace CostAuthentication\Form\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use CostAuthentication\Form\ForgottenPasswordFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Interop\Container\ContainerInterface;


class ForgottenPasswordFilterFactory implements FactoryInterface
{
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $oUserRepository = $container->get('doctrine.entitymanager.orm_default')->getRepository('CostAuthentication\Entity\User');
        $forgottenPasswordFilter= new  ForgottenPasswordFilter($oUserRepository);
        return $forgottenPasswordFilter;
    }
}

?>