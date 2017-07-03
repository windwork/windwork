<?php
require_once '../lib/Pager.php';

use wf\pager\Pager;

/**
 * Pager test case.
 */
class PagerTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var Pager
     */
    private $pager;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated PagerTest::tearDown()
        $this->pager = null;
        
        parent::tearDown ();
    }
    
    
    /**
     * Tests Pager->getPageUrl()
     */
    public function testGetPageUrl() {
        // 默认参数
        $args = [
            'argSeparator'   => '/',  // 参数分隔符号
            'valSeparator'   => ':', // 变量和值的分隔符
            'pageVar'        => 'page', // 分页的url请求变量
            'rowsVar'        => 'rows', // 每页行数url请求变量
        ];
        
        // /demo/path/to/arg1:val1?rows=10&page=2
        $uri = '/demo/path/to/arg1:val1/page:1/rows:10';
        $pager = new Pager(200, 20, $uri);
        
        $args = [
            'argSeparator'   => '&',  // 参数分隔符号
            'valSeparator'   => '=', // 变量和值的分隔符
            'pageVar'        => 'page', // 分页的url请求变量
            'rowsVar'        => 'rows', // 每页行数url请求变量
        ];
        
        // /demo/path/to/arg1:val1?rows=10&page=2
        $uri = '/demo/path/to/arg1:val1?page=1&rows=10&argx=123';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(2);        
        $this->assertEquals('/demo/path/to/arg1:val1?argx=123&rows=10&page=2', $url);

        $url = $pager->getPageUrl(1);
        $this->assertEquals('/demo/path/to/arg1:val1?argx=123&rows=10', $url);
        
        $url = $pager->getPageUrl(2321);
        $this->assertEquals('/demo/path/to/arg1:val1?argx=123&rows=10&page=2321', $url);
        
        // /test/xx?my=test&rows=20&page=55
        $uri = '/test/xx?my=test';
        $pager = new Pager(200, 20, $uri, $args);
        
        
        $url = $pager->getPageUrl(55);
        $this->assertEquals('/test/xx?my=test&rows=20&page=55', $url);


        // 分隔符改回默认
        $args = [
            'argSeparator'   => '/',  // 参数分隔符号
            'valSeparator'   => ':', // 变量和值的分隔符
            'pageVar'        => 'xpage', // 分页的url请求变量
            'rowsVar'        => 'xrows', // 每页行数url请求变量
        ];
        
        // /test/xx/rows:20/page:2
        $uri = '/test/xx';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(2);
        $this->assertEquals("{$uri}/xrows:20/xpage:2", $url);
        
        
        // /test/xx/page:2
        $uri = '/test/xx';
        $pager = new Pager(200, 20, $uri, $args);
        $pager->allowCustomRows = false;
        
        $url = $pager->getPageUrl(2);
        $this->assertEquals("{$uri}/xpage:2", $url);
        
        // 有POST数据
        $_POST = [
            'arr' => [
                'a1' => 123,
                'a2' => "d中文sf\"xs\'xs",
            ],
            'str' => 'jdsoi',
        ];

        $uri = '/test/xx';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(2);
        $this->assertEquals("/test/xx/arr%5Ba1%5D:123/arr%5Ba2%5D:d%E4%B8%AD%E6%96%87sf%22xs%5C%27xs/str:jdsoi/xrows:20/xpage:2", $url);

        $uri = '/test/xx?q=123';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(2);
        $this->assertEquals("/test/xx/arr%5Ba1%5D:123/arr%5Ba2%5D:d%E4%B8%AD%E6%96%87sf%22xs%5C%27xs/str:jdsoi/xrows:20/xpage:2?q=123", $url);
                
        $_POST = [];
        // END 有POST数据

        // /test/xx/r/每页行数/p/页码
        $args = [
            'argSeparator'   => '/',  // 参数分隔符号
            'valSeparator'   => '/', // 变量和值的分隔符
            'pageVar'        => 'p', // 分页的url请求变量
            'rowsVar'        => 'r', // 每页行数url请求变量
        ];
        $uri = '/test/xx';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(2);
        $this->assertEquals("/test/xx/r/20/p/2", $url);
        

        $uri = '/test/xx/r/8/p/15';
        $pager = new Pager(200, 20, $uri, $args);
        
        $url = $pager->getPageUrl(12);
        $this->assertEquals('/test/xx/r/8/p/12', $url);
        
        $url = $pager->getPageUrl($pager->lastPage);
        $this->assertEquals('/test/xx/r/8/p/25', $url);
        
    }
    
    /**
     * Tests Pager->getObj4Json()
     */
    public function testAsJson() {
        $this->pager = new Pager(100, 10, '/demo/dox?ajio');
        
        $json =(array)$this->pager->asJson();
        $this->assertEquals([
            'totals' => 100,
            'pages' => 10,
            'page' => 1,
            'rows' => 10,
            'offset' => 0,
        ], $json);
    }
    
    /**
     * Tests Pager->getHtml()
     */
    public function testGetHtml() {
        $this->pager = new Pager(100, 12);
        $simpleHtml = $this->pager->getHtml('simple');
        $mobileHtml = $this->pager->getHtml('mobile');
        $complexHtml = $this->pager->getHtml('complex');

        $this->assertNotEmpty($simpleHtml);
        $this->assertNotEmpty($mobileHtml);
        $this->assertNotEmpty($complexHtml);

        $this->assertNotEquals($simpleHtml, $mobileHtml);
        $this->assertNotEquals($simpleHtml, $complexHtml);
    }
}

