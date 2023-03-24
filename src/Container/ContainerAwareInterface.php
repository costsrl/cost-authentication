<?php
declare(strict_types=1);

namespace CostAuthentication\Container;


use Interop\Container\ContainerInterface;

interface ContainerAwareInterface
{
    /**
     * @param ContainerInterface $container
     * @return null
     */
    public function setContainer(ContainerInterface $container);
}