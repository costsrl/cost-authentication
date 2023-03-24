<?php
namespace CostAuthentication\Form;

use Laminas\Form\Form;

class RegistrationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Username',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));

        $this->add(array(
            'name' => 'displayName',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Display Name',
            ),
            'options' => array(
                'label' => 'DisplayName',
            ),
        ));

        $this->add(array(
            'name' => 'firstName',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'First Name',
            ),
            'options' => array(
                'label' => 'FirstName',
            ),
        ));

        $this->add(array(
            'name' => 'lastName',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Last Name',
            ),
            'options' => array(
                'label' => 'LastName',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'placeholder' =>'Email',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'Password',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'passwordConfirm',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'Confirm Password',
            ),
            'options' => array(
                'label' => 'passwordConfirm',
            ),
        ));

        $this->add(array(
            'type' => 'Laminas\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array(
                'placeholder' =>'Please verify you are human',
            ),
            'options' => array(
                'label' => ' ',
                'captcha' => new \Laminas\Captcha\Figlet(array(
                    'wordLen' => 3,
                )),
            ),
        ));
        
          

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Sign In',
                'class' => 'btn btn-lg btn-primary btn-block text-uppercase',
            ),
        ));
    }
}
