<?php
namespace CostAuthentication\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Controller\RegistrationController;
use CostAuthentication\Form\RegistrationForm;
use CostAuthentication\Form\RegistrationFilter;
use CostAuthentication\Form\ForgottenPasswordForm;
use CostAuthentication\Form\ForgottenPasswordFilter;
use CostAuthentication\Form\ChangeEmailForm;
use CostAuthentication\Form\ChangeEmailFilter;
use CostAuthentication\Form\ChangePasswordForm;
use CostAuthentication\Form\ChangePasswordFilter;
use CostAuthentication\Form\EditProfileForm;
use CostAuthentication\Form\EditProfileFilter;
use CostAuthentication\Authentication\Adapter\passwordAdapter as passwordAdapter;


class RegistrationControllerFactory implements FactoryInterface
{
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config                 = $container->get('Config');
        $translator             = $container->get('MvcTranslator');
        $passwordAdapter        = new passwordAdapter($config['costauthentication']);
        $registrationController = new RegistrationController();
        $registrationController->setServiceLocator($container);
        $registrationController->setTranslator($translator);
        $registrationController->setRegistrationFilter($container->get('registration-form-filter'));
        $registrationController->setRegistrationForm($container->get('registration-form'));
        $registrationController->setChangeEmailFilter($container->get('change-email-filter'));
        $registrationController->setChangeEmailForm($container->get('change-email-form'));
        $registrationController->setChangePasswordFilter($container->get('change-password-filter'));
        $registrationController->setChangePasswordForm($container->get('change-password-form'));
        $registrationController->setForgottenPasswordFilter($container->get('forgotten-password-filter'));
        $registrationController->setForgottenPasswordForm($container->get('forgotten-password-form'));
        $registrationController->setEntityManager($container->get('doctrine.entitymanager.orm_default'));
        $registrationController->setPasswordAdapter($passwordAdapter);
        return $registrationController;
    }
}

?>