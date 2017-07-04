所有配置选项
==================

```
return [
    // 应用组件设置
    'theme'              => 'default',            // 主题样式    
    'timezone'           => 'Asia/Shanghai',      // 时区
    'locale'             => 'zh_CN',              // 系统默认本地化语言
    'gzcompress'         => 0,                    // 启用压缩开关,当web服务器不支持文本内容压缩的时候建议启用
    
    // 发送 HTTP Header状态码开关, 
    // 发送状态码对搜索引擎友好，但有些服务器一旦发送状态码则会使用自定义的页面，这种情况可设置不发送
    'isSendHttpStatus'   => 1,
    'superUid'           => 1,
    
    'auth' => [
        'name' => 'wk_auth',
        'key'  => 'sample@windwork.org',
    ],
    
    // 自动加载类查找文件所在文件夹
    'classPath' => [
        ROOT_DIR,
    ],
    
    // URL相关配置
    'url' => [
	    'class'            => '\\wf\\route\\strategy\\Simple',
	    'useModule'        => 1,       // 是否启用模块
	    
	    // 默认请求响应action
	    'defaultMod'       => 'hello',   // 默认模块
	    'defaultCtl'       => 'Welcome',  // 默认控制器
	    'defaultAct'       => 'index',    // 默认action
	
	    // 命令行下需要设置应用网站首页，如：https://windwork.org/demo/
	    'siteUrl'          => 'http://localhost/windwork.org/public/',
	    
	    // 子站点URL，根据模块、控制器识别，把优先设置放前面
	    'domain'      => [
	    ],
	        
	    // hostInfo         => '', // 从siteUrl中自动提取
	    // basePath         => '', // 从siteUrl中自动提取
	        
	    // static、theme 文件夹使用的网址，如：https://static.windwork.org/demo/
	    'staticPath'       => '',                  
	
	    'rewrite'          => 1,       // 启用URLRewrite
	    'rewriteExt'       => '.html', // 链接后缀，如：.html
	    'fullUrl'          => 0,       // 是否使用完整URL（http://开头）
	    'encode'           => 0,       // 是否对链接参数进行编码
	        
	    // 链接别名，简短URL规则，把长链接缩短成端链接的规则
	    // 下标只允许包含字母数字和下划线
	    'alias' => [
	    ],
	],
    
    // 钩子设置
    'hooks' => [
	    'enableHook' => 1, // 启用Hook
	    
	    // 设置方式1：钩子类名或钩子类的实例,如：'\\user\\hook\\Acl', new \app\user\hook\Acl()
	    // 设置方式2：钩子类名或钩子类的实例+数组参数,如：['\\user\\hook\\Acl', [$param1, $param2, ....]], [new \app\user\hook\Acl(), [$param1, $param2, ....]]
	    
	    
	    // 加载完系统配置后触发的钩子，目的是增加修改系统配置信息
	    // 只在创建Application单例时执行一次，框架仅初始化了request、response、自动加载、默认异常处理，其他库不可用；
	    'appRuntimeAft' => [
	        //'\app\system\hook\InitOptionHook', // 读取数据库保存的配置信息
	    ],
	    
	    // 初始化控制器前在控制器基类构造函数中触发的钩子
	    'dspNewControllerFore' => [
	        //'\app\user\hook\AuthHook', //权限控制
	        //'\app\system\hook\BannedIPHook', // IP 禁止
	        //'\app\system\hook\PauseServiceHook', // 系统暂停服务信息，在后台设置
	        //'\app\system\hook\AdminCPLogHook', // 后台操作日志
	    ],
	    
	    // 执行action前触发的钩子
	    'dspRunActionFore' => [        
	    ],
	        
	    // 发送响应内容前触发的钩子，可对输出内容进行处理过滤
	    'dspOutputFore' => [
	    ],
	        
	    // 发送响应内容（程序执行完）后触发的钩
	    'dspResponseAft' => [
	    ],
	],
    
    // session设置
    'session' => [
        'saveHandler'     => 'files',         //
        'savePath'        => dirname(__DIR__) . '/data/session',  //
        'useTransSid'     => 0, // 是否允许通过url传递session ID，只有客户端不支持cookie的时候（比如使用falsh上传）才启用
        'cookiePath'      => '/',
        'cookieDomain'    => '',
        'cookieLifetime'  => 43200, // 43200=12个小时
    ],
    
    // 服务定位器加载组件配置
    'srv' => [
        // 模板组件设置
        'template' => [
            'class'              => '\\wf\\template\\strategy\\Wind',
            'compileForce'       => 1,                  // 强制编译模板开关
            'compileMerge'       => 1,                  // 将编模板合并成一个文件
            'compileCheck'       => 1,                  // 检查编译模板开关
            'compileDir'         => dirname(__DIR__) . '/data/template',    // 编译后模板存放（相对于根目录）的目录，相对于src文件夹；
            'tplDir'             => dirname(__DIR__) . '/app/{0}/view',     // 模板文件目录，相对于src文件夹
            'defaultTpl'         => '{mod}/{ctl}/{act}.html',  // 默认模板文件，可使用变量：{mod}）模块；{ctl}）控制器；{act}）操作
            'tplMajorId'         => '',                 // tplDir后面再加一个文件夹
            'tplMinorId'         => '',                 // tplDir后面再加一个备选文件夹，tplMajorId文件夹中的模板不存在时选用这个设置
            'srvShare'           => false,              // 组件实例不共享
        ],
        
        // 缓存组件设置
        'cache' => [
            'class'            => '\\wf\\cache\\strategy\\File',              // 缓存模式，File|Memcache|Memcached|Redis
            'enabled'          => 1,                   // 是否启用缓存
            'dir'              => dirname(__DIR__) . '/data/cache',        // 缓存文件夹
            'expire'           => 7200,                // 缓存更新周期(默认：7200s)
            'compress'         => 0,                   // 是否启用缓存内容压缩后存贮（建议只在虚拟主机中使用文件缓存时启用，以便省出存储空间）
            
            // redis
            'redis' => [
                'host'         => '127.0.0.1',     //
                'port'         => 6379,            //
                'pconnect'     => 1,               //
                'timeout'      => 0,               // 超时时间（秒），0为不限
            ],
            // memcache
            'memcache' => [
                'host'         => '127.0.0.1',     //
                'port'         => 11211,           //
                'pconnect'     => 1,               //
                'timeout'      => 1,               // 超时时间（秒），0为不限
            ],
            // memcached
            'memcached' => [
                'host'         => '127.0.0.1',     //
                'port'         => 11211,           //
                'pconnect'     => 1,               //
                'timeout'      => 1,               // 超时时间（秒），0为不限
            ],
        ],
        
        // 数据库连接配置
        'db' => [
		    'default' => [
		        // 数据库设置
		        'class'          => '\\wf\\db\\strategy\\PDOMySQL',    // MySQLi|PDOMySQL
		        'host'           => '127.0.0.1',   // 本机测试
		        'port'           => '3306',        // 数据库服务器端口
		        'name'           => 'icsmalldb',       // 数据库名
		        'user'           => 'root',        // 数据库连接用户名
		        'pass'           => '123456',      // 数据库连接密码
		        'tablePrefix'    => 'wk_',         // 表前缀
		        'debug'          => 0,
		    ],
		    
		    // 主从分离，启用后，从slave读，从default写
		    /*
		    'slave' => array(
		        // 数据库设置
		        'class'          => '\\wf\\db\\strategy\\PDOMySQL',    // MySQLi|PDOMySQL
		        'host'           => '127.0.0.1',   // 本机测试
		        'port'           => '3306',        // 数据库服务器端口
		        'name'           => 'windworkdb',  // 数据库名
		        'user'           => 'root',        // 数据库连接用户名
		        'pass'           => '123456',      // 数据库连接密码
		        'tablePrefix'    => 'wk_',         // 表前缀
		        'debug'          => 0,
		    ),
		    */
		],
        
        // 附件组件设置
        'storage' => [
            // 附件处理类
            'class'           => '\\wf\\storage\\strategy\\File',
            
            // 附件存贮文件夹，相对于站点根目录
            'dir'             => 'storage',
            
            // 附件大小限制（默认4M）
            'sizeLimit'       => '4M',
            
            // 附件目录url，格式：http://www.windwork.org/（后面带'/'，如果上传附件网站跟站点不是同一个站时设置）
            // 如果附件为动态内容，并且不启用rewrite，这里使用index.php?system.uploader.load
            'storageUrl'         => '',
            
            // 当浏览不存在的图片时显示的图片
            'noPicUrl'        => '',
            
            // 子文件夹格式
            'subdirFormat'    => 'Y/m/d',
        ],
        
        // 日志组件设置
        'logger' => [
            'class'           => '\\wf\\logger\\strategy\\File',
            'dir'             => dirname(__DIR__) . '/data/log',          //
            
            // 日志级别，可设置为0-7，记录小于或等于该级别的日志
            // 0)emergency，
            // 1)alert，
            // 2)critical，
            // 3)error，
            // 4)warning，
            // 5)notice，
            // 6)info，
            // 7)debug
            'level'           => 7,
        ],
        
        // 加密解密组件设置
        'crypt' => [
            'class'          => '\\wf\\crypt\\strategy\\AzDG',
        ],
        
        // 验证码组件设置
        'captcha' => [
            'class'         => '\\wf\\captcha\\strategy\\GDSimple',
        ],
        
        // 邮件发送组件设置
        'mailer' => [
            'class'         => '\\wf\\mailer\\strategy\\SMTP',
            'port'          => 25,
            'host'          => 'smtp.163.com',
            'auth'          => true,
            'user'          => 'p_cm@163.com',
            'pass'          => 'CM->o.163.',
        ],
    ],
];




```