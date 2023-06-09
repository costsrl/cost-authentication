<?php
namespace CostAuthentication\Form;

use Laminas\Form\Form;

class ChangePasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'currentPassword',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'Current password',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'newPassword',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'New password',
                "autocomplete" =>"off"
            ),
            'options' => array(
                'label' => ' '
            ),
        ));

        $this->add(array(
            'name' => 'newPasswordConfirm',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' =>'Confirm new password',
                "autocomplete" =>"off"
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'class' => 'btn btn-lg btn-primary btn-block text-uppercase',
            ),
        ));
    }
}
