<?php
namespace CostAuthenticationTest\Entity;

use CostAuthentication\Entity\Language;
use PHPUnit_Framework_TestCase;

class LanguageTest extends PHPUnit_Framework_TestCase
{
    public function testLanguageInitialState()
    {
        $user = new Language();

        $this->assertNull(
            $user->getId(),
            '"id" should initially be null'
        );
        $this->assertNull(
            $user->getName(),
            '"name" should initially be null'
        );
    }
    
    
    public function testLanguageAfterIdrate()
    {
        $user = new Language();
        
        $user->setId(1);
        $user->setName('foo');
    
        $this->assertEquals(
            $user->getId(),
            1
            );
        
        $this->assertEquals(
            $user->getName(),
            'foo'
            );
    }
}
