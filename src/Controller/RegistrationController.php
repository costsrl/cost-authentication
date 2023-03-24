<?php
/**
 * @author renato salvatori
 */
namespace CostAuthentication\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mail\Message;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use CostAuthentication\Entity\User;
use CostAuthentication\Entity\Role;
use CostAuthentication\Entity\Language;
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
use CostAuthentication\Options\ModuleOptions;
use CostAuthorization\Model\Entity\Roles;

/**
 * <b>Registration controller</b>
 * This controller has been build with educational purposes to demonstrate how registration can be done
 */
class RegistrationController extends AbstractActionController
{

    /**
     *
     * @var ModuleOptions
     */
    protected $options;

    protected $registrationForm;

    protected $registrationFilter;

    protected $changeEmailForm;

    protected $changeEmailFilter;

    protected $changePasswordForm;

    protected $changePasswordFilter;

    protected $forgottenPasswordForm;

    protected $forgottenPasswordFilter;

    protected $passwordAdapter;

    protected $entityManager;

    protected $ServiceLocator;

    protected $translator;

    /**
     * @return the $translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param field_type $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return the $ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->ServiceLocator;
    }

    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $ServiceLocator
     */
    public function setServiceLocator($ServiceLocator)
    {
        $this->ServiceLocator = $ServiceLocator;
    }

    /**
     * Retrieve action from CRUD
     *
     * The method uses Doctrine Entity Manager to retrieve the Entities from the virtual database
     *
     * return Laminas\View\Model\ViewModel|array colection of objects
     */
    public function indexAction()
    {
        $translate      = $this->getTranslator();
        $request        = $this->getRequest();
        $entityManager  = $this->getEntityManager();
        if (! $oUser = $this->identity()) {
            $oUser = new User(); // CostAuthentication\Entity\User
            $form = $this->getRegistrationForm();
            $form->bind($oUser);
            if ($request->isPost()) {
                $data = $request->getPost();
                $useAll = false;
                if (isset($data['displayName']) && isset($data['firstName']) && isset($data['lastName'])) {
                    $useAll = true;
                }
                foreach ($data as $key => $value) {
                    if ($key === 'displayName' && $value !== '' && $useAll == false) {
                        $data["firstName"] = '-';
                        $data["lastName"] = '-';
                        break;
                    }
                    if (($key === 'firstName' && $value !== '' && $useAll == false)) {
                        $data["displayName"] = '-';
                        break;
                    }
                }
                $form->setInputFilter($this->getServiceLocator()->get('registration-form-filter'));
                $form->setData($data);

                if ($form->isValid()) {
                    $this->prepareData($oUser);
                    $this->flashMessenger()->addMessage($oUser->getEmail());
                    $entityManager->persist($oUser);
                    $entityManager->flush();
                    $result = $this->sendConfirmationEmail($oUser);
                    return $this->redirect()->toRoute('registration-success');
                }
                else{
                   //
                }
            }


            return new ViewModel(array(
                'form' => $form,
                'navMenu' => $this->getOptions()->getNavMenu()
            ));
        } else {
            return $this->redirect()->toRoute($this->getOptions()
                ->getLoginRedirectRoute());
        }
    }

    public function changeEmailAction()
    {
        $entityManager = $this->getEntityManager();
        if ($user = $this->identity()) {
            //$form = new ChangeEmailForm();
            $form = $this->getChangeEmailForm();
            $form->get('submit')->setValue('Change email');
            $request = $this->getRequest();
            $message = null;
            if ($request->isPost()) {
                $form->setInputFilter($this->getChangeEmailFilter());
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $currentPassword = $data['currentPassword'];
                    $newMail = $data['newEmail'];
                    $originalPassword = $user->getPassword();
                    $comparePassword = $this->encryptPassword($this->getOptions()
                        ->getStaticSalt(), $currentPassword, $user->getPasswordSalt());

                    if ($originalPassword == $comparePassword) {
                        $email = $user->setEmail($newMail);
                        $message = 'Your email has been changed to ' . $newMail . '.';

                        $entityManager->persist($user);
                        $entityManager->flush();
                    } else {
                        $message = 'Your current password is not correct.';
                    }
                }
            }

            return new ViewModel(array(
                'form' => $form,
                'navMenu' => $this->getOptions()->getNavMenu(),
                'message' => $message
            ));
        } else {
            return $this->redirect()->toRoute($this->getOptions()
                ->getLogoutRedirectRoute());
        }
    }

    public function changePasswordAction()
    {
        $entityManager = $this->getEntityManager();
        if ($user = $this->identity()) {
            //$form = new ChangePasswordForm();
            $form = $this->getChangePasswordForm();
            $form->get('submit')->setValue('Change password');
            $request = $this->getRequest();
            $message = null;
            if ($request->isPost()) {
                $form->setInputFilter($this->getChangePasswordFilter());
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $currentPassword = $data['currentPassword'];
                    $newPassword = $data['newPassword'];
                    $originalPassword = $user->getPassword();
                    $comparePassword = $this->encryptPassword($this->getOptions()
                        ->getStaticSalt(), $currentPassword, $user->getPasswordSalt());

                    if ($originalPassword == $comparePassword) {
                        $password = $this->encryptPassword($this->getOptions()
                            ->getStaticSalt(), $newPassword, $user->getPasswordSalt());
                        $email = $user->setPassword($password);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $message = 'Your password has been changed successfully.';
                    } else {
                        $message = 'Your current password is not correct.';
                    }
                }
            }

            return new ViewModel(array(
                'form' => $form,
                'navMenu' => $this->getOptions()->getNavMenu(),
                'message' => $message
            ));
        } else {
            return $this->redirect()->toRoute($this->getOptions()
                ->getLogoutRedirectRoute());
        }
    }

    public function editProfileAction()
    {
        $entityManager = $this->getEntityManager();
        if ($user = $this->identity()) {
            $form = new EditProfileForm();
            $form->get('submit')->setValue('Save changes');
            $email = $user->getEmail();
            $username = $user->getUsername();
            $displayname = $user->getDisplayName();
            $request = $this->getRequest();
            $message = null;
            if ($request->isPost()) {
                $form->setInputFilter(new EditProfileFilter($this->getServiceLocator()));
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $currentDisplayname = $user->getDisplayName();
                    $newDisplayname = $data['displayName'];
                    if ($currentDisplayname != $newDisplayname) {
                        $newnewdisplayname = $user->setDisplayName($newDisplayname);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $message = 'Your display name has been changed to: ' . $newDisplayname . '.';
                    }
                }
            }

            return new ViewModel(array(
                'form' => $form,
                'email' => $email,
                'username' => $username,
                'message' => $message,
                'displayname' => $displayname,
                'navMenu' => $this->getOptions()->getNavMenu()
            ));
        } else {
            return $this->redirect()->toRoute($this->getOptions()
                ->getLogoutRedirectRoute());
        }
    }

    public function registrationSuccessAction()
    {
        $email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $key => $value) {
                $email .= $value;
            }
        }
        if ($email != null) {
            return new ViewModel(array(
                'email' => $email,
                'navMenu' => $this->getOptions()->getNavMenu()
            ));
        } else {
            return $this->redirect()->toRoute('login');
        }
    }

    public function confirmEmailAction()
    {
        $token = $this->params()->fromRoute('id');
        $viewModel = new ViewModel(array(
            'navMenu' => $this->getOptions()->getNavMenu()
        ));
        try {
            $entityManager = $this->getEntityManager();
            if ($token !== '' && $user = $entityManager->getRepository(CostAuthentication\Entity\User::class)->findOneBy(array(
                'registrationToken' => $token
            ))) {
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true))); // change immediately taken to prevent multiple requests to db
                $user->setState(1);
                $user->setEmailConfirmed(1);
                $entityManager->persist($user);
                $entityManager->flush();
            } else {
                return $this->redirect()->toRoute('login');
            }
        } catch (\Exception $e) {
            $viewModel->setTemplate('cost-authentication/registration/confirm-email-error');
        }

        return $viewModel;
    }

    public function confirmEmailChangePasswordAction()
    {
        $token = $this->params()->fromRoute('id');
        $viewModel = new ViewModel(array(
            'navMenu' => $this->getOptions()->getNavMenu()
        ));
        try {
            $entityManager = $this->getEntityManager();
            if ($token !== '' && $user = $entityManager->getRepository(User::class)->findOneBy(array(
                'registrationToken' => $token
            ))) {
                $user->setRegistrationToken(md5(uniqid(mt_rand(), true))); // change immediately taken to prevent multiple changing of password
                $password = $this->generatePassword();
                $passwordHash = $this->encryptPassword($this->getOptions()
                    ->getStaticSalt(), $password, $user->getPasswordSalt());
                $user->setPassword($passwordHash);
                $email = $user->getEmail();
                $username = $user->getUsername();
                $this->sendPasswordByEmail($username, $email, $password);
                $this->flashMessenger()->addMessage($email);
                $entityManager->persist($user);
                $entityManager->flush();
                $viewModel = new ViewModel(array(
                    'email' => $email,
                    'navMenu' => $this->getOptions()->getNavMenu()
                ));
            } else {
                return $this->redirect()->toRoute('user');
            }
        } catch (\Exception $e) {
            $viewModel->setTemplate('cost-authentication/registration/confirm-email-change-password-error', array(
                'navMenu' => $this->getOptions()
                    ->getNavMenu()
            ));
        }

        return $viewModel;
    }

    /*
     * without Confirmation email; Only send a new password;
     * public function forgottenPasswordAction()
     * {
     * $form = new ForgottenPasswordForm();
     * $form->get('submit')->setValue('Send');
     * $request = $this->getRequest();
     * if ($request->isPost()) {
     * $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
     * $form->setData($request->getPost());
     * if ($form->isValid()) {
     * $data = $form->getData();
     * $email = $data['email'];
     * $entityManager = $this->getEntityManager();
     * $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $email));
     * $password = $this->generatePassword();
     * $passwordHash = $this->encryptPassword($this->getOptions()->getStaticSalt(), $password, $user->getPasswordSalt());
     * $this->sendPasswordByEmail($email, $password);
     * $this->flashMessenger()->addMessage($email);
     * $user->setPassword($passwordHash);
     * $entityManager->persist($user);
     * $entityManager->flush();
     *
     * return $this->redirect()->toRoute('default', array('controller'=>'registration', 'action'=>'password-change-success'));
     * }
     * }
     *
     * return new ViewModel(array('form' => $form));
     * }
     *
     */

    /*
     * public function forgottenPasswordAction()
     * {
     * $form = new ForgottenPasswordForm();
     * $form->get('submit')->setValue('Send');
     * $request = $this->getRequest();
     * if ($request->isPost()) {
     * $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
     * $form->setData($request->getPost());
     * if ($form->isValid()) {
     * $data = $form->getData();
     * $email = $data['email'];
     * $entityManager = $this->getEntityManager();
     * $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $email));
     * $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
     * $this->sendConfirmationEmailChangePassword($user);
     * $this->flashMessenger()->addMessage($user->getEmail());
     * $entityManager->persist($user);
     * $entityManager->flush();
     *
     * return $this->redirect()->toRoute('password-change-success');
     * }
     *
     * }
     *
     * return new ViewModel(array('form' => $form));
     * }/
     */
    public function forgottenPasswordAction()
    {
        //$form = new ForgottenPasswordForm();
        $form = $this->getForgottenPasswordForm();
        $form->get('submit')->setValue('Send reset email');
        $request = $this->getRequest();
        $message = null;
        if ($request->isPost()) {
            $form->setInputFilter($this->getForgottenPasswordFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $usernameOrEmail = $data['usernameOrEmail'];
                $entityManager = $this->getEntityManager();
                if (
                    ($user = $entityManager->getRepository(User::class)->findOneBy(array(
                    'email' => $usernameOrEmail))) ||
                    ($user = $entityManager->getRepository(User::class)->findOneBy(array(
                    'username' => $usernameOrEmail)))
                    ) {
                    $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                    $entityManager->flush();
                    $result = $this->sendConfirmationEmailChangePassword($user);
                    if($result["status"]=="send"){
                        $this->flashMessenger()->addMessage($user->getEmail());
                        return $this->redirect()->toRoute('password-change-success');
                    }
                    else{
                        $this->flashMessenger()->addMessage($result["message"]);
                        return $this->redirect()->toRoute('password-change-eror');
                    }

                }
                else {
                    $message = 'The username or email is not valid!';
                }
            }


        }

        return new ViewModel(array(
            'form' => $form,
            'navMenu' => $this->getOptions()->getNavMenu(),
            'message' => $message
        ));
    }

    public function passwordChangeSuccessAction()
    {
        $email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $key => $value) {
                $email .= $value;
            }
        }
        if ($email != null) {
            return new ViewModel(array(
                'email' => $email,
                'navMenu' => $this->getOptions()->getNavMenu()
            ));
        } else {
            return $this->redirect()->toRoute('user');
        }
    }


    public function passwordChangeErrorAction()
    {
        $email = null;
        $flashMessenger = $this->flashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $key => $value) {
                $email .= $value;
            }
        }
        if ($email != null) {
            return new ViewModel(array(
                'email' => $email,
                'navMenu' => $this->getOptions()->getNavMenu()
            ));
        } else {
            return $this->redirect()->toRoute('user');
        }
    }

    /**
     *
     * @param CostAuthentication\Entity\User $user
     */
    public function prepareData($user)
    {
        $passwordBcrypt = $this->getPasswordAdapter()->setPassword($user->getPassword())->encryptPassword();


        $user->setState(1); //enabled
        $user->setPasswordSalt($this->generateDynamicSalt());
        $user->setPassword($this->encryptPassword($this->getOptions()
            ->getStaticSalt(), $user->getPassword(), $user->getPasswordSalt()));
        $entityManager = $this->getEntityManager();
        $role = $entityManager->getRepository(Roles::class)->findOneBy(array(
            'name' => Roles::defaultRole
        ));
        $user->setRole($role);
        $language = $entityManager->find(Language::class, 1);
        $user->setLanguage($language);
        $user->setRegistrationDate(new \DateTime());
        $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
        $user->setEmailConfirmed(0);

        return $user;
    }

    public function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i ++) {
            $dynamicSalt .= chr(rand(33, 126));
        }

        return $dynamicSalt;
    }

    public function encryptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }

    /**
     *
     * @param number $l
     * @param number $c
     * @param number $n
     * @param number $s
     * @return boolean|string
     */
    protected function generatePassword($l = 8, $c = 0, $n = 0, $s = 0)
    {
        // get count of all required minimum special chars
        $count = $c + $n + $s;
        $out = '';
        // sanitize inputs; should be self-explanatory
        if (! is_int($l) || ! is_int($c) || ! is_int($n) || ! is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);

            return false;
        } elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);

            return false;
        } elseif ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);

            return false;
        } elseif ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);

            return false;
        } elseif ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);

            return false;
        } elseif ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);

            return false;
        }

        // all inputs clean, proceed to build password

        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        // build the base password of all lower-case letters
        for ($i = 0; $i < $l; $i ++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if ($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = array();

            // add required special character(s) to second array
            for ($i = 0; $i < $c; $i ++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for ($i = 0; $i < $n; $i ++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for ($i = 0; $i < $s; $i ++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    /**
     *
     * @param CostAuthentication\Entity\User $user
     */
    public function sendConfirmationEmail($user)
    {
        $aMessage = [];
        $config_module = $this->getServiceLocator()->get('config');
        $translator = $this->getServiceLocator()->get('translator');
        $addfrom = $config_module['notification_user'];

        $hostname = $_SERVER['HTTP_HOST'];
        $fullLink = "http://" . $hostname . $this->url()->fromRoute('confirm-email/default', array(
            'controller' => 'registration',
            'action' => 'confirm-email',
            'id' => $user->getRegistrationToken()
        ));

        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $aServerVar = $this->getRequest()->getServer(); // Server vars

        // You will receive confirmation of activation of your account as soon as possible
        $message->addTo($user->getEmail())
            ->addFrom($addfrom)
            ->setSubject($translator->translate('Welcome'))
            ->setBody($translator->translate("Messege_Activation_Account"));
        $transport->send($message);


        try {
            $transport->send($message);
            $aMessage = [
                "status" => "send"
            ];
        } catch (\Throwable $e) {
            $aMessage = [
                "status" => "fail",
                "message" => $e->getmessage()
            ];
        }


        // lato amministratore
        // Registrazione Nuovo Utente
        $body = sprintf($translator->translate("New_User_Registration") . "  %s %s Email: %s", $user->getLastName(), $user->getFirstName(), $user->getEmail());

        $AdministratorMessage = new Message();
        $AdministratorMessage->addTo($addfrom)
            ->addFrom($addfrom)
            ->setSubject($translator->translate("Notify_User_Registration"))
            ->setBody($body);

        try {
            $transport->send($AdministratorMessage);
            $aMessage = [
                "status" => "send"
            ];
        } catch (\Throwable $e) {
            $aMessage = [
                "status" => "fail",
                "message" => $e->getmessage()
            ];
        }

        return $aMessage;
    }

    /**
     *
     * @param CostAuthentication\Entity\User $user
     */
    public function sendConfirmationEmailChangePassword($user)
    {
        $aMessage = [];
        $config_module = $this->getServiceLocator()->get('config');
        $addfrom = $config_module['notification_user'];
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();

        $hostname = $_SERVER['HTTP_HOST'];
        $fullLink = "http://" . $hostname . $this->url()->fromRoute('confirm-email-change-password/default', array(
            'controller' => 'registration',
            'action' => 'confirm-email-change-password',
            'id' => $user->getRegistrationToken()
        ));

        $this->getRequest()->getServer();
        $message->addTo($user->getEmail())
            ->addFrom($addfrom)
            ->setSubject('Please, confirm your request to change password!')
            ->setBody('Hi, ' . $user->getUsername() . ". Please, follow " . $fullLink . " to confirm your request to change password.");

        try {
            $transport->send($message);
            $aMessage = ["status"=>"send"];
        } catch (\Throwable $e) {
            $aMessage = ["status"=>"fail","message"=>$e->getmessage()];
        }

        return $aMessage;
    }

    /**
     *
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function sendPasswordByEmail($username, $email, $password)
    {
        $aMessage = [];
        $config_module = $this->getServiceLocator()->get('config');
        $addfrom = $config_module['notification_user'];
        $hostname = $_SERVER['HTTP_HOST'];
        $fullLink = "http://" . $hostname;

        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer(); // Server vars
        $message->addTo($email)
            ->addFrom($addfrom)
            ->setSubject('Your password has been changed!')
            ->setBody('Hello again ' . $username . '. Your new password is: ' . $password . '. Please, follow ' . $fullLink . '/login/ to log in with your new password.');
        try {
            $transport->send($message);
            $message = [
                "status" => "send"
            ];
        } catch (\Throwable $e) {
            $aMessage = [
                "status" => "send",
                "message" => $e->getmessage()
            ];
        }

        return $aMessage;
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
        if (! $this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()
                ->get('cost_authentication_module_options'));
        }

        return $this->options;
    }

    /**
     *
     */
    public function getRegistrationForm()
    {
        return $this->registrationForm;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\RegistrationForm $registratioForm
     */
    public function setRegistrationForm($registratioForm)
    {
        $this->registrationForm = $registratioForm;
        return $this;
    }

    public function getRegistrationFilter()
    {
        return $this->registrationFilter;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\RegistrationFilter $registrationFilter
     */
    public function setRegistrationFilter($registrationFilter)
    {
        $this->registrationFilter = $registrationFilter;
        return $this;
    }


    public function getChangeEmailForm()
    {
        return $this->changeEmailForm;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\ChangeEmailForm $changeEmailForm
     */
    public function setChangeEmailForm($changeEmailForm)
    {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }

    public function getChangeEmailFilter()
    {
        return $this->changeEmailFilter;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\ChangeEmailFilter $changeEmailForm
     */
    public function setChangeEmailFilter($changeEmailFilter)
    {
        $this->changeEmailFilter = $changeEmailFilter;
        return $this;
    }

    public function getChangePasswordForm()
    {
        return $this->changePasswordForm;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\changePasswordForm $changePasswordForm
     */
    public function setChangePasswordForm($changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    public function getChangePasswordFilter()
    {
        return $this->changePasswordFilter;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\ChangePasswordFilter $changePasswordForm
     */
    public function setChangePasswordFilter($changePasswordFilter)
    {
        $this->changePasswordFilter = $changePasswordFilter;
        return $this;
    }


    public function getForgottenPasswordForm()
    {
        return $this->forgottenPasswordForm;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\ForgottenPasswordForm $forgottenPasswordForm
     */
    public function setForgottenPasswordForm($forgottenPasswordForm)
    {
        $this->forgottenPasswordForm = $forgottenPasswordForm;
        return $this;
    }


    public function getForgottenPasswordFilter()
    {
        return $this->forgottenPasswordFilter;
    }

    /**
     *
     * @param Cost\CostAuthentication\Form\ForgottenPasswordFilter $forgottenPasswordForm
     */
    public function setForgottenPasswordFilter($forgottenPasswordFilter)
    {
        $this->forgottenPasswordFilter = $forgottenPasswordFilter;
        return $this;
    }




    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getPasswordAdapter()
    {
        return $this->passwordAdapter;
    }

    public function setPasswordAdapter(\CostAuthentication\Authentication\Adapter\passwordAdapterInterface $passwordAdapter)
    {
        $this->passwordAdapter = $passwordAdapter;
        return $this;
    }





}
