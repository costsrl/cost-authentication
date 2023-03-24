<?php
namespace CostAuthentication\Service\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Session\SessionManager;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Container;

class SessionService implements FactoryInterface{
	
	const CONTAINER ='cost_authentication';
	
	public function createService(ServiceLocatorInterface $serviceLocator){
		$config = $serviceLocator->get('cost_authentication');
		if(isset($config['container'])){
			$container =$config;
		}
		else 
			$container = self::CONTAINER;
		
		return new Container($container);
	}

}
?>