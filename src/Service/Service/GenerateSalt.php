<?php
namespace CostAuthentication\Service\Service;

class GenerateSalt
{
    
    public static function generateDynamicSalt()
    {
        $dynamicSalt = '';
        for ($i = 0; $i < 50; $i ++) {
            $dynamicSalt .= chr(rand(33, 126));
        }
    
        return $dynamicSalt;
    }
    
    
    public static function encryptPassword($staticSalt, $password, $dynamicSalt)
    {
        return $password = md5($staticSalt . $password . $dynamicSalt);
    }
    
}

?>