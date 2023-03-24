<?php
namespace CostAuthentication\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\ChangeEmailForm;

class ChangeEmailFormFactory implements FactoryInterface
{
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $changeEmailForm = new ChangeEmailForm('ChangeEmailForm');
        return $changeEmailForm;
    }
    
    
}

?>