<?php
namespace CostAuthentication\Form\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Interop\Container\ContainerInterface;
use CostAuthentication\Form\ChangePasswordFilter;


class ChangePasswordFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator){
        $changePasswordFilter = new ChangePasswordFilter($serviceLocator);
        return $changePasswordFilter;
    }
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $changePasswordFilter = new ChangePasswordFilter();
        return $changePasswordFilter;
    }
}

?>