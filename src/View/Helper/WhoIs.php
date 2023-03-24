<?php

/**
 * Coolcsn Laminas Framework 2 Authorization Module
 * 
 * @link https://github.com/coolcsn/CsnAuthorization for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAuthorization/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 */

namespace CostAuthentication\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class WhoIs extends AbstractHelper {
    
    protected $auth;
    protected $acl;

    public function __construct($auth, $acl) {
        $this->auth = $auth;
        $this->acl = $acl;
    }

    /**
     * Checks whether the current user has acces to a resource.
     * 
     * @param string $resource
     * @param string $privilege
     */
    public function __invoke($resource, $privilege = null) {
        if ($this->auth->hasIdentity()) {
            $user = $this->auth->getIdentity();
            $display_name = $user->getDisplayName();
            $role = $user->getRole()->getName();
            echo  $display_name.' ['.$role.'] ';
        }
        else{
            echo 'User not authenticated';
        }
    }

}
