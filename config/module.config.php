<?php

namespace CostAuthentication;
use CostAuthentication\Authentication\Storage\StorageFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return array(
    'static_salt' => 'aFGQ475SDsdfsaf2342',
    'notification_user' => 'rennasalva@gmal.com',
    'controllers' => array(
        'invokables' => array(
            //'CostAuthentication\Controller\Index'        => 'CostAuthentication\Controller\IndexController',
            //'CostAuthentication\Controller\Registration' => 'CostAuthentication\Controller\RegistrationController'
        ),
        'factories' => array(
            Controller\RegistrationController::class    => Controller\Factory\RegistrationControllerFactory::class,
            Controller\IndexController::class           => Controller\Factory\IndexControllerFactory::class,
        )
    ),
    'view_helpers' => array(
        'factories' => array(
            'whoIs' => function ($sm) {
                $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
                $auth = $sm->get('Laminas\Authentication\AuthenticationService');
                $acl = $sm->get('acl');
                $helper = new \CostAuthentication\View\Helper\WhoIs($auth, $acl);
                return $helper;
            }
        ),
    ),
    'service_manager' => array(
        'services' =>[
            "role-fixture" => [
                ["name" => "Guest", "parent" => "", "def" => "role-guest"],
                ["name" => "Member", "parent" => "role-guest", "def" => "role-member"],
                ["name" => "Admin", "parent" => "role-member", "def" => "role-admin"]
            ]
        ],
        'factories' =>[
            'registration-form' => Form\Factory\RegistrationFormFactory::class,
            'registration-form-filter' => Form\Factory\RegistrationFilterFactory::class,
            'change-password-form' => Form\Factory\ChangePasswordFormFactory::class,
            'change-password-filter' => Form\Factory\ChangePasswordFilterFactory::class,
            'forgotten-password-form' => Form\Factory\ForgottenPasswordFormFactory::class,
            'forgotten-password-filter' => Form\Factory\ForgottenPasswordFilterFactory::class,
            'change-email-form' => Form\Factory\ChangeEmailFormFactory::class,
            'change-email-filter' => Form\Factory\ChangeEmailFilterFactory::class,
            'login-form' => Form\Factory\LoginFormFactory::class,
            'login-form-filter' => Form\Factory\LoginFilterFactory::class
        ]
    ),
    'doctrine_factories'=>[
        'authenticationstorage'=> StorageFactory::class,
    ],
    'form_elements' => array(
        'invokables' => array(),
        'factories' => array()
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\IndexController::class,
                                'action' => 'login',
                            ),
                        ),
                    ),
                ),
            ),
            'logout' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'logout',
                    ),
                ),
            ),
            'user' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\IndexController::class,
                                'action' => Controller\IndexController::class,
                            ),
                        ),
                    ),
                ),
            ),
            'changeEmail' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/change-email',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'changeEmail',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'changeEmail',
                            ),
                        ),
                    ),
                ),
            ),
            'changePassword' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/change-password',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'changePassword',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'changePassword',
                            ),
                        ),
                    ),
                ),
            ),
            'editProfile' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/edit-profile',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'editProfile',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'editProfile',
                            ),
                        ),
                    ),
                ),
            ),
            'logout' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'logout',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' =>Controller\IndexController::class,
                                'action' => 'logout',
                            ),
                        ),
                    ),
                ),
            ),
            'registration' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/signup',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'forgotten-password' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/forgotten-password',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'forgottenPassword',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'forgottenPassword',
                            ),
                        ),
                    ),
                ),
            ),
            'password-change-success' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/password-change-success',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'passwordChangeSuccess',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'passwordChangeSuccess',
                            ),
                        ),
                    ),
                ),
            ),
            'password-change-eror' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/password-change-error',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'passwordChangeError',
                    ),
                ),
            ),
            'registration-success' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/registration-success',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'registrationSuccess',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[...]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'registrationSuccess',
                            ),
                        ),
                    ),
                ),
            ),
            'confirm-email' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/confirm-email',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'confirmEmail',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'confirmEmail',
                            ),
                        ),
                    ),
                ),
            ),
            'confirm-email-change-password' => array(
                'type' => Literal::class,
                'options' => array(
                    'route' => '/confirm-email-change-password',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\RegistrationController::class,
                        'action' => 'confirmEmailChangePassword',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'CostAuthentication\Controller',
                                'controller' => Controller\RegistrationController::class,
                                'action' => 'confirmEmailChangePassword',
                            ),
                        ),
                    ),
                ),
            ),
            'cost-auth' => array(
                'type' => Literal::class,
                'options' => array(

                    // Change this to something specific to your module
                    'route' => '/costauth',
                    'defaults' => array(

                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'CostAuthentication\Controller',
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(

                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => Segment::class,
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'cost-authorization/layout/nav-menu' => __DIR__ . '/../view/cost-authorization/layout/nav-menu.phtml',
            'cost-authorization/registration/confirm-email-error' => __DIR__ . '/../view/cost-authorization/registration/confirm-email-error.phtml',
            'cost-authorization/registration/confirm-email-change-password-error' => __DIR__ . '/../view/cost-authorization/registration/confirm-email-change-password-error.phtml',
        ),
        'template_path_stack' => array(
            'cost-authorization' => __DIR__ . '/../view'
        ),

        'display_exceptions' => true,
    ),
    'doctrine' => [
        // 1) for Authentication
        'authentication' => [ // this part is for the Auth adapter from DoctrineModule/Authentication
            'orm_default' => [
                'object_manager' => 'Doctrine\ORM\EntityManager',
                // object_repository can be used instead of the object_manager key
                'identity_class' => 'CostAuthentication\Entity\User', //'Application\Entity\User',
                'identity_property' => 'username', // 'username', // 'email',
                'credential_property' => 'password', // 'password',
                'credential_callable' => function (Entity\User $user, $passwordGiven) {
                    if ($user->getPassword() == md5('aFGQ475SDsdfsaf2342' . $passwordGiven . $user->getPasswordSalt()) &&
                        $user->getState() == 1) {
                        return true;
                    } else {
                        return false;
                    }
                },
            ],
        ],
        // 2) standard configuration for the ORM from https://github.com/doctrine/DoctrineORMModule
        // http://www.jasongrimes.org/2012/01/using-doctrine-2-in-Laminas-framework-2/
        // ONLY THIS IS REQUIRED IF YOU USE Doctrine in the module
        'driver' => array(
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        ),
        'fixture' => [
            'CostAuthentication' => __DIR__ . '/../src/Fixture'
        ]
    ]
);
