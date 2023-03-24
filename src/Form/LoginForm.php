<?php
namespace CostAuthentication\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'usernameOrEmail',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Username or email',
                'class' =>'form-control'
            ),
            'options' => array(
                'label' => 'Username or Email',
                //'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'Password',
                'class' =>'form-control'
            ),
            'options' => array(
                'label' => 'Password',
                //'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Remember me? ',
                'label_attributes' => array(
                    'class'  => 'class="checkbox-inline"'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'SignIn',
                'class' => 'btn btn-lg btn-primary btn-block text-uppercase',
            ),
        ));
    }
}
