<?php
/**
 * Windwork
 * 
 * 一个用于快速开发高并发Web应用的轻量级PHP框架
 * 
 * @copyright Copyright (c) 2008-2017 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
namespace wf\web;

require_once __DIR__ . '/helper.php';

/**
 * Web应用容器类
 * 
 * @package     wf.web
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.web.app.html
 * @since       0.1.0
 */
final class Application 
{
    
    /**
     * app实例
     * @var \wf\web\Application
     */
    private static $instance;
    
    /**
     * 应用配置信息
     * @var \wf\core\Config
     */
    protected $config;
    
    /**
     * 应用钩子实例
     * @var \wf\core\Hook
     */
    protected $hook;
    
    /**
     * 应用允许环境
     * @var string $env
     */
    private $env;
    
    /**
     * 服务定位器实例
     * @var \wf\core\ServiceLocator
     */
    private $srv;
    
    /**
     * 调度器实例
     * 
     * @var \wf\web\Dispatcher $dispatcher
     */
    private $dispatcher;

    /**
     * 
     * @var \wf\web\Message
     */
    private $message;
    
    
    /**
     * 取得前端控制器实例，只允许实例化一次
     *
     * @param string $cfgDir = ''
     * @param string $env = 'develop' 系统部署环境
     * <pre>
     *   <b>develop）</b>开发环境；
     *   <b>testing）</b>测试环境；
     *   <b>product）</b>正式产品环境
     *   <b>自定义）</b>自定义的运行环境，配置文件放在“config/自定义”文件夹中
     * </pre>
     * @return \wf\web\Application
     */
    public static function app($cfgDir = '', $env = 'develop')
    {
        if (!self::$instance){
            \wf\core\Benchmark::start();
            self::$instance = new static($cfgDir, $env);
            self::$instance->initRuntime();
            \wf\core\Benchmark::mark('appInstanceAft');
        }
        
        return self::$instance;
    }
    
    /**
     * 限制只能使用单例模式创建实例，通过 Application::app()创建/获取实例
     * @param string $cfgDir
     * @param string $env
     */
    private function __construct($cfgDir = '', $env = 'develop')
    {        
        // 自定义异常处理
        set_exception_handler('exceptionHandler');
        
        // 运行环境参数
        $this->env = $env;
        
        // 初始化配置对象
        $cfgDir || $cfgDir = ROOT_DIR . '/config';
        $this->config = new \wf\core\Config($cfgDir, $env);
        $this->config->load('app');
    }
    
    /**
     * 运行时 ini设置
     */
    protected function initRuntime()
    {
        // 防止出现循环初始化
        static $inited;
        if ($inited) {
            return;
        }
        $inited = true;
        
        @ini_set('zend.script_encoding', 'UTF-8');  // php脚本使用UTF-8字符集
        @ini_set('default_charset',      'UTF-8');  // 输出默认使用UTF-8字符集
        
        // 加载config后，初始化运行时 ini设置
        @ini_set('memory_limit',   '128M'); // 如需更大内存，可在控制器中覆盖设置
        @ini_set('date.timezone',  cfg('timezone')); // 默认时区, 可在用户登录后重设时区
        @ini_set('error_log',      cfg('log.dir') . '/php_error.log');
        @ini_set('log_errors',     1);
        
        if (defined('WF_DEBUG') && WF_DEBUG) {
            // 调试模式设置
            @ini_set('error_reporting', E_ALL|E_STRICT);
            @ini_set('display_errors',  1);
        } else {
            // 非调试模式
            @ini_set('error_reporting', E_ALL ^ (E_NOTICE | E_WARNING | E_STRICT));
            @ini_set('display_errors',  0);
        }
                
        // 设置自动加载类查找类文件的文件夹
        \wf\core\Loader::addClassPath(cfg('classPath'));
        
        // init web runtime
        $this->dispatcher = new \wf\web\Dispatcher($this);
        
        // 清除输出缓冲
        while (ob_get_level()) {
            @ob_end_clean();
        }
        
        // 启用压缩，服务器端支持压缩并且客户端支持解压缩则启用压缩
        @ob_start(cfg('gzcompress') ? 'ob_gzhandler' : null);
        
        // 响应头信息设置
        header('Content-Type: text/html;charset=UTF-8');
        header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('X-Powered-By: Windwork');
                
        // 初始化hook，加载hook配置
        $this->hook = new \wf\core\Hook(cfg('hooks'));
        
        // hook 1 Web运行环境初始化后的钩子
        $this->hook->call('appRuntimeAft');
    }
    
    /**
     * 获取服务定位器实例。
     *
     * 必须加载config/app.php配置文件以后才能调用该方法，否则将无法根据配置加载框架组件。
     *
     * @return \wf\core\ServiceLocator
     */
    public function getSrv()
    {
        if ($this->srv) {
            return $this->srv;
        }
        
        $this->srv = new \wf\core\ServiceLocator();
        
        // 框架组件从配置文件注入服务定位器
        $srvCfgs = cfg('srv');
        if (!$srvCfgs) {
            return $this->srv;
        }
        
        foreach ($srvCfgs as $srvKey => $srvArgs) {
            if (isset($srvArgs['class'])) {
                $isShare = !isset($srvArgs['srvShare']) || $srvArgs['srvShare'] ? true : false;
                $this->srv->set($srvKey, $srvArgs['class'], [$srvArgs], $isShare);
            } else {
                foreach ($srvArgs as $subKey => $subSrvArgs) {
                    if (empty($subSrvArgs['class'])) {
                        continue;
                    }
                    $isShare = !isset($subSrvArgs['srvShare']) || $subSrvArgs['srvShare'] ? true : false;
                    $this->srv->set("{$srvKey}.{$subKey}", $subSrvArgs['class'], [$subSrvArgs], $isShare);
                }
            }
        }
        
        return $this->srv;
    }
    
    /**
     * 获取系统运行环境名
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }
    
    /**
     * 获取app创建的钩子实例
     * @return \wf\core\Hook
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * 获取调度器实例
     * @return \wf\web\Dispatcher
     */
    public function getDispatcher() 
    {
        return $this->dispatcher;
    }
    
    /**
     * 应用执行入口
     */
    public function run() 
    {
        \wf\core\Benchmark::mark('appRunFore');
        
        return $this->getDispatcher()->dispatch();
    }
    
    /**
     * 消息对象实例，支持在多个控制器间（dsp()->dispatch()时）共享消息
     * @return \wf\web\Message
     */
    public function getMessage()
    {
        if (!$this->message) {
            $this->message = new \wf\web\Message();
        }
        return $this->message;
    }
    
    /**
     * 应用创建的配置信息类实例
     * @return \wf\core\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * 删除创建的应用实例
     */
    public static function destroy()
    {
        static::$instance = null;
    }
    
    /**
     * 获取国际化多语言支持实例
     * @return \wf\core\I18n
     */
    public function getI18n()
    {
        static $i18n;
        if ($i18n) {
            return $i18n;
        }
        
        $i18n = new \wf\core\I18n();
        $i18n->setDir(ROOT_DIR . '/i18n');
        
        if (!empty($_SESSION['locale']) && $i18n->setLocale($_SESSION['locale'])) {
            // 优先级1、从session获取地区
            return $i18n;
        } elseif (!empty($_COOKIE['locale']) && $i18n->setLocale($_COOKIE['locale'])) {
            // 优先级2、从cookie获取地区
            return $i18n;
        } elseif (class_exists('\\Locale') && $i18n->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))) {
            // 优先级3、从客户端浏览器识别地区
            return $i18n;
        } elseif ($i18n->setLocale($this->getConfig()->get('locale'))) {
            // 优先级4、从配置文件获取地区
            return $i18n;
        } else {
            $i18n->setLocale('zh_CN');
            return $i18n;
        }
    }
    
}
