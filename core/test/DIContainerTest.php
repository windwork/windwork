<?php
require_once '../lib/DIContainer.php';
require_once '../lib/Exception.php';

use \wf\core\DIContainer;

class Inst {
    public function __construct($arg)
    {                
        print "\n\nInst->__construct\n----\n";
        print_r($arg);
        print "\n----\n";            
        return true; 
    }
    
    public function exec($arg)
    {
        print "\n\nInst->exec\n----\n";
        print_r($arg);
        print "\n----";            
        return true; 
        
    }
}

/**
 * Config test case.
 */
class ConfigTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var DIContainer
     */
    private $container;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        
        // TODO Auto-generated ConfigTest::setUp()
        
        $this->container = new DIContainer();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated ConfigTest::tearDown()
        $this->container = null;
        
        parent::tearDown();
    }
    
    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }
    
    public function testAll()
    {
        // 保存闭包
        $this->container->closure = function($arg) {
            print "\n\ncontainer->closure\n";
            print "----\n";
            print_r($arg);
            print "\n----";            
            return true;
        };
        $fnc = $this->container->closure;
        
        $this->assertTrue($fnc('hi，好啊油！'));
        
        // 注入对象
        $this->container->inst = new Inst('jo简介');
        $this->container->inst->exec('偶记即将');
        
        $this->assertTrue(isset($this->container->inst));
        $this->assertTrue($this->container->inst instanceof Inst);
        unset($this->container->inst);

        $this->assertNull($this->container->inst);
        $this->assertFalse(isset($this->container->inst));
        
        
    }
    
}

