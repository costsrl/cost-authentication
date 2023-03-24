<?php
namespace CostAuthentication\Authentication\Adapter;

interface passwordAdapterInterface
{ 
    /**
     * 
     * @param string $clearPassword
     */
    public function setPassword($clearPassword);
}

?>