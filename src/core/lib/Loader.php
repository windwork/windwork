<?php
/**
 * Windwork
 * 
 * 一个用于快速开发高并发Web应用的轻量级PHP框架
 * 
 * @copyright Copyright (c) 2008-2017 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
namespace wf\core;

defined('IS_IN') or die('access denied');
defined('ROOT_DIR') or die('Please define "ROOT_DIR" const (where the site document root directory)');

// wf文件夹前缀（{ROOT_DIR}/wf/src或{ROOT_DIR}/vendor/windwork/wf/src）
define('WF_BASE_DIR', dirname(dirname(__DIR__)));

// 程序执行开始时间
defined('WF_START_TIME') || define('WF_START_TIME', microtime(1));

// 开始执行占内存量
defined('WF_START_MEM') || define('WF_START_MEM', memory_get_usage());

/**
 * Windwork加载器
 * 
 * @package     wf.core
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.core.loader.html
 * @since       0.1.0
 */
final class Loader 
{
    /**
     * 是否已注册Windwork自动加载，已注册则可不再重新注册
     * 
     * @var bool
     */
    private static $isReged = false;
    
    /**
     * 自动加载类文件时查找类所在的文件夹
     * @var array
     */
    private static $classPath = [];
    
    /**
     * 命名空间对应文件夹
     * @var array
     */
    private static $namespaceMap = [
    ];
    
    /**
     * 添加类文件目录
     * @param array $classPath
     */
    public static function addClassPath(array $classPath) 
    {
        static::$classPath = $classPath + static::$classPath;
    }
    
    /**
     * 获取已设置的classPath
     * @return array
     */
    public static function getClassPath()
    {
        return static::$classPath;
    }
    
    /**
     * 加载类脚本
     * @param string $class
     */
    public static function import($class) 
    {
        $file = static::getClassFile($class);
        
        if ($file) {
            return include_once $file;
        }
        
        return false;
    }
    
    /**
     * 获取类文件路径
     * @param string $class
     * @return string|boolean
     */
    public static function getClassFile($class) {
        $class = '\\' . ltrim($class, '\\');
        
        // wf框架组件源码放到组件文件夹下的lib子文件夹
        if(preg_match("/^(\\\\wf\\\\)([a-z0-9]+\\\\)(.+)/i", $class, $match)) {
            // "libs/wf-{$component}/lib/{$class}.php";
            // "wf/{$component}/lib/{$class}.php";
            // "vendor/windwork/{$component}/lib/{$class}.php";
            $file = WF_BASE_DIR . strtr("/{$match[2]}lib\\{$match[3]}", '\\', '/') . '.php';
            if (is_file($file)) {
                return $file;
            }
        }
        
        // 通用加载文件方式，命名空间与文件夹对应
        $file = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
        
        foreach (static::$classPath as $dir) {
            if (is_file($dir . $file)) {
                return $dir . $file;
            }
        }
        
        return false;
    }
    
    /**
     * 注册自动加载类
     */
    public static function regWfAutoload() 
    {
        if (static::$isReged) {
            return;
        }

        // 注册自动加载类方法
        spl_autoload_register('\wf\core\Loader::import', false, true);
    }

    /**
     * 预先加载Web应用必须的类
     */
    public static function preloadWebLib() {        
        if (static::loadWeblibLite()) {
            return true;
        }
        
        require_once WF_BASE_DIR . '/core/lib/Benchmark.php';
        require_once WF_BASE_DIR . '/core/lib/Config.php';
        require_once WF_BASE_DIR . '/core/lib/Hook.php';
        require_once WF_BASE_DIR . '/core/lib/ServiceLocator.php';
        require_once WF_BASE_DIR . '/route/lib/RouteAbstract.php';
        require_once WF_BASE_DIR . '/route/lib/strategy/Simple.php';
        require_once WF_BASE_DIR . '/web/lib/Request.php';
        require_once WF_BASE_DIR . '/web/lib/Response.php';
        require_once WF_BASE_DIR . '/web/lib/Application.php';
        require_once WF_BASE_DIR . '/web/lib/Controller.php';
        require_once WF_BASE_DIR . '/web/lib/Dispatcher.php';
    }
    
    /**
     * wf框架主要文件合并后可一次加载
     * @return boolean
     */
    private static function loadWebLibLite() {        
        if (!function_exists('cfg')) {
            \wf\core\Loader::import("\\wf\\web\\helper");
        }
        
        $liteFile = ROOT_DIR . '/data/temp/wf_web_lite.php';
        
        if (is_file($liteFile) && date('Y-m-d', filemtime($liteFile)) == date('Y-m-d')) {
            return include_once $liteFile;
        }
        
        if (!is_writeable(dirname($liteFile))) {
            return false;
        }
        
        // 待合并预加载的类
        $libClass = [
            // core
            '\\wf\\core\\Benchmark',
            '\\wf\\core\\Config',
            '\\wf\\core\\Hook',
            '\\wf\\core\\HookInterface',
            '\\wf\\core\\I18n',
            '\\wf\\core\\Object',
            '\\wf\\core\\ServiceLocator',
            '\\wf\\core\\Session',
            '\\wf\\core\\Version',
            
            // cache
            '\\wf\\cache\\CacheInterface',
            '\\wf\\cache\\CacheAbstract',
            '\\wf\\cache\\strategy\\File',
            '\\wf\\cache\\strategy\\Memcache',
            '\\wf\\cache\\strategy\\Memcached',
            '\\wf\\cache\\strategy\\Redis',
            
            // db
            '\\wf\\db\\DBInterface',
            '\\wf\\db\\DBAbstract',
            '\\wf\\db\\Finder',
            '\\wf\\db\\QueryBuilder',
            '\\wf\\db\\strategy\\MySQLi',
            '\\wf\\db\\strategy\\PDOMySQL',
            
            // logger
            '\\wf\\logger\\LoggerInterface',
            '\\wf\\logger\\LoggerAbstract',
            '\\wf\\logger\\strategy\\File',
            
            // mvc
            '\\wf\\route\\RouteAbstract',
            '\\wf\\route\\strategy\\Simple',
            '\\wf\\model\\Model',
            '\\wf\\model\\Error',
            '\\wf\\model\\ActiveRecord',
            '\\wf\\template\\EngineInterface',
            '\\wf\\template\\strategy\\Wind',
            '\\wf\\web\\Application',
            '\\wf\\web\\Controller',
            '\\wf\\web\\Dispatcher',
            '\\wf\\web\\Message',
            '\\wf\\web\\Output',
            '\\wf\\web\\Request',
            '\\wf\\web\\Response',
        ];
        
        $liteContent = "<?php\n";
        
        foreach ($libClass as $class) {
            $file = static::getClassFile($class);
            if (!$file) {
                continue;
            }
            
            // 类文件源码内容
            $content = file_get_contents($file);
            $content = substr(trim($content), 5); // 去掉 <?php
            $content = trim($content);
            
            
            // 提取namespace
            if(preg_match("/namespace\s+.*?;/", $content, $match)) {            
                $ns = $match[0];
                if(false !== strpos($liteContent, $ns)) {
                    $content = str_replace($ns, '', $content);
                }
            }
                        
            $liteContent .= "\n# {$class}\n". $content;
        }
        
        // 去掉文档注释
        $liteContent = preg_replace("/\\/\\*\\*(\r)?\n.*?\\*\\//is", '', $liteContent);
        
        $liteContentArr = explode("\n", $liteContent);
        
        foreach ($liteContentArr as $lineNumber => $lineText) {
            // 去掉行后单行注释
            $liteContentArr[$lineNumber] = preg_replace("/(.*;)(\s*\\/\\/.*)/", "\\1", $lineText);
            
            // 一行字符串
            $lineText = trim($lineText);
            
            // 去掉空行
            if (strlen($lineText) == 0) {
                unset($liteContentArr[$lineNumber]);
                continue;
            }
            
            // 去掉单行注释
            if (strlen($lineText) >= 2 && "{$lineText[0]}{$lineText[1]}" == '//') {
                unset($liteContentArr[$lineNumber]);
                continue;
            }
            
            // 去掉包含文件
            if (substr($lineText, 0, 12) == 'require_once' || substr($lineText, 0, 12) == 'include_once') {
                unset($liteContentArr[$lineNumber]);
                continue;
            }
        }
        
        $liteContent = implode("\n", $liteContentArr);
        
        if(file_put_contents($liteFile, $liteContent)) {
            return include_once $liteFile;
        }
        
        return false;
    }
}

// 注册自动加载类
\wf\core\Loader::regWfAutoload();

