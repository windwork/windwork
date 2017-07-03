
<nav> 
  <ul class="pagination mobile">
    <!-- 头页 -->
    <li class="paging-first"><a href="<?php echo $this->getPageUrl(1)?>"><span>头页</span></a></li>
        
    <!-- 前页 -->
    <?php if($this->prePage) { ?>
    <li class="paging-pre"><a href="<?php echo $this->getPageUrl($this->prePage)?>"><span>上一页</span></a></li>
    <?php } else { ?>
    <li class="paging-pre empty"><a href="javascript:;"><span>上一页</span></a></li>
    <?php } ?>
    
    <!-- 当前页/总页数 -->
    <li class="paging-page-num"><a href="javascript:;" title="当前页/总页数"><span><?php echo $this->page?>/<?php echo $this->lastPage?></span></a></li>
        
    <!-- 后页 -->
    <?php if($this->nextPage) { ?>
    <li class="paging-next"><a href="<?php echo $this->getPageUrl($this->nextPage)?>"><span>下一页</span></a></li>
    <?php } else { ?>
    <li class="paging-next empty"><a href="javascript:;"><span>下一页</span></a></li>
    <?php } ?>
        
    <!-- 尾页 -->
    <li class="paging-last"><a href="<?php echo $this->getPageUrl($this->lastPage)?>"><span>尾页</span></a></li>
  </ul>
</nav>