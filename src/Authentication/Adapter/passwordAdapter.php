<?php
namespace CostAuthentication\Authentication\Adapter;

use Laminas\Crypt\Password\Bcrypt;
use CostAuthentication\Authentication\Adapter\passwordAdapterInterface;

class passwordAdapter implements passwordAdapterInterface
{
    
    protected $bcrypt;
    protected $password;
    
    
    public function __construct(array $config){
        $this->bcrypt = new Bcrypt();
        $this->bcrypt->setCost($config['paramsCost']);
        
        if($config['paramsSalt']!=''){
            $this->bcrypt->setSalt($config['paramsSalt']);
        }
        
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \CostAuthentication\Authentication\Adapter\passwordAdapterInterface::setPassword()
     * @return string cripted
     */
    public function setPassword($clearPassword){
        $this->password = $clearPassword;
        return $this;
    }
    
    
    
    /**
     * @return password crypted
     */
    public function encryptPassword(){
        $password = $this->bcrypt->create($clearPassword);
        return $password;
    }
    
    /**
     * 
     * @param string $clearPassword
     * @param string $password
     * @return boolean
     */
    public function  verifiyPassword($clearPassword,$password){
        return $this->bcrypt->verify($clearPassword, $password);
    }
}

?>