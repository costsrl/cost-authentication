<?php
namespace CostAuthentication\Form;

use Laminas\Form\Form;

class ChangeEmailForm extends Form
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
                "autocomplete" =>"off"
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'newEmail',
            'attributes' => array(
                'type'  => 'email',
                'placeholder' =>'New email',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));

        $this->add(array(
            'name' => 'newEmailConfirm',
            'attributes' => array(
                'type'  => 'email',
                'placeholder' =>'Confirm new email',
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
