<?php
namespace CostAuthentication\Form\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\LoginForm;
use Interop\Container\ContainerInterface;

class LoginFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator){
        $loginForm = new  LoginForm('LoginForm');
        return $loginForm;
    }
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $loginForm = new  LoginForm('LoginForm');
        return $loginForm;
    }
}

?>