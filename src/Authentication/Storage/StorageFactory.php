<?php
namespace CostAuthentication\Authentication\Storage;

use DoctrineModule\Authentication\Storage\ObjectRepository;
use DoctrineModule\Service\AbstractFactory;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostAuthentication\Authentication\Storage\StorageRepository;

/**
 * Factory to create authentication storage object.
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StorageFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var $options \DoctrineModule\Options\Authentication */
        $options = $this->getOptions($container, 'authentication');
        

        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($container->get($objectManager));
        }

        if (is_string($storage = $options->getStorage())) {
            $options->setStorage($container->get($storage));
        }

        return new StorageRepository($options);
    }

    /**
     * {@inheritDoc}
     *
     * @return \DoctrineModule\Authentication\Storage\ObjectRepository
     */
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, StorageRepository::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionsClass() : string
    {
        return 'DoctrineModule\Options\Authentication';
    }
}
