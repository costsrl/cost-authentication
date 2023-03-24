<?php
namespace CostAuthentication;

// Add this for SMTP transport
use CostAuthentication\Command\FixturesLoadCommand;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\ModuleManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mail\Message;
use CsnUser\Form\AdminUserForm;
use CsnUser\Form\AdminUserFilter;
use CsnUser\Form\AdminFormSearch;
use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\EventManager;
use Laminas\Console\Request as ConsoleRequest;


class Module
{

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager()->getSharedManager();
        $events->attach('doctrine', 'loadCli.post', [$this, 'addFixturesLoadCommand']);
    }
    
	public function onBootstrap(MvcEvent $event)
    {
    	$services              = $event->getApplication()->getServiceManager();
    	$translator            = $services->get('MvcTranslator');
    	$sharedEventManager    = $event->getApplication()->getEventManager()->getSharedManager();

    	// gestione invio email notifica
    	$sharedEventManager->attach(__NAMESPACE__,'send_confirmation_email',function($event) use ($services,$translator){
    		$user             = $event->getTarget();
    		$params           = $event->getParams();
    		$config_module    = $services->get('config');
    		$addfrom          = $config_module['notification_user'];
    		$transport        = $services->get('mail.transport');
    		$message          = new Message();
    		//$message->addTo($user->getEmail())
    			
    		$sMsgNotify = $translator->translate("Dear customer %s %s you can now access to our Portal: Username: %s");
    		$body=sprintf($sMsgNotify,
    				$user->getLastName(),
    				$user->getFirstName(),
    		        $user->getUsername()
    		);
    			
    		$message->addTo($user->getEmail())
    		->addFrom($addfrom)
    		->setSubject($translator->translate('Account activation confirmation.!'))
    		->setBody($body);
    		$transport->send($message);
    		//die('message send');
    		});
    	
    	// gestione auth_success_login
    	$sharedEventManager->attach(__NAMESPACE__,'auth_access_user',function($event) use ($services,$translator){
    			$user = $event->getTarget();
    			$em = $services->get('doctrine.entitymanager.orm_default');
    			$user->setLastlogin(new \DateTime());
    			$em->flush(); // confirm data
    		});
	}

	
	
	public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'cost_authentication_module_options' => function ($container) {
                    $config = $container->get('Config');
                    return new Options\ModuleOptions(isset($config['costauthentication']) ? $config['costauthentication'] : array());
                },
                'cost_authentication_session'=>'CostAuthentication\Service\Factory\SessionService',
                // taken from DoctrineModule on GitHub
                // Please note that I am using here a Laminas\Authentication\AuthenticationService name, but it can be anything else
                // However, using the name Laminas\Authentication\AuthenticationService will allow it to be recognised by the ZF2 view helper.
                // the configuration of doctrine.authenticationservice.orm_default is in module.config.php
                'Laminas\Authentication\AuthenticationService' => function($container) {
                    //'doctrine_authenticationservice'  => function($serviceManager) {
                    // If you are using DoctrineORMModule:
                    return $container->get('doctrine.authenticationservice.orm_default');
                    // If you are using DoctrineODMModule:
                    //- return $serviceManager->get('doctrine.authenticationservice.odm_default');
                },
                // Add this for SMTP transport
                'mail.transport' => function (ServiceManager $container) {
                    $config = $container->get('Config');
                    $transport = new Smtp();
                    $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                }
            ),
            'services'=>array(
                'session_config'=>'cost_authentication',
            )
        );
    }

    /**
     * @param EventInterface $event
     */
    public function addFixturesLoadCommand(EventInterface $event)
    {
        /* @var \Symfony\Component\Console\Application $application */
        $application = $event->getTarget();

        /* @var \Interop\Container\ContainerInterface $container */
        $container = $event->getParam('ServiceManager');
        $fixturesLoadCommand = new FixturesLoadCommand($container);
        $application->add($fixturesLoadCommand);
    }
}
