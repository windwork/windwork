Windwork编码规范
=========================
编码标准对任何开发项目都很重要，特别是很多开发者在同一项目上工作。编码标准帮助确保代码的高质量、少 bug 和容易维护。
约束级别分别为：【必须】、【建议】、【可以】、【禁止】

## 1、PHP File 文件格式
### 1.1、编码
文件统一保存为 Unicode（UTF-8）编码格式，不要使用BOM签名。

### 1.2、常规
对于只包含有 PHP 代码的文件，结束标志（”?>”）是不允许存在的，PHP自身不需要（”?>”）, 这样做, 可以防止它的末尾被意外地注入响应。

### 1.3、缩进
缩进由四个空格组成，如果使用制表符 TAB则必须把TAB设置为四个空格，不能设置则不允许使用TAB 。

### 1.4、行的最大长度
一行 80 字符以内是比较合适，就是说，开发者应当努力在可能的情况下保持每行代码少于 80 个字符，在有些情况下，长点也可以, 最多尽可能不要超过 120 个字符。
主要考虑显示器（一行显示多页）、终端、打印的显示效果方便阅读。

### 1.5、行结束标志
行结束标志遵循 Unix 文本文件的约定，行必需以单个换行符（LF）结束。换行符在文件中表示为 10，或16进制的 0x0A。

注：不要使用 苹果操作系统的回车（0x0D）或 Windows 电脑的回车换行组合如（0x0D,0x0A）。

## 2、命名约定

因部分数据库服务器设置强制转换表字段名为小写，因此表字段相关变量可使用下划线，其他都使用驼峰格式。
【可以】使用缩写，但【建议】使用完整英文拼写，【禁止】使用完全不规范的缩写，避免望文不知义。

### 2.1、命名空间
核心框架命名空间以 wf开头，文件放在 ./wf 文件夹中。
命名空间【必须】全部是小写，统一使用单数形式。
模块中的类命名空间以module开头，相对于src目录；如 app/user/model/UserModel.php文件中的类命名空间为 namespace module\user\model;
```
模块命名空间：
namespace module\{$mod}\model; 模型的命名空间
namespace module\{$mod}\controller; 控制器的命名空间
namespace module\{$mod}\hook; 钩子类的命名空间
```

### 2.2、类
类名只允许有字母数字字符，在大部分情况下不鼓励使用数字。
如果类名包含多个单词，每个单词的第一个字母必须大写，连续的大写是不允许的，例如 “MYCLASS” 是不允许的，而应该是MyClass。
类名统一使用单数形式，如果有复数含义，类名可以使用复数形式。

### 2.3、文件名

PHP文件名与其中的类名后加上.php构成。
```
wf/Application.php
wf/Config.php
app/user/controller/AccountController.php
app/system/controller/admin/NavController.php
```

### 2.4、函数和方法
函数名只包含字母数字字符，下划线是不允许的。数字是允许的但大多数情况下不鼓励。
函数名总是以小写开头，当函数名包含多个单词，除第一个词外每个词的首字母必须大写，这就是所谓的 “驼峰” 格式。
我们一般鼓励使用冗长的名字，函数名应当长到足以说明函数的意图和行为。

这些是可接受的函数名的例子：
``` 
filterInput()
getElementById()
widgetFactory() 
```

对于面向对象编程，实例或静态变量的访问器总是以 “get” 或 “set” 为前缀。在设计模式实现方面，如单态模式（singleton）或工厂模式（factory）， 方法的名字应当包含模式的名字，这样名字更能描述整个行为。

全局函数 (如：”floating functions”) 允许但大多数情况下不鼓励，建议把这类函数封装到静态类里。

### 2.5、变量

变量只包含数字字母字符，大多数情况下不鼓励使用数字，不使用下划线。
和函数名一样，变量名总以小写字母开头并遵循“驼峰式”命名约定。
我们一般鼓励使用冗长的名字，这样容易理解代码，开发者知道把数据存到哪里。除非在小循环里，不鼓励使用简洁的名字如 “$i” 和 “$n” 。如果一个循环超过 20 行代码，索引的变量名必须有个具有描述意义的名字。

### 2.6、常量

常量包含数字字母字符和下划线，数字允许作为常量名。
常量名的所有字母必须大写。
常量中的单词必须以下划线分隔，例如可以这样 EMBED_SUPPRESS_EMBED_EXCEPTION 但不许这样 EMBED_SUPPRESSEMBEDEXCEPTION。
常量必须通过 “const” 定义为类的成员，强烈不鼓励使用 “define” 定义的全局常量。

## 3、编码风格
### 3.1、PHP 代码划分（Demarcation）

PHP 代码总是用完整的标准的 PHP 标签定界：
```
<?php

?> 
```

短标签（<?）是不允许的，只包含 PHP 代码的文件，不要?>结束标签 。

### 3.2、字符串
### 3.2.1、字符串文字
当字符串是文字(不包含变量)，应当用单引号（apostrophe）来括起来：
$a = 'Example String';

### 3.2.2、包含单引号（'）的字符串文字
当文字字符串包含单引号（apostrophe）就用双引号括起来，特别在 SQL 语句中有用：
``` 
$sql = "SELECT id, nickname from `people` WHERE username='Fred' OR username='Susan'"; 
```
在转义单引号时，上述语法是首选的，因为很容易阅读。

### 3.2.3、变量替换
 变量替换有下面这些形式：

```
$greeting = "Hello $name, welcome back!";
$greeting = "Hello {$name}, welcome back!"; 
```

为保持一致，这个形式不允许：
```
// 不允许
$greeting = “Hello ${name}, welcome back!”;
```
### 3.2.4、字符串连接
字符串连接符的前后加上空格以提高可读性：
```
$company = $varA . $varB;
```

当用 “.” 操作符连接字符串，鼓励把代码可以分成多个行，也是为提高可读性。在这些例子中，每个连续的行应当由 whitespace 来填补，例如 “.” 和 “=” 对齐：
```
$sql = "SELECT uid, nickname FROM `people` " 
       . "WHERE groupid = 1 "
       . "ORDER BY uid ASC"; 
```

### 3.3、数组
### 3.3.1、数字索引数组

索引不允许为负数
建议数组索引从 0 开始。
当用 array 函数声明有索引的数组，在每个逗号的后面间隔空格以提高可读性：
```
$sampleArray = array(1, 2, 3, 'Windwork', 'Application'); 
```

可以用 “array” 声明多行有索引的数组，在每个连续行的开头要用空格填补对齐：
``` 
$sampleArray = array(
     1, 2, 3, 'wind', 'work',
     $a, $b, $c,
     56.44, $d, 500
); 
```

### 3.3.2、关联数组
当用声明关联数组，array 我们鼓励把代码分成多行，在每个连续行的开头用空格填补来对齐键和值：
```
$sampleArray = array(
     'firstKey'   => 'firstValue',
     'secondKey' => 'secondValue'
); 
```

## 3.4、类
### 3.4.1类的声明
 * 每个类要有命名空间；
 * 花括号和类名同一行，前面空一格。
 * 每个类必须有一个符合 PHPDocumentor 标准的文档块。
 * 类中所有代码【必需】用四个空格的缩进，【禁止】使用tab。
 * 每个 PHP 文件中【必须】只有一个类。
 * 抽象类名【建议】以Abstract或Base结尾；
 * 接口名【必须】以Interface结尾；
 * 如果使用到了设计模式，建议在类名中体现出具体模式。

放另外的代码到类里允许但不鼓励。在这样的文件中，用两行空格来分隔类和其它代码。

下面是个可接受的类的例子：
``` 
namespace demo;
/**
 * Documentation Block Here
 */
class SampleClass {
    // 类的所有内容
    // 必需缩进四个空格
} 
```

### 3.4.2、类成员变量
变量的声明必须在类的顶部，在方法的上方声明。
要用 private、 protected 或 public可见性，不用var。
直接访问 public 变量是允许的但不鼓励，最好使用访问器 （set/get）。

## 3.5、函数和方法
### 3.5.1、函数和方法声明

 * 在类中的函数必须用 private、 protected 或 public 声明它们的可见性。
 * 花括号在类、方法名下一行。
 * 函数名和括参数的圆括号中间没有空格。
 * 尽可能少用全局函数。

下面是可接受的在类中的函数声明的例子：
```
namespace demo;
/**
 * Documentation Block Here
 */
class Foo
{
    /**
     * Documentation Block Here
     */
    public function bar() 
    {
        // 函数的所有内容
        // 必需缩进四个空格
    }
} 
```

返回值不能在圆括号中，这妨碍可读性而且如果将来方法被修改成传址方式，代码会有问题。
```
namespace demo;

/**
 * Documentation Block Here
 */
class Foo 
{
    /**
     * 错误
     */
     public function bar() 
    {
         return($this->bar);
    }

    /**
     * 正确
     */
    public function bar() 
    {
        return $this->bar;
    }
} 
```

### 3.5.2、函数和方法的用法

函数的参数应当用逗号和紧接着的空格分开，下面可接受的调用的例子中的函数带有三个参数：
```
threeArguments(1, 2, 3); 
```

传址方式在调用的时候是严格禁止的，参见函数的声明一节如何正确使用函数的传址方式。

带有数组参数的函数，函数的调用可包括 “array” 提示并可以分成多行来提高可读性，同时，书写数组的标准仍然适用：
``` 
threeArguments(array(1, 2, 3), 2, 3);

threeArguments(array(1, 2, 3, 'wind', 'work',
      $a, $b, $c,
      56.44, $d, 500), 2, 3); 
```

## 3.6、控制语句
### 3.6.1、if/else/elseif

使用 if and elseif 的控制语句在条件语句的圆括号前后都必须有一个空格。

在圆括号里的条件语句，操作符必须用空格分开，鼓励使用多重圆括号以提高在复杂的条件中划分逻辑组合。

前花括号必须和条件语句在同一行，后花括号单独在最后一行，其中的内容用四个空格缩进。

``` 
if ($a != 2) {
    $a = 2;
} 
```

对包括”elseif” 或 “else”的 “if” 语句，和 “if” 结构的格式类似， 下面的例子示例 “if” 语句， 包括 “elseif” 或 “else” 的格式约定：
``` 
if ($a != 2) {
     $a = 2;
} else {
     $a = 7;
}


if ($a != 2) {
     $a = 2;
} elseif ($a == 3) {
     $a = 4;
} else {
     $a = 7;
} 
```

在有些情况下， PHP 允许这些语句不用花括号，但在我们的编码规范里，它们（”if”、 “elseif” 或 “else” 语句）必须使用花括号。

“elseif” 是允许的但强烈不鼓励，我们支持 “else if” 组合。

### 3.6.2、Switch

在 “switch” 结构里的控制语句在条件语句的圆括号前后必须都有一个单个的空格。

“switch” 里的代码必须有四个空格缩进，在”case”里的代码再缩进四个空格。
```
switch ($numPeople) {
     case 1:
         break;

     case 2:
         break;

     default:
         break;
} 
```

switch 语句应当有 default。

注： 有时候，在 falls through 到下个 case 的 case 语句中不写 break or return 很有用。 为了区别于 bug，任何 case 语句中，所有不写 break or return 的地方应当有一个 “// break intentionally omitted” 这样的注释来表明 break 是故意忽略的。

4、文档注释
-------------
所有文档块 (“docblocks”) 必须和 phpDocumentor 格式兼容，phpDocumentor 格式的描述超出了本文档的范围，关于它的详情，参考：» http://phpdoc.org/。

所有类文件必须在文件的顶部包含文件级 （”file-level”）的 docblock ，在每个类的顶部放置一个 “class-level” 的 docblock。下面是一些例子：

## 4.1、文件文档注释
每个文件必须在顶部添加文件说明
```
/**
 * Windwork
 * 
 * 一个高效的开源 PHP Web 开发框架
 * 
 * @copyright   Copyright (c) 2008-2016 Windwork Team. (http://www.windwork.org)
 * @license   http://opensource.org/licenses/MIT
 */
```

## 4.2、类文档注释
每个类名钱必须至少包含这些类说明 phpDocumentor 标签：
```
/**
 * 类的简述
 *
 * 类的详细描述 （如果有的话）
 * 
 * @package     wf.model
 * @author      cm <cmpan@qq.com>
 * @link        http://docs.windwork.org/manual/wf.model.html
 * @since	    1.0.0
 */
```
 * @package 命名空间把反斜杠换成.
 * @author 作者昵称 <邮箱>
 * @link   帮助文档URL，如果没有则不用写
 * @since  类从哪个版本开始有效

## 4.3、函数/方法

每个函数，包括对象方法，必须有最少包含下列内容的文档块（docblock）：

 * 函数的描述
 * 所有参数
 * 所有可能的返回值

因为访问级已经通过 “public”、 “private” 或 “protected” 声明， 不需要使用 “@access”。

如果函数/方法抛出一个异常，使用 @throws 于所有已知的异常类：
``` 
@throws ExceptionClass [description] 
```

## 5、语言结构关键词
语言结构关键词统一使用小写，包括 if/else/foreach/as/for/echo/do/while/class/and/or/include等。
更多关键词详见：http://php.net/manual/zh/reserved.keywords.php

