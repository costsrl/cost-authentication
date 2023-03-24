<?php
namespace CostAuthentication\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface
{
    /*public function createService(ServiceLocatorInterface $serviceLocator){
        $oServiceManager = $serviceLocator->getServiceLocator();
        $indexController = new IndexController();
        $indexController->setServiceLocator($container);
        $indexController->setLoginFilter($oServiceManager->get('login-form-filter'));
        $indexController->setLoginForm($oServiceManager->get('login-form'));
        return $indexController;
    }*/
    
    /*
    * @param ContainerInterface $container
    * @param string $requestedName
    * @param null|array $options
    * @return Translator
    */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $indexController = new IndexController();
        $indexController->setLoginFilter($container->get('login-form-filter'));
        $indexController->setLoginForm($container->get('login-form'));
        $indexController->setEm($container->get('doctrine.entitymanager.orm_default'));
        $indexController->setServiceLocator($container);
        return $indexController;
    }
}

?>