# CSH架构模式
Windwork框架使用CCH（核心Core + 组件Component + 钩子Hook）架构模式，达到易用、松耦合、高性能、高可扩展的目的。


## Core（核心）


## Component（组件）
同一个组件有时候需要根据环境或者条件的不同选择不同的算法或者策略来完成工作，因此我们采用策略设计模式，给该组件定义一系列的算法，把它们一个个封装起来，并且使它们可以互相替换。例如缓存组件，我们可以选择使用文件缓存、也可以使用memcache或Redis缓存服务器缓存内容，而更换缓存存储方式后，我们在应用中使用的缓存操作的API是不需要更改的。这样每个组件都具备了高可扩展的特性。

支持策略模式的组件：
  - \wf\cache 缓存组件
  - \wf\captcha 验证码组件
  - \wf\crypt 加密解密组件
  - \wf\db 数据库操作组件（支持PDOMySQL、MySQLi）
  - \wf\image 图片处理，生成缩略图和打水印
  - \wf\logger 日志记录
  - \wf\mailer 邮件发送
  - \wf\route  路由
  - \wf\storage 存储
  - \wf\template 模板引擎

## Hook（钩子）

