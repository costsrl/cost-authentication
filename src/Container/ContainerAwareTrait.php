<?php
declare(strict_types=1);

namespace CostAuthentication\Container;

use Interop\Container\ContainerInterface;

trait ContainerAwareTrait
{
    /**
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
}