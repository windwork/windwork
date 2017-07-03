windwork 分页组件
===================

通过设置总记录数和每页显示记录数，计算出分页导航参数并可生成分页导航html。
我们提供了3种不同的分页显示模板，如果不能满足你的需要，可自己编写分页模板。

## 使用案例
```

// $userObj = new \app\user\model\UserModel();
// $total = $userObj->find()->count(); // 总用户数

$total = 125; // 总共记录数
$rows  = 20; // 每页显示记录数
$pager = new \wf\pager\Pager($total, 12);
// 在Windwork控制器中使用 $pager = pager($total, $rows);

// 让sql知道从第几行获取数据，获取多少行
// $userObj->find()->fetchAll($pager->offset, $pager->rows);

// 显示分页导航条
echo $pager->getHtml();

```

## 分页条显示风格
可以选择使用不同风格显示导航条
- simple  默认简介风格导航
- mobile  使用于手机界面的导航
- complex 复杂导航，一般用在管理后台


### 方式1、在构造函数中指定模板风格
```
// 导航选择使用模板
$tpl = 'simple'; // simple|mobile|complex

$pager = new \wf\pager\Pager($total, 12, '', ['tpl' => $tpl]);
$pager->getHtml()

```

### 方式2、在模板中选择主题风格

当我们视图手机版和电脑版分开的时候，可以在视图中直接调用不同风格的分页导航条以适应不同的客户端。

```
// 控制器中
$pager = new \wf\pager\Pager($total, 10);

// PC版视图中使用默认分页导航条
<div>{$pager->getHtml('complex')}</div>

// 手机版视图中使用手机分页导航条
<div>{$pager->getHtml('mobile')}</div>

```

## 分页样式案例

```

<style>
.pagination { margin: 20px auto; padding: 0; height: 36px; font-size: 14px; }
.pagination li { display: inline; }
.pagination li a { display: inline; float: left; line-height: 36px; margin-left: -1px; padding: 0 12px; border: 1px solid #e0e0e0; position: relative; text-decoration: none; }
.pagination li a:hover { background: #f8f8f8; }
.pagination li.current a {background: #f8f8f8;}
.pagination li a span { color:#2a6496; }
.pagination li.current a span {color: #000; font-weight: bold; }
.pagination li.paging-select { line-height: 36px; float: right; color:#999; }
.pagination li.paging-select span { font-size: 12px; }
.pagination li.paging-select select { border: 1px solid #e0e0e0; height: 28px; padding: 0 3px; }

.pagination.mobile { font-size: 0; text-align: center; }
.pagination.mobile li { display: inline-block; }
.pagination.mobile li a span { font-size: 14px; }
</style>

```

## 高级功能
### 1、自定义分页参数
可配置参数：
```
$args = [
    'argSeparator'   => '&',      // 参数分隔符号
    'valSeparator'   => '=',      // 参数变量名和值的分隔符
    'pageVar'        => 'page',   // 分页页码的url请求变量名
    'rowsVar'        => 'rows',   // 每页行数的url请求变量名
    'rows_max'       => 100,      // 每页允许最多记录数
    'tpl'            => 'simple', // 默认分页样式，mobile）手机分页, simple）简单分页, complex）复杂分页
];
```

```
$pager = new Pager(200, 10);
$pager->uri = 'http://localhost/demo/xx';

// 得到默认格式的分页链接
// http://localhost/demo/xx?rows=10&page=2
$url = $pager->getPageUrl(2); 

// 通过设置参数获得自定义的分页变量分隔符
$args = [
    'argSeparator'   => '/',  // 参数分隔符号
    'valSeparator'   => ':', // 变量和值的分隔符
];
$pager = new Pager(200, 10, '', $args);
$pager->uri = 'http://localhost/demo/xx';

// 得到个性化格式的分页链接： 
// http://localhost/demo/xx/rows:10/page:2
$url = $pager->getPageUrl(2); 

```


<br />  
<br />  

### 要了解更多？  
> - [官方完整文档首页](http://docs.windwork.org/manual/)  
> - [官方源码首页](https://github.com/windwork)  
