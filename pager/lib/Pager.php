<?php
/**
 * Windwork
 * 
 * 一个用于快速开发高并发Web应用的轻量级PHP框架
 * 
 * @copyright Copyright (c) 2008-2017 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
namespace wf\pager;


/**
 * 分页类
 * 
 * 通过传入总记录数、每页显示记录数来生成分页导航
 * 
 * @package     wf.pager
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.pager.html
 * @since       0.1.0
 */
class Pager 
{
    /**
     * 当前页码，从1开始，大于最大页数被强制转换成最大页码
     * 
     * @var int = 1
     */
    public $page = 1; 

    /**
     * 总记录数
     *
     * @var int = 0
     */
    public $totals = 0;
    
    /**
     * 每页显示记录数
     *
     * @var int = 10
     */
    public $rows = 10;
    
    /**
     * 最后一页页码，总页数
     *
     * @var int
     */
    public $lastPage;

    /**
     * 上一页的页码
     *
     * @var int
     */
    public $prePage;

    /**
     * 下一页的页码
     *
     * @var int
     */
    public $nextPage;

    /**
     * 分页的url查询变量
     *
     * @var string = 'page'
     */
    public $pageVar = 'page';
    
    /**
     * 每页允许最多行数，仅限制通过URL设置每页行数，不限分页类构造函数中的rows参数
     * @var string = 100
     */
    public $rowsMax = 100;

    /**
     * 查询的起始项，从0开始，提供数据库查询时确定查询开始记录下标
     *
     * @var int
     */
    public $offset;

    /**
     * 每页记录数URL参数
     * @var string = 'rows
     */
    public $rowsVar = 'rows';

    /**
     * 变量分隔符
     * @var string
     */
    public $argSeparator = '&';
    
    /**
     * 变量和值的分隔符
     * @var string
     */
    public $valSeparator = '=';
    
    /**
     * 是否允许通过URL设置每页记录数
     * @var bool
     */
    public $allowCustomRows = true;
    
    /**
     * 分页导航条显示模板id， mobile）手机分页, simple）简单分页, complex）复杂分页
     * @var string
     */
    public $tpl = 'simple';

    /**
     * 分页页的uri
     *
     * @var string
     */
    private $uri;
    
    /**
     * 是否已经计算出分页参数
     * @var bool
     */
    private $isParsed = false;
    
    /**
     * 当URL请求参数不使用常规格式（&key=value）时，用于记录URI后面的查询串
     * @var string
     */
    private $uriQuery = '';
    
    /**
     * 分页设置
     * 
     * 当页面URL中存在 ? 时，分页参数总是使用标准模式，即?k1=v1&k2=v2模式，否则按设置的参数格式获取到url
     *
     * @param int $totals 总记录数
     * @param int $rows = 10 每页显示记录数
     * @param int $uri = '' 分页页面URL
     * @param array $opts = [] 设置属性，可设置部分属性
     * <pre> [
     *     'argSeparator' => '&',    // 参数分隔符号
     *     'valSeparator' => '=',    // 参数变量名和值的分隔符
     *     'pageVar'      => 'page', // 分页页码的url请求变量名
     *     'rowsVar'      => 'rows', // 每页行数的url请求变量名
     *     'rowsMax'      => 100,    // 每页允许最多记录数
     *     'tpl'          => 'simple',
     * ]
     * </pre>
     */
    public function __construct($totals, $rows = 10, $uri = '', array $opts = []) 
    {
        $this->totals = $totals;
        $this->rows = $rows;
        $this->uri = $uri ? $uri : @$_SERVER['REQUEST_URI'];
                
        
        // $opts参数赋值给public属性
        if ($opts) {
            foreach ($opts as $attrName => $val) {                
                if (!in_array($attrName, ['argSeparator', 'valSeparator', 'pageVar', 'rowsVar', 'rowsMax'])) {
                    continue;
                }
                
                $this->$attrName = $val;
            }
        }
        
        // 参数分隔符限制
        $forbidChar = ['?', '#', '.', '%'];
        if (in_array($this->argSeparator, $forbidChar)) {
            throw new Exception(get_class($this) . '::argSeparator 属性不能是' . implode(',', $forbidChar));
        }
        if (in_array($this->valSeparator, $forbidChar)) {
            throw new Exception(get_class($this) . '::valSeparator 属性不能是' . implode(',', $forbidChar));
        }
        
        $this->parseArgs();
    }
    
    /**
     * 提取page,rows参数
     */
    private function parseArgs() 
    {
        $pregArgSeparator = $this->argSeparator == '&' ? '' : preg_quote($this->argSeparator, '/');
        $pregValSeparator = $this->valSeparator == '=' ? '' : preg_quote($this->valSeparator, '/');
        
        if ($this->argSeparator == '&' && $this->valSeparator == '=') {
            // 常规链接应该有参数前应该有 ?
            $this->uri = str_replace('&amp;', '&', $this->uri);
            if (false === stripos($this->uri, '?')) {
                $this->uri .= '?';
            }
        } elseif (false !== stripos($this->uri, '?')) {
            // URL变量不使用&xx=yy的格式，URL中有?
            $this->uriQuery = substr($this->uri, strpos($this->uri, '?'));
            $this->uri = substr($this->uri, 0, strpos($this->uri, '?'));
        }
        
        $this->uri = rtrim($this->uri, $this->argSeparator);
        
        // POST请求参数合并入URL
        if ($_POST) {
            $argStr = http_build_query($_POST, '__', $this->argSeparator);
            
            // 键值分隔符号不是=
            if ($this->valSeparator != '=') {
                $argStr = str_replace('=', $this->valSeparator, $argStr);
            }
            
            $this->uri .= $this->argSeparator . $argStr;
        }
        
        // 从请求参数中提取当前页码
        if(preg_match_all("/[\\?&{$pregArgSeparator}]{$this->pageVar}[={$pregValSeparator}](\\d+)/i", $this->uri, $pageMatch)) {
            // 匹配?page=N、&page=N、{$this->argSeparator}page{$this->valSeparator}=N
            $this->page = end($pageMatch[1]);
        }
        $this->page <= 0 && $this->page = 1;
        
        // 允许地址栏传入每页记录数参数，则从请求参数中提取每页行数
        if ($this->allowCustomRows) {
            if(preg_match_all("/[\\?&{$pregArgSeparator}]{$this->rowsVar}[={$pregValSeparator}](\\d+)/i", $this->uri, $rowsMatch)) {
                // 匹配?page=N、&page=N、{$this->argSeparator}page{$this->valSeparator}=N
                $this->rows = end($rowsMatch[1]);
            }
            
            // 最多记录数限制
            $this->rows > $this->rowsMax && $this->rows = $this->rowsMax;
        }
        
        // 去掉URI中的分页、每页行数参数
        $this->uri = preg_replace("/([&{$pregArgSeparator}]({$this->pageVar}|{$this->rowsVar})[={$pregValSeparator}]\\d+)/", '', $this->uri);
        $this->uri = preg_replace("/(\\?({$this->pageVar}|{$this->rowsVar})[={$pregValSeparator}]\\d+)/", '?', $this->uri); // 页码、页记录数变量连在?之后，即?page=xx或?page:xxx
                    
        
        /* 页码计算 */
        
        // 最后页，也是总页数
        $this->lastPage = ceil($this->totals / $this->rows);
        
        // 当前页，page值超过最大值时取最大值做page值
        $this->page = min($this->lastPage, $this->page);
        
        // 上一页
        $this->prePage = max($this->page - 1, 1);
        
        // 下一页
        $this->nextPage = min($this->lastPage, $this->page + 1);
        
        // 起始记录，当前页在数据库查询符合结果中的起始记录
        $this->offset = max(($this->page - 1) * $this->rows, 0);    
        
        $this->isParsed = true;
    }
    
    /**
     * 根据参数生成URL
     * @param int $page 页码
     * @param int $rows = null 为null时使用$this->rows的值
     * @return string
     */
    public function getPageUrl($page, $rows = null) 
    {        
        $rows || $rows = $this->rows;
        $url = $this->uri;
                
        if ($this->allowCustomRows) {
            $url .= "{$this->argSeparator}{$this->rowsVar}{$this->valSeparator}{$rows}";
        }
        
        if ($page > 1) {
            $url .= "{$this->argSeparator}{$this->pageVar}{$this->valSeparator}{$page}";
        }
        
        $url = str_replace("?&", '?', $url);
        
        $url .= $this->uriQuery;
        
        return $url;
    }
    
    /**
     * 提供给js调用的分页信息，需使用json_encode() 编码返回的对象实例
     * 返回 (object) array(
     *      'totals' => '',
     *      'pages'  => '',
     *      'page'   => '',
     *      'rows'   => '',
     *      'offset' => ''
     *  );
     *  
     * @return object
     */
    public function asJson() 
    {
        if (!$this->isParsed) {
            $this->parseArgs();
        }
        
        $r = array(
            'totals' => $this->totals,
            'pages'  => $this->lastPage,
            'page'   => $this->page,
            'rows'   => $this->rows,
            'offset' => $this->offset
        );
        
        return (object)$r;
    }
    
    /**
     * 获取导航条html
     * @param string $tpl = '' 选择使用导航条模板， mobile）手机分页, simple）简单分页, complex）复杂分页
     */
    public function getHtml($tpl = '') 
    {
        if (!$this->isParsed) {
            $this->parseArgs();
        }
        
        $tpl || $tpl = $this->tpl;
        $viewFile = __DIR__ . "/view/{$tpl}.php";
        
        ob_start();
        include $viewFile;
        return ob_get_clean();
    }
}

