Windwork 版本
======================

为解决composer多组件情况下各个组件的版本不一致的问题，每个组件分别字composer.json保存发布版本，\wf\core\Version::VERSION 只保存精确到一位小数的大版本号。

\wf\core\Version关联的组件如下：
 - [cache](https://github.com/windwork/wf-cache)
 - [captcha](https://github.com/windwork/wf-captcha)
 - [core](https://github.com/windwork/wf-core)
 - [crypt](https://github.com/windwork/wf-crypt)
 - [db](https://github.com/windwork/wf-db)
 - [image](https://github.com/windwork/wf-image)
 - [logger](https://github.com/windwork/wf-logger)
 - [mailer](https://github.com/windwork/wf-mailer)
 - [model](https://github.com/windwork/wf-model)
 - [pager](https://github.com/windwork/wf-pager)
 - [route](https://github.com/windwork/wf-route)
 - [storage](https://github.com/windwork/wf-storage)
 - [template](https://github.com/windwork/wf-template)
 - [util](https://github.com/windwork/wf-util)
 - [web](https://github.com/windwork/wf-web)
 - [widget](https://github.com/windwork/wf-widget)


## composer.json中关于版本的参数
 - version 组件版本号
 - time 组件最后更新时间，\wf\core\Version支持汇总所有组件的最后发布时间。

## 获取所有组件的版本信息

```
$componentsVersion = \wf\core\Version::getComponentsVersion();

/*
// 返回类型为
$componentsVersion = [
    'release' => '所有组件中最大的time参数',
    'component' => [
	    '组件1' => [
	        'version' => xx.xx.xx,
	        'time' => 'YYYY-mm-dd HH:ii:ss',
	    ],
	    '组件2' => [
	        'version' => xx.xx.xx,
	        'time' => 'YYYY-mm-dd HH:ii:ss',
	    ]
	    ...
    ],
]
*/
```

## 获取本地Windwork最后发布时间
遍历组件获取最新发布时间。
可选是否从缓存读取

```
$releaseTime = \wf\core\Version::getReleaseTime();
```

## composer中关于版本的参数
 - version 组件版本，每个组件分别保存发布版本，\wf\core\Version::VERSION 只保存精确到一位小数的大版本号。
 - time 组件最后更新时间，\wf\core\Version支持汇总所有组件的最后发布时间。

## 获取远程（GitHub上）Windwork所有组件的版本信息

```
$componentsVersion = \wf\core\Version::getRemoteComponentsVersion();

/*
// 返回类型为
$componentsVersion = [
    'release' => '所有组件中最大的time参数',
    'component' => [
	    '组件1' => [
	        'version' => xx.xx.xx,
	        'time' => 'YYYY-mm-dd HH:ii:ss',
	    ],
	    '组件2' => [
	        'version' => xx.xx.xx,
	        'time' => 'YYYY-mm-dd HH:ii:ss',
	    ]
	    ...
    ],
]
*/
```
## 获取远程Windwork组件最后发布时间
可选是否从缓存读取

```
// 所有组件在GitHub上最后发布的时间
$remoteLatestReleaseTime = \wf\core\Version::getRemoteReleaseTime();
```

