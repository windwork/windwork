<?php 
// 显示的第1个翻页链接页数
$numFirst = $this->page - 5;
if ($numFirst < 1) {
    $numFirst = 1;
}

// 显示的最后一个翻页链接页数
$numLast = $numFirst + 9;
if ($numLast > $this->lastPage) {
    $numLast = $this->lastPage;
}

if ($numLast < 1) {
    $numLast = 1;
}

if ($numLast - $numFirst < 9) {
    $numFirst = $numLast - 9;
    if ($numFirst < 1) {
        $numFirst = 1;
    }
}
?>
<nav>
  <ul class="pagination simple">    
    <!-- 上页 -->
    <?php if($this->prePage):?>
    <li class="paging-pre"><a href="<?php echo $this->getPageUrl($this->prePage) ?>"><span>«</span></a></li>
    <?php else: ?>
    <li class="paging-pre empty"><a href="javascript:;" title="上一页"><span>«</span></a></li>
    <?php endif; ?>
    <!-- /上页 -->
  
    <?php if($numFirst > 1):?>
    <!-- 头页 -->
    <li class="paging-first"><a href="<?php echo $this->getPageUrl(1) ?>"><span>1...</span></a></li>
    <!-- /头页 -->
    <?php endif; ?>
    
    <!-- 显示翻页页码 -->
    <?php 
    if ($this->lastPage > 1) {
        for ($i = $numFirst; $i <= $numLast; $i++) {
            $current = $i == $this->page ? 'current' : '';
            $url = $this->getPageUrl($i);;
            echo "<li class=\"page-num {$current}\"><a href=\"{$url}\"><span>{$i}</span></a></li>";
        }
    }
    ?>
    <!-- /显示翻页页码 -->
    
    <?php if($this->lastPage > $numLast):?>
    <!-- 尾页 -->
    <li class="paging-last"><a href="<?php echo $this->getPageUrl($this->lastPage) ?>"><span>...<?php echo $this->lastPage?></span></a></li>
    <!-- /尾页 -->
    <?php endif; ?>
  
    <!-- 下一页 -->
    <?php if($this->nextPage):?>
    <li class="paging-next"><a href="<?php echo $this->getPageUrl($this->nextPage) ?>" title="下一页"><span>»</span></a></li>
    <?php else: ?>
    <li class="paging-next empty"><a href="javascript:;"><span>»</span></a></li>
    <?php endif; ?>
    <!-- /下一页 -->
    
  </ul>
</nav>