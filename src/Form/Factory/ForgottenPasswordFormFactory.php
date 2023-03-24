<?php
namespace CostAuthentication\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\ForgottenPasswordForm;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;

class ForgottenPasswordFormFactory implements FactoryInterface
{
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $forgottenPasswordForm = new ForgottenPasswordForm();
        return $forgottenPasswordForm;
    }
}

?>