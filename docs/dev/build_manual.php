<?php
/**
 * 从Markdown生成html帮助文档的工具
 */
require __DIR__ . '/lib/Parsedown.php';

// 项目根目录
define('ROOT_DIR', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

global $manualDir;

$dir = 'manual/';
$manualDir = __DIR__ . '/../manual/';
clearDirs($manualDir);

// 
$docList = [
    // 组件
    'src/cache/README.md'               => 'wf.cache.html',
    'src/captcha/README.md'             => 'wf.captcha.html',
    'src/crypt/README.md'               => 'wf.crypt.html',
    'src/db/README.md'                  => 'wf.db.html',
    'src/db/docs/querybuilder.md'       => 'wf.db.querybuilder.html', // TODO
    'src/db/docs/finder.md'             => 'wf.db.finder.html', // TODO
    'src/image/README.md'               => 'wf.image.html',
    'src/logger/README.md'              => 'wf.logger.html',
    'src/mailer/README.md'              => 'wf.mailer.html',
    'src/model/README.md'               => 'wf.model.html',
    'src/pager/README.md'               => 'wf.pager.html',
    'src/route/README.md'               => 'wf.route.html',
    'src/storage/README.md'             => 'wf.storage.html',
    'src/template/README.md'            => 'wf.template.html',
    
    // core    
    'src/core/docs/benchmark.md'        => 'wf.core.benchmark.html',
    'src/core/docs/config.md'           => 'wf.core.config.html',
    'src/core/docs/hook.md'             => 'wf.core.hook.html',  // TODO Hook && HookInterface
    'src/core/docs/i18n.md'             => 'wf.core.i18n.html',
    'src/core/docs/loader.md'           => 'wf.core.loader.html', // TODO
    'src/core/docs/servicelocator.md'   => 'wf.core.servicelocator.html',
    'src/core/docs/session.md'          => 'wf.core.session.html',
    'src/core/docs/version.md'          => 'wf.core.version.html',
    
    // web
    'src/web/README.md'                 => 'wf.web.html', //
    'src/web/docs/application.md'       => 'wf.web.application.html',
    'src/web/docs/controller.md'        => 'wf.web.controller.html',
    'src/web/docs/message.md'           => 'wf.web.message.html',
    'src/web/docs/mvc.md'               => 'wf.web.mvc.html',
    'src/web/docs/request.md'           => 'wf.web.request.html',
    'src/web/docs/response.md'          => 'wf.web.response.html',
    'src/web/docs/restful.md'           => 'wf.web.restful.html',
    
    // util
    'src/util/README.md'                => 'wf.util.html',
    'src/util/docs/validator.md'        => 'wf.util.validator.html',
    
    // other（TODO more）
    'docs/dev/manual/README.md'            => 'index.html',
    'docs/dev/manual/about.md'             => 'about.html',
    'docs/dev/manual/coding-standard.md'   => 'coding-standard.html',
    'docs/dev/manual/concept.md'           => 'concept.html',
    'docs/dev/manual/folder-structure.md'  => 'folder-structure.html',
    
];

// res
// 需要复制的文件/文件夹
$resList = [
    'src/captcha/assets/example-1.png' => 'assets/example-1.png',
    'src/captcha/assets/example-2.jpg' => 'assets/example-2.jpg',
];

cpDir("{$dir}res/", "{$manualDir}/res/");

foreach ($resList as $srcRes => $distRes) {
    $distResFile = $manualDir . $distRes;
    if (!is_dir(dirname($distResFile))) {
        mkdir(dirname($distResFile), 0755, true);
    }
    @copy(ROOT_DIR . $srcRes, $distResFile);
}

$catalog = (parseMarkDown('docs/dev/manual/catalog.md', 0))['html'];

// 解析markdown文档
foreach ($docList as $mdFile => $distHtml) {
    print "parse {$mdFile}\n";
    
    $parsed = parseMarkDown($mdFile, $catalog);
    if (!$parsed) {
	    continue;
	}
	
	$html = '<!DOCTYPE html>
<html>
  <head>
  <title>' . $parsed['title'] . '</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="res/style.css" />
<body>
  <div class="wrapper">
    <div class="main">' . $parsed['html']. '</div>
    <aside class="catalog">' . $catalog . '</aside>
    <div class="clearfix"></div>
    <footer> &copy ' . date('Y') . ' windwork</footer>
  </div>
  <link rel="stylesheet" href="res/highlight/styles/default.css">
  <script src="res/highlight/highlight.pack.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
</body>
</html>';
	
	file_put_contents($manualDir . $distHtml, $html);
	
	print "parse {$mdFile} ok\n";
}

function parseMarkDown($mdFile)
{
    $mdRealFile = ROOT_DIR . $mdFile;
    if (!is_file($mdRealFile)) {
        return;
    }
    
    $parsed = [];
    $mdText = file_get_contents($mdRealFile);
    
    $prefix = '';
    if(substr($mdFile, 0, 3) == 'src/') {
        $prefix = preg_replace("/src\\/([0-9a-z]+)\\/.*/i", "wf.\\1", $mdFile);
    }
    
    // 提取标题
    $parsed['title'] = trim(preg_replace("/(.*?)\n.+/s", "\\1", trim($mdText)));
        
    // 处理链接
    // [xxx](xxx.md)
    $mdText = preg_replace("/\\]\\(([0-9a-z\\.\\-_]+)\\.md\\)/i", "](\\1.html)", $mdText);
    
    // 组件docs目录下的md文件链接到组件根目录下的README.md
    // [xxx](../README.md)  => [xxx](wf.xx.html)
    $mdText = str_replace('](../README.md)', "]({$prefix}.html)", $mdText);
    
    // 组件根目录下的README.md链接docs文件夹下的md文档
    // [xxx](docs/xxx.md)  => [xxx](wf.xx.xxx.html)
    $mdText = preg_replace("/\\]\\(docs\\/([0-9a-z\\.\\-_]+)\\.md\\)/i", "]({$prefix}.\\1.html)", $mdText);
    
    // 跨组件链接到组件根目录下的README.md
    // [xxx](https://github.com/windwork/wf-{COMPONENT})  => [xxx](wf.xx.html)
    $mdText = preg_replace("/\\]\\(https\\:\\/\\/github\\.com\\/windwork\\/wf\\-([0-9a-z]+)\\)/i", "](wf.\\1.html)", $mdText);
    
    // 跨组件链接到docs文件夹下的.md文档
    // [xxx](https://github.com/windwork/wf-{COMPONENT}/blob/master/docs/xxx.md)  => [xxx](wf.xx.xxx.html)
    $mdText = preg_replace("/\\]\\(https\\:\\/\\/github\\.com\\/windwork\\/wf\\-([0-9a-z]+)\\/blob\\/master\\/docs\\/([0-9a-z\\.]+?)\\.md\\)/i", "](wf.\\1.\\2.html)", $mdText);
    
    $parsedown = new Parsedown();
    $parsed['html']= $parsedown->text($mdText);
    
    return $parsed;
}

print "完成";
print "\n" . date('Y-m-d H:i:s');


/**
 * 删除文件夹的全部内容
 *
 * @param string $dir 目录
 */
function clearDirs($dir) {
	if(!$dir || !$d = dir($dir)) {
		return;
	}

	while (false !== ($entry = @$d->read())) {
		if($entry[0] == '.') {
			continue;
		}
		if (is_dir($dir.'/'.$entry)) {
			clearDirs($dir.'/'.$entry, true);
		} else {
			@unlink($dir.'/'.$entry);
		}
	}
		
	@$d->close();
}

function cpDir($srcDir, $distDir) {
	if(!$srcDir || !$d = dir($srcDir)) {
		return;
	}

	$srcDir = rtrim($srcDir, '/\\') . '/';
	$distDir = rtrim($distDir, '/\\') . '/';
	
	if(!is_dir($distDir)) {
		@mkdir($distDir, true);
		print "mkdir $distDir\n";
	}
	
	while (false !== ($entry = @$d->read())) {
		if($entry[0] == '.') {
			continue;
		}
		if (is_dir($srcDir.$entry)) {
			cpDir($srcDir.$entry, $distDir.$entry);
		} else {
			copy($srcDir.$entry, $distDir.$entry);

			print mb_convert_encoding("copy {$srcDir}{$entry}\n", 'UTF-8', 'GBK');
		}
	}
		
	@$d->close();
}