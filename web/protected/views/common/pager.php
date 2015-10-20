<?php
    $start_num = ($page - 1) * $this->PAGE_SIZE + 1;
    $end_num = $page * $this->PAGE_SIZE;
?>
<div class="pagination pagination-large">
	<ul class="page_lists">
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $start_num - 1;?>">上一页</a>
		</li>
        <?php for($i=$start_num;$i<=$end_num;$i++):?>
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $i;?>"><?php echo $i;?></a>
		</li>
        <?php endfor;?>
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $end_num - 1;?>">下一页</a>
		</li>
	</ul>
</div>
<script type="text/javascript">
    $(function()){
        $(".pagination").bootstrapPaginator({currentPage: <?php echo $page;?>,totalPages: <?php echo $count;?>,numberOfPages:10});
        $(".page_lists > li > a").each(function(){
            var cur_url = window.location.href;
            var cur_page = $(this).attr(data-page);
            var forward_url = cur_url.replace("page=<?php echo $page;?>","page=" + cur_page);
        });
    }
</script>