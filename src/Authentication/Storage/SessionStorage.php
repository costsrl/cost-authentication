<?php
/**
 * Created by PhpStorm.
 * User: renato
 * Date: 12/11/18
 * Time: 17.57
 */

namespace CostAuthentication\Authentication\Storage;


use Laminas\Authentication\Storage\Session;




class SessionStorage extends Session
{
    protected $options;


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
        return $this->options;
    }


    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }


}