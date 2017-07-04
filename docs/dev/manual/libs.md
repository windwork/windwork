# 架构说明

## 目录结构

## 后端
使用Windwork框架


## 前端框架

 解决问题      | 使用组件/框架     | 支持手机 | IE兼容 | 说明  | 官网  
   --        |      --           | -- | --
JS框架       | jQuery            | ✔ | 1.x IE6+,2.x/3.x IE9+ | 提升JS开发效率 | http://jquery.com/
JS加载框架   | requirejs         | ✔ | IE6+ | js模块化编程 | https://github.com/requirejs/requirejs
UI框架       | bootstrap         | ✔ | 3.x IE8+hack | 提升页面UI开发效率 | [英文](http://getbootstrap.com/) / [中文](v3.bootcss.com)
数据绑定      | Vue               | ✔ | ? | 提升动态数据绑定效率 | https://cn.vuejs.org/v2/guide/syntax.html
文本编辑器    | KindEditor        | ✘ | IE6+ |编辑图文内容 | https://github.com/kindsoft/kindeditor
文本编辑器    | CKEditor          | ✔ | ? |编辑图文内容 | https://github.com/ckeditor/ckeditor-dev
markdown编辑器 | pen             | ✔ | -  | https://github.com/sofish/pen
提示信息、小弹窗 | noty            | ✔ | 2.x IE8+ | 显示服务器端响应数据或页面操作的提示信息 | 仅使用本地封装方法 Wind.message.show/showMessage/showError/showSuccess
模态对话框    | bootstrap.modal   | ✔ | IE8+hack  | 当需要再弹窗中显示表单时使用 | http://v3.bootcss.com/javascript/#modals
动态创建表单  | formbuilder       | ✔ | ？ | 服务器存储json格式数据，js显渲染示成输入框 | https://github.com/dobtco/formbuilder
表单输入验证  | jQuery.validation | ✔ | IE6+ | 验证表单数据类型 | https://jqueryvalidation.org/ 
延迟加载      | lazysizes        | ✔ | IE9+ | 图片、视频、iframe等页面元素延迟加载 | https://github.com/aFarkas/lazysizes
延迟加载图片  | jQuery.lazyload   | ✔ | IE6+ | 只支持延迟加载图片 | https://github.com/tuupola/jquery_lazyload
图表         | echarts           | ✔ | IE8+ | 柱状图、饼图等图表 | https://github.com/ecomfe/echarts
内联小图表    | jQuery Sparklines | ✔ | IE6+ | 在页面中嵌入小图表 | https://github.com/gwatts/jquery.sparkline/
智能输入框下拉框 |jQuery.chosen   | ？ | IE9+ |输入框从下拉菜单中输入/选择关键词 | https://harvesthq.github.io/chosen/
弹出层插件    | colorbox         | ✔ | IE6？IE7+ | 弹出窗口显示图片、文字等，可根据内容大小自动改变大小 | https://github.com/jackmoore/colorbox 、[Demo](http://www.jq22.com/yanshi223)
图片放大      | jQuery.zoom      | ✔ | IE7+ |  鼠标放到图片上，在指定位置显示鼠标位置放大图 | https://github.com/jackmoore/zoom
日期选择组件  | jQuery.pickadate | ✔  | IE8+ | 可选择年月日、时分，兼容手机 | https://github.com/amsul/pickadate.js
图片裁剪插件  | jQuery.cropper   | ？  | IE9+ | 裁剪图片前端处理 | https://github.com/fengyuanchen/cropper
图片裁剪插件  | jQuery.Jcrop     |     | IE6+ | IE8下可用该插件代替 jQuery.cropper | https://github.com/tapmodo/Jcrop
加载进度条    | nprogress        | ✔ | 显示页面加载进度状态 | https://github.com/rstacruz/nprogress
图片放大预览  | PhotoSwipe       | ✔ | 点击详细内容中的图片，放大预览翻页显示 |  https://github.com/nolimits4web/Swiper
拖动显示内容  | iscroll          | ✔ | 可多动大图、大div显示内容，可拖动图片上的框放大显示框内部分的图片，可自定义浏览器滚动条 | https://github.com/cubiq/iscroll
HTML5兼容    | html5shiv        | -  | IE6-9 | 用于解决旧版本IE不兼容HTML5的问题，支持H5基本功能 | https://github.com/aFarkas/html5shiv
min/max-width兼容| Respond      | -  | IE6-8 | 老版本浏览器支持min-width、max-width | https://github.com/scottjehl/Respond
js取色器     | jQuery.spectrum  | ✔ | IE6+  | 很好的html取色js插件 | https://github.com/bgrins/spectrum



其他工具
------------
- yuicompressor.jar
  > js/css压缩  
  > 在命令行下压缩js/css文件
  > http://yui.github.io/yuicompressor/
  

- JShrink 
  > PHP压缩js  
  > https://github.com/tedious/JShrink
  
- PHP压缩css 
  > $css = preg_replace(['/[\t\n\r]/', '/\/\*.+?\*\//'], ['',''], $css);

