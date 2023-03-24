<?php

namespace CostAuthentication\Options;

use Laminas\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var bool
     */
    protected $linkRegister         = true;

    /**
     * @var bool
     */
    protected $linkForgotPassword   = true;

    /**
     * @var bool
     */
    protected $rememberMe   = true;


    /**
     * @var string
     */
    protected $loginRedirectRoute = 'user';

    /**
     * @var string
     */
    protected $logoutRedirectRoute = 'user';

    /**
     * @var string
     */
    protected $static_salt = 'aFGQ475SDsdfsaf2342';

    /**
     * @var bool
     */
    protected $navMenu = true;


    protected $navMenuOption = [];

    /**
     * @return the $navMenuOption
     */
    public function getNavMenuOption()
    {
        return $this->navMenuOption;
    }

    /**
     * @param multitype: $navMenuOption
     */
    public function setNavMenuOption($navMenuOption)
    {
        $this->navMenuOption = $navMenuOption;
    }

    /**
     * set login redirect route
     *
     * @param  string        $loginRedirectRoute
     * @return ModuleOptions
     */
    public function setLoginRedirectRoute($loginRedirectRoute)
    {
        $this->loginRedirectRoute = $loginRedirectRoute;

        return $this;
    }

    /**
     * get login redirect route
     *
     * @return string
     */
    public function getLoginRedirectRoute()
    {
        return $this->loginRedirectRoute;
    }

    /**
     * set logout redirect route
     *
     * @param  string        $logoutRedirectRoute
     * @return ModuleOptions
     */
    public function setLogoutRedirectRoute($logoutRedirectRoute)
    {
        $this->logoutRedirectRoute = $logoutRedirectRoute;

        return $this;
    }

    /**
     * get logout redirect route
     *
     * @return string
     */
    public function getLogoutRedirectRoute()
    {
        return $this->logoutRedirectRoute;
    }

    /**
     * set static salt
     *
     * @param  string        $staticSalt
     * @return ModuleOptions
     */
    public function setStaticSalt($staticSalt)
    {
        $this->static_salt = $staticSalt;

        return $this;
    }

    /**
     * get static salt
     *
     * @return string
     */
    public function getStaticSalt()
    {
        return $this->static_salt;
    }

    /**
     * set visibility of navigation menu
     *
     * @param  bool          $navMenu
     * @return ModuleOptions
     */
    public function setNavMenu($navMenu)
    {
        $this->navMenu = $navMenu;

        return $this;
    }

    /**
     * get visibility of navigation menu
     *
     * @return string
     */
    public function getNavMenu()
    {
        return $this->navMenu;
    }

    /**
     * @return bool
     */
    public function isLinkRegister()
    {
        return $this->linkRegister;
    }

    /**
     * @param bool $linkRegister
     */
    public function setLinkRegister($linkRegister)
    {
        $this->linkRegister = $linkRegister;
    }

    /**
     * @return bool
     */
    public function isLinkForgotPassword()
    {
        return $this->linkForgotPassword;
    }

    /**
     * @param bool $linkForgotPassword
     */
    public function setLinkForgotPassword($linkForgotPassword)
    {
        $this->linkForgotPassword = $linkForgotPassword;
    }

    /**
     * @return mixed
     */
    public function getStrictMode()
    {
        return $this->__strictMode__;
    }

    /**
     * @param mixed $_strictMode__
     */
    public function setStrictMode($_strictMode__)
    {
        $this->__strictMode__ = $_strictMode__;
    }

    /**
     * @return bool
     */
    public function isRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param bool $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }







}
