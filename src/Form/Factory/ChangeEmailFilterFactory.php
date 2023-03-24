<?php
namespace CostAuthentication\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\ChangeEmailFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ChangeEmailFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator){
        $changeEmailFilter = new ChangeEmailFilter($serviceLocator);
        return $changeEmailFilter;
    }
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
         $changeEmailFilter = new ChangeEmailFilter($container);
         return $changeEmailFilter;
    }
}

?>