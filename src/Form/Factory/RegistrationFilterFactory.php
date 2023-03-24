<?php
namespace CostAuthentication\Form\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\RegistrationFilter;
use Interop\Container\ContainerInterface;


class RegistrationFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator){
        $registrationFilter = new  RegistrationFilter($serviceLocator);
        return $registrationFilter;
    }
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $registrationFilter = new  RegistrationFilter($container);
        return $registrationFilter;
    }
    
}

?>