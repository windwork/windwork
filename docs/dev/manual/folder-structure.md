目录机构
===============

目录相关常量
-------------------
 * ROOT_DIR 站点根目录 
 
框架目录结构-基础模式
-------------------
网站入口和源码文件在同一个文件夹
```
--+
  |- config    配置文件目录（禁止网络访问）
  |- data      站点动态数据保存目录（禁止网络访问）
  |- language  语言文件夹（禁止网络访问）
  |- libs      第三方库（禁止网络访问）
  |- app       应用源码目录（禁止网络访问）
  |    |- common 公用模块
  |    |    |- controller 应用文件夹
  |    |    |- model      应用文件夹
  |    |    |- hook       钩子文件夹
  |    |    |- service    服务文件夹
  |    |    |- test       单元测试目录
  |    |- module_name 模块名
  |    |    |- controller 应用文件夹
  |    |    |- model      应用文件夹
  |    |    |- hook       钩子文件夹
  |    |    |- service    服务文件夹
  |    |    |- test       单元测试目录
  |- wf       系统核心框架（Windwork Framework，如果通过composer安装则没有这个文件夹）
  |- static   静态文件目录（允许网络访问）
  |- storage  附件存贮目录（允许网络访问）
  |- template 模板目录（禁止网络访问）
  |- theme    模板主题样式（允许网络访问）
  |- vendor   composer源码目录（禁止网络访问）
  |- index.php 系统入口（允许网络访问）
```


框架目录结构-高级模式（强烈推荐使用此方式）
-------------------
网站入口与源码分离
```
--+
  |- config    配置文件目录
  |- data      站点动态数据保存目录
  |- language  语言文件夹
  |- libs      第三方库
  |- app       应用源码目录
  |    |- common 公用模块
  |    |    |- controller 应用文件夹
  |    |    |- model      应用文件夹
  |    |    |- hook       钩子文件夹
  |    |    |- service    服务文件夹
  |    |    |- test       单元测试目录
  |    |- module_name 模块名
  |    |    |- controller 应用文件夹
  |    |    |- model      应用文件夹
  |    |    |- hook       钩子文件夹
  |    |    |- service    服务文件夹
  |    |    |- test       单元测试目录
  |- template 模板目录
  |- vendor   composer源码目录
  |- wf       系统核心框架（Windwork Framework，如果通过composer安装则没有这个文件夹）
  |- public   网络可访问的目录
  |    |- static    静态文件目录
  |    |- storage   附件存贮目录
  |    |- theme     模板主题样式
  |    |- index.php 系统入口
```

不分模块目录结构
-------------------
我们可以在config/url.php中设置useModule参数来确定是否启用模块。
useModule = false 启用模块，即不划分模块，所有控制器都放在同一个目录中
```
--+
  |- config     配置文件目录
  |- data       站点动态数据保存目录
  |- language   语言文件夹
  |- libs       第三方库
  |- app        系统源码目录
  |    |- controller 应用文件夹
  |    |- model      应用文件夹
  |    |- hook       钩子文件夹
  |    |- service    服务文件夹
  |    |- test       单元测试目录
  |    |- view   模板目录 （模板文件夹可自定义存放目录）
  |- vendor   composer源码目录
  |- wf       系统核心框架（Windwork Framework，如果通过composer安装则没有这个文件夹）
  |- public   网络可访问的目录
  |    |- static    静态文件目录
  |    |- storage   附件存贮目录
  |    |- theme     模板主题样式
  |    |- index.php 系统入口
```

模块目录结构
-----------------------
```
./app +
      |- module1Name
      |- module1Name
      |- module3Name
```
