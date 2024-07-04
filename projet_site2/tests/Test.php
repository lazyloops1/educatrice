<?php
use PHPUnit\Framework\TestCase;
use App\Entity\User;


class Test extends Testcase{
    public function test_get_Nom(){
         $stub=$this->getMockBuilder(user::class)->getMock();
         $stub->method('getNom')->willReturn( 'francis');
         $this->assertEquals('francis', $stub->getNom());
    }
}
?>