<?php
/**
 * coolcsn * Index Controller
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 */

namespace CostAuthentication\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use CostAuthentication\Entity\User; // only for the filters
use CostAuthentication\Form\LoginForm;
use CostAuthentication\Form\LoginFilter;
use CostAuthentication\Options\ModuleOptions;

/**
 * <b>Authentication controller</b>
 * This controller has been build with educational purposes to demonstrate how authentication can be done
 */
class IndexController extends AbstractActionController
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var CostAuthentication\Form\LoginForm
     */
    protected $loginForm;

    /**
     *
     * @var CostAuthentication\Form\LoginFilter
     */
    protected $loginFilter;


    protected $ServiceLocator;


    /**
     * @return the $em
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return the $ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->ServiceLocator;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $ServiceLocator
     */
    public function setServiceLocator($ServiceLocator)
    {
        $this->ServiceLocator = $ServiceLocator;
    }

    /**
     * Index action
     *
     * The method show to users they are guests
     *
     * @return Laminas\View\Model\ViewModelarray navigation menu
     */
    public function indexAction()
    {
        return new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
    }

    /**
     * Log in action
     *
     * The method uses Doctrine Entity Manager to authenticate the input data
     *
     * @return Laminas\View\Model\ViewModel|array login form|array messages|array navigation menu
     */
    public function loginAction()
    {
        $sm = $this->getServiceLocator();
        $request = $this->getRequest();
        $messages = null;

        $aAuthentication = $sm->get('Config')['costauthentication'];
        if ($user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $form = $this->getLoginForm();
        $form->get('submit')->setValue('Log in');

        if ($request->isPost()) {
            $form->setInputFilter($this->getLoginFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {

                $data = $form->getData();
                $authService = $this->getServiceLocator()->get('Laminas\Authentication\AuthenticationService');
                $adapter = $authService->getAdapter();
                $usernameOrEmail = $data['usernameOrEmail'];

                // check for email first
                if ($user = $this->getEntityManager()->getRepository(User::class)->findOneBy(array('email' => $usernameOrEmail))) {
                    // Set username to the input array in place of the email
                    $data['usernameOrEmail'] = $user->getUsername();
                }
                elseif($user = $this->getEntityManager()->getRepository(User::class)->findOneBy(array('username' => $usernameOrEmail))) {
                    // Set username to the input array in place of the email
                    $data['usernameOrEmail'] = $user->getUsername();
                }

                $adapter->setIdentity($data['usernameOrEmail']);
                $adapter->setCredential($data['password']);
                $authResult = $authService->authenticate();

                if ($authResult->isValid()) {
                    $identity = $authResult->getIdentity();
                    $storage = $authService->getStorage();
                    $storage->write($identity);
                    $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
                    if ($data['rememberme']) {
                        //$sessionManager = new \Laminas\Session\SessionManager();
                        //$sessionManager->rememberMe($time);
                        $storage->setRememberMe(1,$time);
                    }

                    $this->getEventManager()->trigger('auth_access_user',$user);
                    $this->flashmessenger()->addSuccessMessage(sprintf('Welcome %s. You are now logged in.',$user->getLastName().'-'.$user->getFirstName()));
                    return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
                }
                foreach ($authResult->getMessages() as $message) {
                    $messages .= "$message\n";
                }
            }
        }

        return new ViewModel(array(
                                'error' => 'Your authentication credentials are not valid',
                                'form'	=> $form,
                                'messages' => $messages,
                                'navMenu' => $this->getOptions()->getNavMenu(),
                                'linkToRegister'=>  $aAuthentication['linkRegister'],
                                'linkToForgetPwd'=> $aAuthentication['linkForgotPassword'],
                            ));
    }

    /**
     * Log out action
     *
     * The method destroys session for a logged user
     *
     * @return redirect to specific action
     */
    public function logoutAction()
    {
        $identity = null;
        $authService = $this->getServiceLocator()->get('Laminas\Authentication\AuthenticationService');

        // @todo Set up the auth adapter, $authAdapter
        if ($authService->hasIdentity()) {
            $identity = $authService->getIdentity();
        }
        $authService->clearIdentity();
        $storage = $authService->getStorage();

        $storage->forgetMe();
        /*
        $sessionManager = new \Laminas\Session\SessionManager();
        $sessionManager->forgetMe();
        */

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());

    }

    /**
     * get entityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }

        return $this->em;
    }

     /**
     * set options
     *
     * @return IndexController
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('cost_authentication_module_options'));
        }

        return $this->options;
    }

    public function getLoginForm()
    {
        return $this->loginForm;
    }

    /**
     *
     * @param CostAuthentication\Form\LoginForm $loginForm
     */
    public function setLoginForm($loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    public function getLoginFilter()
    {
        return $this->loginFilter;
    }

    /**
     *
     * @param CostAuthentication\Form\LoginFilter $loginFilter
     */
    public function setLoginFilter($loginFilter)
    {
        $this->loginFilter = $loginFilter;
        return $this;
    }

}
