<?php
namespace CostAuthentication\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use CostAuthentication\Form\LoginFilter;

class LoginFilterFactory implements FactoryInterface
{
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $loginFilter = new  LoginFilter($container);
        return $loginFilter;
    }
}

?>