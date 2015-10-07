<?php

use Strata\Strata;

use Tests\Fixtures\Core\TestStrataObjectTrait;

class StrataTest extends PHPUnit_Framework_TestCase
{
    public function testCanBeInstanciated()
    {
        $this->assertTrue(new Strata(array()) instanceof Strata);
    }

    public function testStrataConfigurableTrait()
    {
        $strata = new Strata();
        $strata->setConfig("first_level", array("second_level" => true));
        $value = $strata->getConfig("first_level.second_level");
        $this->assertTrue($value);
    }

    public function testStrataObjectTrait()
    {
        $this->assertTrue(TestStrataObjectTrait::staticFactory() instanceof TestStrataObjectTrait);
        $this->assertEquals("Tests\Fixtures\\\\TestWierdNameTesting", TestStrataObjectTrait::generateClassPath("Test--wierd name"));
        $this->assertEquals("Tests\\Fixtures\\\\EndingWithTesting", TestStrataObjectTrait::generateClassPath("EndingWithTesting"));
    }

    /**
     * @expectedException        Exception
     */
    public function testInvalidClassName()
    {
        TestStrataObjectTrait::generateClassPath("3Test--wierdest é#&!@#$%?&*(name");
    }

    public function testGetShortname()
    {
        $obj = TestStrataObjectTrait::staticFactory();
        $this->assertEquals("TestStrataObjectTrait", $obj->getShortName());
    }
}
