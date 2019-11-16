<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Omatech\FireAndForget\FireAndForget;

final class FireAndForgetTest extends TestCase {

    public function testHello() :void
	{
        $this->assertEquals('hello', 'hello');
    }
		
	public function testBasicConnection() :void
	{
        $url="https://postman-echo.com/get";
        $params=['test'=>'testval'];

        $faf = new FireAndForget($url, $params);
        $faf->send();

        $time=$faf->time_spent;
        echo $faf->debug;
        $this->assertGreaterThan(0, $time);
        $this->assertLessThan(0.5, $time);
        $this->assertContains('Sent succesfully!', $faf->debug);
	}
		
		
}
