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
  <ul class="pagination complex">
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
    
    <li class="paging-select">
      <!-- 每页显示条数 -->
      <label class="paging-rows">
          <span>每页显示</span>
          <select size="1" onchange="window.location=this.value">
          <?php 
            for($i = 10; $i <= 100; $i+=10) { 
                $url = $this->getPageUrl(1, $i);
                if($i == $this->rows) { 
                    echo "<option value=\"{$url}\" selected=\"selected\">{$i}</option>";
                } else {
                    echo "<option value=\"{$url}\">{$i}</option>";
                }
            }
          ?>
          </select> 
          <span>条</span>
      </label>
      <label class="paging-goto">
          <span>跳到第 </span>
          <select size="1" onchange="window.location=this.value">
          <?php 
            for($i=1; $i<=$this->lastPage; $i++) {
                if($i > 1000000) {
                    $i += 49999;
                } elseif($i > 100000) {
                    $i += 4999;
                } elseif($i > 10000) {
                    $i += 499;
                } elseif($i > 1000) {
                    $i += 99;
                } elseif($i > 100) {
                    $i += 49;
                } elseif($i > 50) {
                    $i += 9;
                }
                
                $url = $this->getPageUrl($i);
                if ($i == $this->page) {
                  echo "<option value=\"{$url}\" selected=\"selected\">{$i}</option>\n";
                } else {
                  echo "<option value=\"{$url}\">{$i}</option>\n";
                }
            } 
          ?>
          </select>
          <span>页</span>
      </label>  
    </li>
  </ul>
</nav>