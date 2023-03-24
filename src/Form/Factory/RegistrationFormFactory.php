<?php
namespace CostAuthentication\Form\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Form\RegistrationForm;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;



class RegistrationFormFactory implements FactoryInterface
{
    
    public function createService(ServiceLocatorInterface $serviceLocator){
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $form = new RegistrationForm('signup');
        $form->get('submit')->setValue('Sign up');
        $form->setHydrator(new DoctrineHydrator($entityManager,'CostAuthentication\Entity\User'));
        return $form;
        
    }
    
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $form = new RegistrationForm('signup');
        $form->get('submit')->setValue('Sign up');
        $form->setHydrator(new DoctrineHydrator($entityManager,'CostAuthentication\Entity\User'));
        return $form;
    }
    
}

?>