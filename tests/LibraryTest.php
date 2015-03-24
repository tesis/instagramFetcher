<?php
use Tesis\Socials\Instagram\Library;

class LibraryTest extends PHPUnit_Framework_TestCase {

    public $library;
    public $searchParams;
    public $lat;
    public $lng;
    public $tag;

    public function setUp()
    {
        parent::setUp();
        $this->lat = '51.507350';
        $this->lng = '-0.127758';
        $this->tag = 'dog';
        $this->searchParams = ['latitude'=>'51.507350', 'longitude'=>'-0.127758'];
        $this->library = new Library;
    }
    public function tearDown()
    {
        //
    }

    /**
     * test_searchByLocation_Pass
     *
    */
    public function test_searchByLocation_Pass()
    {
        $test = $this->library->searchByLocation($this->searchParams);
        $this->assertNotEmpty(sizeof($test), 'Expected Pass');
        //print_r($test);
    }
    /**
     * test_searchByLocation_Fail
     * @expectedException     \Exception
     * expectedExceptionMessage Check arguments, seems not OK
    */
    public function test_searchByLocation_Fail()
    {
        $test = $this->library->searchByLocation();
        $this->assertNotEmpty(sizeof($test), 'Expected Fail');
    }
    /**
     * test_searchByLocation_Pass
     *
    */
    public function test_searchByTag_Pass()
    {
        $test = $this->library->searchByTag($this->tag);
        $this->assertNotEmpty(sizeof($test), 'Expected Pass');
    }
    /**
     * test_searchByTag_Fail
     * @expectedException     \Exception
     * expectedExceptionMessage Check arguments, seems not OK
    */
    public function test_searchByTag_Fail()
    {
        $test = $this->library->searchByTag();
        $this->assertNotEmpty(sizeof($test), 'Expected Fail');
    }


}
